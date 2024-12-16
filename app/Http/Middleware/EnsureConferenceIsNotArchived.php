<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Src\Domains\Conferences\Models\Conference;
use Symfony\Component\HttpFoundation\Response;

class EnsureConferenceIsNotArchived
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (is_string($request->route('conference'))) {
            $conference = Conference::where('slug', $request->route('conference'))->first();
        } else {
            $conference = $request->route('conference');

            abort_unless($conference instanceof Conference, 404, 'Conference not found');
        }

        if ($conference?->isArchived()) {
            abort(403, __('errors.Conference is archived'));
        }

        return $next($request);
    }
}
