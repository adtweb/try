<?php

namespace Src\Domains\Conferences\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Src\Domains\Conferences\Models\Conference;

class CreateConference
{
    public function handle(Request $request): Conference
    {
        $conference = Conference::create([
            'title_ru' => $request->validated('title_ru'),
            'title_en' => $request->validated('title_en'),
            'slug' => $request->validated('slug'),
            'organization_id' => $request->validated('organization_id'),
            'user_id' => auth()->id(),
            'conference_type_id' => $request->validated('conference_type_id'),
            'format' => $request->validated('format'),
            'with_foreign_participation' => $request->validated('with_foreign_participation'),
            'website' => $request->validated('website'),
            'need_site' => $request->validated('need_site'),
            'co-organizers' => $request->validated('co-organizers'),
            'address' => $request->validated('address'),
            'phone' => $request->validated('phone'),
            'email' => $request->validated('email'),
            'start_date' => $request->validated('start_date'),
            'end_date' => $request->validated('end_date'),
            'timezone' => $request->validated('timezone'),
            'description_ru' => $request->validated('description_ru'),
            'description_en' => $request->validated('description_en'),
            'lang' => $request->validated('lang'),
            'participants_number' => $request->validated('participants_number'),
            'report_form' => $request->validated('report_form'),
            'whatsapp' => $request->validated('whatsapp'),
            'telegram' => $request->validated('telegram'),
            'price_participants' => $request->validated('price_participants'),
            'price_visitors' => $request->validated('price_visitors'),
            'discount_students' => $request->validated('discount_students'),
            'discount_participants' => $request->validated('discount_participants'),
            'discount_special_guest' => $request->validated('discount_special_guest'),
            'discount_young_scientist' => $request->validated('discount_young_scientist'),
            'abstracts_price' => $request->validated('abstracts_price'),
            'abstracts_format' => $request->validated('abstracts_format'),
            'abstracts_lang' => $request->validated('abstracts_lang'),
            'max_thesis_characters' => $request->validated('max_thesis_characters'),
            'thesis_accept_until' => $request->validated('thesis_accept_until'),
            'thesis_edit_until' => $request->validated('thesis_edit_until'),
            'assets_load_until' => $request->validated('assets_load_until'),
            'thesis_instruction' => $request->validated('thesis_instruction'),
        ]);

        if ($request->file('image')) {
            $image = Image::read($request->file('image'))
                ->scaleDown(200, 200);

            $image = (string) $image->toWebp(85);
            $path = 'images/conferences/'.$conference->id.'/logo.webp';

            Storage::disk('s3')->put($path, $image);

            $conference->update(['logo' => $path]);
        }

        $conference->subjects()->sync($request->validated('subjects'));

        return $conference;
    }
}
