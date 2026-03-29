<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['title', 'desciption', 'scheduled_at', 'capacity'])]

class Workshop extends Model
{
	/** @use HasFactory<\Database\Factories\WorkshopFactory> */
	use HasFactory, SoftDeletes;

	protected $cast = [
		'scheduled_at' => 'datetime',
	];

	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class)
			->withTimestamps();
	}
}
