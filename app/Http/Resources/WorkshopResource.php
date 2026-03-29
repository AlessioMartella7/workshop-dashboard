<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkshopResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'date_display' => $this->scheduled_at->format('d M Y, H:i'),
			'date_iso' => $this->scheduled_at->toIso8601String(),
			'capacity' => $this->capacity,
			'available_seats' => $this->available_seats,
			'is_registered' => $this->users->contains(auth()->id()),
			'can_join' => $this->available_seats > 0 && !$this->users->contains(auth()->id()),
		];
	}
}
