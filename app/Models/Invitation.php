<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Invitation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'theme_id',
        'slug',
        'title',
        'status',
        'published_at',
        'expired_at',
        'groom_name',
        'bride_name',
        'groom_nickname',
        'bride_nickname',
        'groom_photo',
        'bride_photo',
        'akad_date',
        'reception_date',
        'venue',
        'address',
        'maps_url',
        'description',
        'wedding_mood',
        'customization',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'expired_at' => 'datetime',
            'akad_date' => 'datetime',
            'reception_date' => 'datetime',
            'customization' => 'array',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::saving(function ($invitation) {
            if ($invitation->isDirty('status') && $invitation->status === 'published') {
                if (empty($invitation->published_at)) {
                    $invitation->published_at = now();
                }

                // Set expired_at automatically to the subscription end_date if empty
                if (empty($invitation->expired_at)) {
                    $today = Carbon::today();
                    $activeSub = $invitation->user->subscriptions()
                        ->where('status', 'active')
                        ->where('start_date', '<=', $today)
                        ->where('end_date', '>=', $today)
                        ->first();

                    if ($activeSub) {
                        $invitation->expired_at = $activeSub->end_date;
                    }
                }
            } elseif ($invitation->isDirty('status') && $invitation->status === 'draft') {
                $invitation->published_at = null;
                $invitation->expired_at = null;
            }
        });

        static::deleting(function ($invitation) {
            // Clean up custom music files
            foreach ($invitation->music as $music) {
                if ($music->status === 'custom') {
                    $oldPath = str_replace('/storage/', '', $music->file);
                    Storage::disk('public')->delete($oldPath);
                    $music->delete();
                }
            }

            // Clean up gallery files
            foreach ($invitation->galleries as $gallery) {
                $oldPath = str_replace('/storage/', '', $gallery->image);
                Storage::disk('public')->delete($oldPath);
            }
        });
    }

    /**
     * Get the user who owns this invitation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the theme used for this invitation.
     */
    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    /**
     * Get the guests invited to this invitation.
     */
    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    /**
     * Get the visits logged for this invitation.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(InvitationVisit::class);
    }

    /**
     * Get the gallery images for this invitation.
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class)->orderBy('sort');
    }

    /**
     * Get the stories for this invitation.
     */
    public function stories(): HasMany
    {
        return $this->hasMany(Story::class)->orderBy('sort');
    }

    /**
     * Get the events for this invitation.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class)->orderBy('date');
    }

    /**
     * Get the background music chosen for this invitation.
     */
    public function music(): BelongsToMany
    {
        return $this->belongsToMany(Music::class, 'invitation_music');
    }
}
