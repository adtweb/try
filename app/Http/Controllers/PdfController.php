<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Src\Domains\Conferences\Models\Conference;
use Src\Domains\Conferences\Models\Section;
use Src\Domains\Conferences\Models\Thesis;
use Src\Domains\Schedule\Models\Schedule;

class PdfController extends Controller
{
    public function thesisPreview(Request $request, Conference $conference): Response
    {
        $authors = $request->json('authors');

        $sectionSlug = $conference->sections->isNotEmpty()
            ? '-'.$conference->sections->where('id', $request->json('section_id'))->first()?->slug
            : '';
        $thesisId = $conference->slug.$sectionSlug;

        $title = $request->get('title');
        $reporter = $request->json('reporter');
        $contact = $request->json('contact');
        $text = str($request->json('text'))->replace('<br>', ' ');
        $solicitedTalk = $request->json('solicited_talk');

        return PDF::loadView('pdf.thesis', compact('conference', 'authors', 'thesisId', 'title', 'reporter', 'contact', 'text', 'solicitedTalk'))
            ->download('abstracts.pdf');
    }

    public function thesisDownload(Conference $conference, Thesis $thesis): Response
    {
        if (! $conference->schedule_is_published && Gate::denies('updateAbstracts', $conference)) {
            abort(403);
        }

        $authors = $thesis->authors;
        $thesisId = $thesis->thesis_id;
        $title = $thesis->title;
        $reporter = $thesis->reporter;
        $contact = $thesis->contact;
        $text = $thesis->text;
        $solicitedTalk = $thesis->solicited_talk;

        return PDF::loadView('pdf.thesis', compact('conference', 'authors', 'thesisId', 'title', 'reporter', 'contact', 'text', 'solicitedTalk'))
            ->download($thesisId.'.pdf');
    }

    public function thesesDownload(Request $request, Conference $conference): Response
    {
        if (app()->isLocal()) {
            set_time_limit(300);
        }

        $theses = Thesis::whereIn('id', $request->json('theses'))
            ->orderBy('thesis_id')
            ->cursor();

        return PDF::loadView('pdf.theses', compact('conference', 'theses'))
            ->download($conference->slug.'-theses.pdf');
    }

    public function scheduleDownload(Conference $conference)
    {
        if (! $conference->schedule_is_published && Gate::denies('updateAbstracts', $conference)) {
            abort(403);
        }

        if (app()->isLocal()) {
            set_time_limit(300);
        }

        $sections = Section::where('conference_id', $conference->id)
            ->with([
                'scheduleItems' => fn ($q) => $q->orderBy('time_start')->orderBy('position')->with('scheduleItemTags'),
            ])
            ->get();

        // return view('pdf.schedule', compact('conference', 'sections'));

        return PDF::loadView('pdf.schedule', compact('conference', 'sections'))
            ->download($conference->slug.'-schedule.pdf');
    }
}
