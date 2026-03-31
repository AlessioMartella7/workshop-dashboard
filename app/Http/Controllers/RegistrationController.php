<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class RegistrationController extends Controller
{

	public function store(Workshop $workshop): RedirectResponse
	{
		try {
			DB::transaction(function () use ($workshop) {
				$workshop->lockForUpdate();

				if ($workshop->available_seats <= 0) {
					throw new \Exception('Spiacente, i posti per questo workshop sono esauriti.');
				}

				if ($workshop->users->contains(auth()->id())) {
					throw new \Exception('Sei già iscritto a questo workshop.');
				}

				$workshop->users()->attach(auth()->id());
			});

			return back()->with('success', 'Ti sei iscritto con successo!');

		} catch (\Exception $e) {
			return back()->with('error', $e->getMessage());
		}
	}
	public function destroy(Workshop $workshop): RedirectResponse
	{
		$workshop->users()->detach(auth()->id());

		return back()->with('success', 'Iscrizione annullata. Il tuo posto è ora disponibile per altri colleghi.');
	}
}