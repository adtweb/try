<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Src\Domains\Conferences\Models\Conference;

class CsvController extends Controller
{
    public function thesesById(Conference $conference)
    {
        $conference->load([
            'theses' => function (HasManyThrough $query) {
                $query
                    ->whereIn('theses.id', request('theses', []))
                    ->with('section');
            },
        ]);

        $lang = $conference->abstracts_lang->value;

        $writer = SimpleExcelWriter::streamDownload('abstracts.csv');
        $writer->addHeader([
            'ID',
            'Заголовок',
            'Авторы',
            'Секция',
            'Форма доклада',
            'Приглашенный доклад',
            'Докладчик',
            'Контактное лицо',
            'Контактный email',
            'Дата подачи',
            'Дата последнего изменения',
        ]);

        foreach ($conference->theses as $thesis) {
            $authorsList = '';

            foreach ($thesis->authors as $author) {
                $authorsList .= "{$author['name_'.$lang]} {$author['surname_'.$lang]}, ";
            }

            $authors = collect($thesis->authors);

            $reporterId = $thesis->reporter['id'];
            $reporter = $authors->get($reporterId);
            if (! is_null($reporter)) {
                $reporterName = $reporter["name_$lang"].' ';

                if (! empty($reporter['middle_name_'.$lang])) {
                    $reporterName .= mb_substr($reporter['middle_name_'.$lang], 0, 1).'. ';
                }

                $reporterName .= $reporter['surname_'.$lang];
            } else {
                $reporterName = '';
            }

            $contactId = $thesis->contact['id'];
            $contact = $authors->get($contactId);
            if (! is_null($contact)) {
                $contactName = $contact["name_$lang"].' ';

                if (! empty($contact['middle_name_'.$lang])) {
                    $contactName .= mb_substr($contact['middle_name_'.$lang], 0, 1).'. ';
                }

                $contactName .= $contact['surname_'.$lang];
            } else {
                $contactName = '';
            }

            $writer->addRow([
                $thesis->thesis_id,
                $thesis->title,
                trim($authorsList, ', '),
                $thesis->section->slug,
                $thesis->report_form->value,
                $thesis->solicited_talk ? 'да' : 'нет',
                trim($reporterName),
                $contactName,
                $thesis->contact['email'],
                $thesis->created_at->format('d.m.Y H:i'),
                $thesis->updated_at->format('d.m.Y H:i'),
            ]);
        }

        $writer->toBrowser();
    }

    public function participationsById(Conference $conference)
    {
        $conference->load([
            'participations' => function ($query) {
                $query
                    ->whereIn('participations.id', request('participations', []));
            },
        ]);

        $writer = SimpleExcelWriter::streamDownload('participations.csv');
        $writer->addHeader([
            'Имя RU',
            'Фамилия RU',
            'Отчество RU',
            'Имя EN',
            'Фамилия EN',
            'Отчество EN',
            'email',
            'Телефон',
            'Аффилиации RU',
            'Аффилиации EN',
            'ORCID',
            'Сайт',
            'Тип участия',
            'Молодой ученый',
            'Дата создания',
            'Дата обновления',
        ]);

        foreach ($conference->participations as $participation) {
            $affiliationsEn = '';
            $affiliationsRu = '';

            foreach ($participation->affiliations as $affiliation) {
                $affiliationsEn .= "{$affiliation['title_en']}, ";
                $affiliationsRu .= "{$affiliation['title_ru']}, ";
            }

            $writer->addRow([
                $participation->name_ru,
                $participation->surname_ru,
                $participation->middle_name_ru,
                $participation->name_en,
                $participation->surname_en,
                $participation->middle_name_en,
                $participation->email,
                $participation->phone?->raw(),
                trim($affiliationsRu, ', '),
                trim($affiliationsEn, ', '),
                $participation->orcid_id,
                $participation->website,
                $participation->participation_type->value,
                $participation->is_young ? 'да' : 'нет',
                $participation->created_at->format('d.m.Y H:i'),
                $participation->updated_at->format('d.m.Y H:i'),
            ]);
        }

        $writer->toBrowser();
    }
}
