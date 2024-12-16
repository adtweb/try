<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxStripTagsCharacters implements ValidationRule
{
    public function __construct(private int $max) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (mb_strlen(htmlspecialchars_decode(html_entity_decode(strip_tags($value)))) > $this->max) {
            $fail(__('validation.max.strip_tags', ['max' => $this->max, 'attribute' => $attribute]));
        }
    }
}
