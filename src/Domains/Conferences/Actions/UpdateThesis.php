<?php

namespace Src\Domains\Conferences\Actions;

use Illuminate\Http\Request;
use Src\Domains\Conferences\Models\Thesis;

class UpdateThesis
{
    public function handle(Thesis $thesis, Request $request): void
    {
        $thesis->update([
            'section_id' => $request->get('section_id'),
            'report_form' => $request->get('report_form'),
            'solicited_talk' => $request->get('solicited_talk'),
            'title' => $request->get('title'),
            'authors' => $request->get('authors'),
            'reporter' => $request->get('reporter'),
            'contact' => $request->get('contact'),
            'text' => $request->get('text'),
        ]);
    }
}
