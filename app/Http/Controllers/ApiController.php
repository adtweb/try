<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Domains\Conferences\Models\Affiliation;

class ApiController extends Controller
{
    public function affiliations(Request $request)
    {
        $affiliations = Affiliation::query()
            ->when($request->has('search'), function (Builder $query) use ($request) {
                $query->where(function ($builder) use ($request) {
                    $builder->where('title_ru', 'like', '%'.$request->get('search').'%')
                        ->orWhere('title_en', 'like', '%'.$request->get('search').'%');
                });
            })
            ->when($request->has('except'), function (Builder $query) use ($request) {
                $query->whereNotIn('id', $request->get('except'));
            })
            ->take($request->get('limit') ?? 50)
            ->get(['id', 'title_ru', 'title_en']);

        return response()->json($affiliations);
    }

    public function countries(Request $request)
    {
        $countries = Country::query()
            ->when($request->has('search'), function (Builder $query) use ($request) {
                $query->where(function ($builder) use ($request) {
                    $builder->where('name_ru', 'like', '%'.$request->get('search').'%')
                        ->orWhere('name_en', 'like', '%'.$request->get('search').'%');
                });
            })
            ->take($request->get('limit') ?? 10)
            ->get(['id', 'name_ru', 'name_en']);

        return response()->json($countries);
    }

    public function currentUser(): JsonResponse
    {
        $user = auth()->user();

        if (is_null($user)) {
            return response()->json();
        }

        $user->participant;

        return response()->json($user);
    }
}
