<?php

namespace App\Casts;

use DomainException;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Src\Domains\Conferences\Enums\DiscountUnit;
use Src\Domains\Conferences\ValueObjects\Discount;

class DiscountCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $array = json_decode($value, true);

        if (empty($array['amount'])) {
            $array['amount'] = 0;
        }

        if (empty($array['unit'])) {
            $array['unit'] = 'RUB';
        }

        return new Discount($array['amount'], DiscountUnit::from($array['unit']));
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value instanceof Discount) {
            return json_encode($value);
        }

        if (is_array($value)) {
            if (is_null($value['amount'])) {
                $value['amount'] = 0;
            }

            if (! isset($value['unit'])) {
                throw new DomainException('Discount has no unit key');
            }
        }

        return json_encode($value);
    }
}
