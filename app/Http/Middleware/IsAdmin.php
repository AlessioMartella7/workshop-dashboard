<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  Closure(Request): (Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		if (!$request->user() || !$request->user()->IsAdmin()) {
			return redirect()->route('dashboard')->with('error', 'Accesso negato: area riservata agli amministratori.');
		}

		return $next($request);
	}
}
