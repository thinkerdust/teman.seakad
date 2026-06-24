<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Music extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'music';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'artist',
        'album',
        'genre',
        'mood',
        'language',
        'duration',
        'cover',
        'preview_url',
        'file',
        'status',
    ];

    /**
     * Get the invitations that use this background music.
     */
    public function invitations(): BelongsToMany
    {
        return $this->belongsToMany(Invitation::class, 'invitation_music');
    }
}
