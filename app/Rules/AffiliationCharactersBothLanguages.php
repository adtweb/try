<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AffiliationCharactersBothLanguages implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value['no_affiliation']) {
            if (preg_match('~[a-zA-Z0-9\-_ ]+~u', $value['title_en']) === 0) {
                $fail('Название аффилиации на английском должно содержать только латинницу, цифры, тире и знак нижнего подчеркивания');
            }

            return;
        }

        if (preg_match('~[a-zA-Z0-9\-_ ]+~u', $value['title_en']) === 0) {
            $fail('Название аффилиации на английском должно содержать только латинницу, цифры, тире и знак нижнего подчеркивания');
        }

        if (preg_match('~[а-яА-Я0-9\-_ ]+~u', $value['title_ru']) === 0) {
            $fail('Название аффилиации на русском должно содержать только кириллицу, цифры, тире и знак нижнего подчеркивания');
        }
    }
}
