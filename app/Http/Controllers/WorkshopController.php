<?php

namespace App\Http\Controllers;

use OpenAI;
use App\Http\Resources\UserResource;
use App\Http\Resources\WorkshopResource;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkshopController extends Controller
{
	public function index(Request $request): Response
	{
		$workshops = Workshop::upcoming()->with('users')->get();

		return Inertia::render('Dashboard', [
			'workshops' => WorkshopResource::collection($workshops),
			'auth_user' => new UserResource($request->user()),
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

	public function generateDescription(Request $request)
	{
		$request->validate(['title' => 'required|string']);

		$client = OpenAI::factory()
			->withApiKey(env('GROQ_API_KEY'))
			->withBaseUri(env('GROQ_BASE_URL'))
			->make();

		$result = $client->chat()->create([
			'model' => 'llama-3.1-8b-instant', // Un modello veloce e gratuito su Groq'
			'temperature' => 0.7,
			'messages' => [
				['role' => 'system', 'content' => 'Sei un esperto di marketing. Scrivi una descrizione breve e accattivante (max 300 caratteri) per un workshop professionale.'],
				['role' => 'user', 'content' => "Il titolo del workshop è: {$request->title}"],
			],
		]);

		return response()->json([
			'description' => $result->choices[0]->message->content
		]);
	}

}
