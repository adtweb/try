<?php

namespace Src\Domains\Conferences\Actions;

use Illuminate\Http\Request;
use Src\Domains\Conferences\Models\Thesis;

class CreateThesis
{
    public function handle(Request $request): Thesis
    {
        return Thesis::create([
            'participation_id' => $request->get('participation_id'),
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
