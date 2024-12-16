<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThesisAssetStoreRequest;
use Illuminate\Support\Facades\Storage;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\ThesisAsset;

class ThesisAssetController extends Controller
{
    public function index(Conference $conference)
    {
        $allowedSections = auth()->user()
            ->moderatedSections
            ->where('conference_id', $conference->id)
            ->pluck('id');

        $thesesIds = $conference
            ->load([
                'theses' => function ($q) use ($allowedSections) {
                    $q->select(['theses.id'])
                        ->when($allowedSections->isNotEmpty(), function ($collection) use ($allowedSections) {
                            return $collection->whereIn('section_id', $allowedSections->toArray());
                        });
                },
                'sections' => function ($query) use ($allowedSections) {
                    $query
                        ->when($allowedSections->isNotEmpty(), function ($collection) use ($allowedSections) {
                            return $collection->whereIn('id', $allowedSections->toArray());
                        })
                        ->select(['sections.id', 'slug', 'conference_id']);
                },
            ])
            ->theses
            ->pluck('id');

        $assets = ThesisAsset::whereIn('thesis_id', $thesesIds)
            ->with(['thesis' => fn ($q) => $q->select(['id', 'title', 'section_id', 'thesis_id'])])
            ->get(['id', 'thesis_id', 'title', 'path', 'is_approved']);

        return view('my.events.personal.thesis-assets', compact('conference', 'assets'));
    }

    public function store(Conference $conference, int $thesisId, ThesisAssetStoreRequest $request)
    {
        $path = $request->file('file')->store(
            'theses/assets/'.$thesisId, 's3'
        );

        $thesisAsset = ThesisAsset::create([
            'path' => $path,
            'title' => $request->get('title'),
            'thesis_id' => $thesisId,
        ]);

        return response()->json($thesisAsset);
    }

    public function destroy(Conference $conference, ThesisAsset $thesisAsset)
    {
        Storage::disk('s3')->delete($thesisAsset->path);

        $thesisAsset->delete();
    }

    public function updateApproved(Conference $conference, ThesisAsset $thesisAsset)
    {
        $thesisAsset->is_approved = request('is_approved');
        $thesisAsset->save();
    }
}
