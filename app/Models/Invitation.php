<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'expired_at',
        'groom_name',
        'bride_name',
        'akad_date',
        'reception_date',
        'venue',
        'address',
        'maps_url',
        'description',
        'wedding_mood',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expired_at' => 'datetime',
            'akad_date' => 'datetime',
            'reception_date' => 'datetime',
        ];
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
    public function music(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Music::class, 'invitation_music');
    }
}
