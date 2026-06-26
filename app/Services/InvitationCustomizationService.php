<?php

namespace App\Services;

use App\Models\Invitation;

class InvitationCustomizationService
{
    /**
     * Merge theme config with user customization and prepare rendering data.
     *
     * @param Invitation $invitation
     * @param array $themeConfig
     * @return array Contains 'themeConfig' and 'invitationData'
     */
    public function getCustomizedData(Invitation $invitation, array $themeConfig): array
    {
        $customization = $invitation->customization ?: [];
        $customStyle = $customization['custom_style'] ?? [];

        // 1. Override Colors in Theme Config
        if (!empty($customStyle['primary_color'])) {
            $themeConfig['design']['colors']['primary'] = $customStyle['primary_color'];
        }
        if (!empty($customStyle['secondary_color'])) {
            $themeConfig['design']['colors']['secondary'] = $customStyle['secondary_color'];
        }
        if (!empty($customStyle['accent_color'])) {
            $themeConfig['design']['colors']['accent'] = $customStyle['accent_color'];
        }

        // 2. Override Font Scale in Theme Config
        if (!empty($customStyle['font_scale'])) {
            $themeConfig['custom_style']['font_scale'] = $customStyle['font_scale'];
        }

        // 3. Override Background Option (plain vs texture)
        if (isset($customStyle['background_option']) && $customStyle['background_option'] === 'plain') {
            if (isset($themeConfig['assets']['background'])) {
                $themeConfig['assets']['background']['texture'] = null;
            }
        }

        // 4. Construct Clean Invitation Data with personalization fallback
        $recipientName = request()->query('to');

        $invitationData = [
            'title' => $invitation->title,
            'groom_name' => $invitation->groom_name,
            'bride_name' => $invitation->bride_name,
            'groom_nickname' => $invitation->groom_nickname ?: $invitation->groom_name,
            'bride_nickname' => $invitation->bride_nickname ?: $invitation->bride_name,
            'groom_photo' => $invitation->groom_photo ? asset($invitation->groom_photo) : null,
            'bride_photo' => $invitation->bride_photo ? asset($invitation->bride_photo) : null,
            'akad_date' => $invitation->akad_date ? $invitation->akad_date->toIso8601String() : null,
            'reception_date' => $invitation->reception_date ? $invitation->reception_date->toIso8601String() : null,
            'venue' => $invitation->venue,
            'address' => $invitation->address,
            'maps_url' => $invitation->maps_url,
            'description' => $invitation->description,
            'recipient_name' => $recipientName,
            'theme' => [
                'name' => $invitation->theme?->name,
                'slug' => $invitation->theme?->slug,
                'folder' => $invitation->theme?->folder,
            ],
            // Only visible gallery images
            'gallery' => $invitation->galleries->where('is_visible', true)->sortBy('sort')->map(function ($g) {
                return [
                    'id' => $g->id,
                    'image' => asset($g->image),
                    'sort' => $g->sort,
                ];
            })->values()->all(),
            // stories with image support
            'story' => $invitation->stories->sortBy('sort')->map(function ($s) {
                return [
                    'id' => $s->id,
                    'title' => $s->title,
                    'date' => $s->date,
                    'description' => $s->description,
                    'image' => $s->image ? asset($s->image) : null,
                    'sort' => $s->sort,
                ];
            })->values()->all(),
            'events' => $invitation->events->sortBy('date')->map(function ($e) {
                return [
                    'id' => $e->id,
                    'name' => $e->name,
                    'date' => $e->date ? $e->date->format('Y-m-d') : null,
                    'time' => $e->time,
                    'location' => $e->location,
                ];
            })->values()->all(),
            'music' => $invitation->music->first() ? [
                'title' => $invitation->music->first()->title,
                'artist' => $invitation->music->first()->artist,
                'file' => asset($invitation->music->first()->file),
            ] : [
                'title' => '',
                'artist' => '',
                'file' => '',
            ],
        ];

        return [
            'themeConfig' => $themeConfig,
            'invitationData' => $invitationData,
        ];
    }
}
