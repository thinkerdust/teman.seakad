<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'status',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke model Role (Many-to-Many).
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    /**
     * Relasi ke model Order (One-to-Many).
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relasi ke model UserSubscription (One-to-Many).
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    /**
     * Relasi ke model Invitation (One-to-Many).
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * Cache in-memory untuk roles dan permissions.
     */
    protected ?Collection $cachedRoles = null;

    protected ?Collection $cachedPermissions = null;

    /**
     * Periksa apakah user memiliki role tertentu.
     */
    public function hasRole(string $roleName): bool
    {
        if (is_null($this->cachedRoles)) {
            $this->cachedRoles = $this->roles->pluck('name');
        }

        return $this->cachedRoles->contains($roleName);
    }

    /**
     * Periksa apakah user memiliki permission dengan key tertentu.
     */
    public function hasPermission(string $permissionKey): bool
    {
        // Bypass jika user adalah admin utama atau memiliki role Superadmin
        if ($this->email === 'admin@teman-seakad.com' || $this->hasRole('Superadmin')) {
            return true;
        }

        if (is_null($this->cachedPermissions)) {
            $this->cachedPermissions = $this->roles()
                ->with('permissions')
                ->get()
                ->pluck('permissions')
                ->flatten()
                ->pluck('key')
                ->unique();
        }

        return $this->cachedPermissions->contains($permissionKey);
    }
}
