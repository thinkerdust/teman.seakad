<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'theme' => $this->theme?->folder ?: ($this->theme?->slug ?: ''),
            'data' => [
                'title' => $this->title,
                'groom_name' => $this->groom_name,
                'bride_name' => $this->bride_name,
                'akad_date' => $this->akad_date ? $this->akad_date->toIso8601String() : null,
                'reception_date' => $this->reception_date ? $this->reception_date->toIso8601String() : null,
                'venue' => $this->venue,
                'address' => $this->address,
                'maps_url' => $this->maps_url,
                'description' => $this->description,
                'story' => $this->stories->sortBy('sort')->map(function ($story) {
                    return [
                        'id' => $story->id,
                        'title' => $story->title,
                        'date' => $story->date,
                        'description' => $story->description,
                        'sort' => $story->sort,
                    ];
                })->values()->all(),
            ],
            'gallery' => $this->galleries->sortBy('sort')->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'image' => $gallery->image,
                    'sort' => $gallery->sort,
                ];
            })->values()->all(),
            'events' => $this->events->sortBy('date')->map(function ($event) {
                return [
                    'id' => $event->id,
                    'name' => $event->name,
                    'date' => $event->date ? $event->date->format('Y-m-d') : null,
                    'time' => $event->time,
                    'location' => $event->location,
                ];
            })->values()->all(),
            'music' => $this->music?->file ?: '',
        ];
    }
}
