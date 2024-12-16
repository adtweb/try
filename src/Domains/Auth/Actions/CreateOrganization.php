<?php

namespace Src\Domains\Auth\Actions;

use Illuminate\Foundation\Http\FormRequest;
use Src\Domains\Auth\Models\Organization;
use Src\Domains\Auth\Models\User;

class CreateOrganization
{
    public function handle(FormRequest $request, User $user): Organization
    {
        //TODO logo saving

        return Organization::create([
            'user_id' => $user->id,
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
    }
}
