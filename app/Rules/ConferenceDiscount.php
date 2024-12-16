<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ConferenceDiscount implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_null($value['amount'])) {
            return;
        }

        if (! is_numeric($value['amount'])) {
            $fail(__('validation.conference_discount.integer'));
        }

        if (str_contains($value['amount'], '.')) {
            $fail(__('validation.conference_discount.integer'));
        }

        if ($value['unit'] === 'percent' && $value['amount'] > 100) {
            $fail(__('validation.conference_discount.many_percents'));
        }
    }
}
