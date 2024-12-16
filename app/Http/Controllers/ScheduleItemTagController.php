<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleItemTagCreateRequest;
use App\Http\Requests\ScheduleItemTagDeleteRequest;
use Illuminate\Http\JsonResponse;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Section;
use Src\Domains\Schedule\Models\ScheduleItemTag;

class ScheduleItemTagController extends Controller
{
    public function store(
        Conference $conference,
        Section $section,
        ScheduleItemTagCreateRequest $request,
    ): JsonResponse {
        return response()->json(ScheduleItemTag::create($request->validated()));
    }

    public function destroy(
        Conference $conference,
        Section $section,
        ScheduleItemTag $scheduleItemTag,
        ScheduleItemTagDeleteRequest $request
    ): JsonResponse {
        $scheduleItemTag->delete();

        return response()->json();
    }
}
