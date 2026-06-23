<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'description',
        'folder',
        'status',
    ];

    /**
     * Get the invitations that use this theme.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }
}
