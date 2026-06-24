<?php

namespace App\Models;

use App\Services\SubscriptionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'customer_name',
        'phone',
        'email',
        'package_id',
        'quota',
        'price',
        'status',
        'start_date',
        'end_date',
        'notes',
        'user_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'price' => 'decimal:2',
            'quota' => 'integer',
            'package_id' => 'integer',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-'.date('Ymd').'-'.strtoupper(Str::random(5));
            }
        });

        static::saved(function ($order) {
            $order->resolveUserAndSubscription();
        });
    }

    /**
     * Resolve user account and user subscription for this order.
     */
    public function resolveUserAndSubscription(): void
    {
        // 1. Automatic User Account Creation
        if (in_array($this->status, ['confirmed', 'active']) && ! $this->user_id) {
            // Check if email already exists
            $user = User::where('email', $this->email)->first();

            if (! $user) {
                $password = Str::random(10);
                $user = User::create([
                    'name' => $this->customer_name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'password' => Hash::make($password),
                    'status' => 'active',
                ]);

                $userRole = Role::where('name', 'User')->first();
                if ($userRole) {
                    $user->roles()->sync([$userRole->id]);
                }

                // Store in session flash for UI display
                session()->flash('user_credentials', [
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $password,
                    'phone' => $this->formatted_phone,
                    'order_number' => $this->order_number,
                ]);
            }

            $this->user_id = $user->id;
            $this->saveQuietly();
        }

        // 2. Automatic User Subscription Creation / Update
        if (in_array($this->status, ['confirmed', 'active']) && $this->user_id && $this->start_date && $this->end_date) {
            $subscription = UserSubscription::where('order_id', $this->id)->first();

            if ($subscription) {
                app(SubscriptionService::class)->extendSubscription($subscription, $this->end_date->toDateString());
                $subscription->update([
                    'user_id' => $this->user_id,
                    'package_id' => $this->package_id,
                    'start_date' => $this->start_date,
                ]);
            } else {
                $user = $this->user ?? User::find($this->user_id);
                app(SubscriptionService::class)->createSubscription(
                    $user,
                    $this,
                    $this->package,
                    $this->start_date->toDateString(),
                    $this->end_date->toDateString()
                );
            }
        }

        // 3. Update status of subscription if order status is expired or cancelled
        if (in_array($this->status, ['expired', 'cancelled'])) {
            $subscription = UserSubscription::where('order_id', $this->id)->first();
            if ($subscription) {
                if ($this->status === 'expired') {
                    app(SubscriptionService::class)->expireSubscription($subscription);
                } else {
                    $subscription->update(['status' => $this->status]);
                }
            }
        }
    }

    /**
     * Clean and format phone number for WhatsApp API (start with 62 instead of 0).
     */
    public function getFormattedPhoneAttribute(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        return $phone;
    }

    /**
     * Get the user account associated with this order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package chosen for this order.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
