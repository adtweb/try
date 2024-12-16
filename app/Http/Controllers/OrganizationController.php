<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganizationStoreRequest;
use App\Http\Requests\OrganizationUpdateRequest;
use Illuminate\Http\JsonResponse;
use Src\Domains\Auth\Models\Organization;

class OrganizationController extends Controller
{
    public function create()
    {
        return view('my.organization.create');
    }

    public function store(OrganizationStoreRequest $request): JsonResponse
    {
        //TODO logo saving

        $organization = Organization::create([
            'full_name_ru' => $request->full_name_ru,
            'short_name_ru' => $request->short_name_ru,
            'full_name_en' => $request->full_name_en,
            'short_name_en' => $request->short_name_en,
            'inn' => $request->inn,
            'address' => $request->address,
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
            'telegram' => $request->telegram,
            'type' => $request->type,
            'actions' => json_encode($request->actions),
            'vk' => $request->vk,
            // 'logo',
        ]);

        return response()->json(['organization' => $organization]);
    }

    public function edit()
    {
        // $organization = organization();

        // return view('my.organization.edit', compact('organization'));
    }

    public function update(OrganizationUpdateRequest $request)
    {
        //TODO logo saving

        // organization()?->update([
        //     'full_name_ru' => $request->full_name_ru,
        //     'short_name_ru' => $request->short_name_ru,
        //     'full_name_en' => $request->full_name_en,
        //     'short_name_en' => $request->short_name_en,
        //     'inn' => $request->inn,
        //     'address' => $request->address,
        //     'phone' => $request->phone,
        //     'whatsapp' => $request->whatsapp,
        //     'telegram' => $request->telegram,
        //     'type' => $request->type,
        //     'actions' => json_encode($request->actions),
        //     'vk' => $request->vk,
        //     // 'logo',
        // ]);
    }
}
