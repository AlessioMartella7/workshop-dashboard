<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkshopController extends Controller
{
	public function index(Request $request): Response
	{
		return Inertia::render('Dashboard', [
			'workshops' => Workshop::upcoming()
				->withCount('users')
				->get()
				->map(fn($workshop) => [
					'id' => $workshop->id,
					'title' => $workshop->title,
					'description' => $workshop->description,
					'scheduled_at' => $workshop->scheduled_at->format('d/m/Y H:i'),
					'capacity' => $workshop->capacity,
					'available_seats' => $workshop->available_seats,
					'is_registered' => $workshop->users->contains(auth()->id()),
				]),
			'can_manage' => $request->user()->isAdmin(),
		]);
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'description' => 'required|string',
			'scheduled_at' => 'required|date|after:now',
			'capacity' => 'required|integer|min:1',
		]);

		Workshop::create($validated);

		return back()->with('success', 'Workshop creato con successo!');
	}

	public function destroy(Workshop $workshop)
	{
		$workshop->delete();
		return back()->with('success', 'Workshop rimosso correttamente.');
	}
}
