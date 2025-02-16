<?php

namespace DeJoDev\MultiLanguageStrings\Casts;

use DeJoDev\MultiLanguageStrings\MultiLanguageString;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class MultiLanguageStringCast implements CastsAttributes
{
    /**
     * {@inheritDoc}
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): MultiLanguageString
    {
        if (is_null($value)) {
            return new MultiLanguageString;
        }

        if (! is_string($value)) {
            throw new InvalidArgumentException('The given value is not a MultiLanguageString JSON string.');
        }

        return MultiLanguageString::fromJson($value);
    }

    /**
     * {@inheritDoc}
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if (! $value instanceof MultiLanguageString) {
            throw new InvalidArgumentException('The given value is not a MultiLanguageString instance.');
        }

        return $value->toJson();
    }
}
