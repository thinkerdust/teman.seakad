<?php

namespace App\Services;

use App\Models\Music;
use App\Models\Invitation;

class MusicRecommendationService
{
    /**
     * Berikan rekomendasi lagu berdasarkan undangan (atau theme folder/slug).
     *
     * @param Invitation $invitation
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function recommend(Invitation $invitation)
    {
        $themeFolder = $invitation->theme?->folder ?: ($invitation->theme?->slug ?: '');
        $moods = [];

        switch (strtolower($themeFolder)) {
            case 'floral-elegant':
            case 'floral':
                $moods = ['Romantic'];
                break;
            case 'luxury-gold':
            case 'luxury':
                $moods = ['Elegant', 'Luxury'];
                break;
            case 'islamic-wedding':
            case 'islamic':
                $moods = ['Islamic'];
                break;
            case 'rustic-forest':
            case 'rustic':
                $moods = ['Acoustic', 'Classic'];
                break;
            default:
                $moods = ['Romantic', 'Elegant'];
                break;
        }

        if ($invitation->wedding_mood) {
            if (!in_array($invitation->wedding_mood, $moods)) {
                array_unshift($moods, $invitation->wedding_mood);
            }
        }

        return Music::whereIn('mood', $moods)
            ->where('status', 'active')
            ->get();
    }
}
