<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Src\Domains\Conferences\Models\Conference;

class PageController extends Controller
{
    public function home(): View|Factory
    {
        $conferences = Conference::query()
            ->where('end_date', '>=', now()->startOfDay())
            ->with('subjects', 'organization')
            ->orderBy('start_date')
            ->take(10)
            ->get();

        return view('home', compact('conferences'));
    }
}
