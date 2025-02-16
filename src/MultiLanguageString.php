<?php

namespace DeJoDev\MultiLanguageStrings;

use DeJoDev\MultiLanguageStrings\Casts\MultiLanguageStringCast;
use Illuminate\Contracts\Database\Eloquent\Castable;
use InvalidArgumentException;
use Stringable;

final class MultiLanguageString implements Castable, Stringable
{
    protected static bool $useFallbackLocale = true;

    private array $values;

    private bool $fallback;

    public function __construct(string|array $value = [], ?bool $useFallbackLocale = null)
    {
        $this->fallback = is_null($useFallbackLocale) ? self::$useFallbackLocale : $useFallbackLocale;
        $this->set($value);
    }

    public static function fromJson(string $json): static
    {
        if (empty($json)) {
            return new self;
        }

        $values = json_decode($json, true);
        if ($values = $values['multi-lang'] ?? null) {
            return new self($values);
        }

        throw new InvalidArgumentException('Invalid MultiLanguageString JSON');
    }

    public function toJson(): string
    {
        return json_encode(['multi-lang' => $this->values]);
    }

    /**
     * Get the name of the caster class to use when casting from / to this cast target.
     *
     * @param  array<string, mixed>  $arguments
     */
    public static function castUsing(array $arguments): string
    {
        return MultiLanguageStringCast::class;
    }

    public static function setUseFallBackLocaleDefault(bool $enable): void
    {
        static::$useFallbackLocale = $enable;
    }

    public static function getUseFallBackLocaleDefault(): bool
    {
        return static::$useFallbackLocale;
    }

    public function getUseFallbackLocale()
    {
        return $this->fallback;
    }

    public function setUseFallbackLocale(bool $enable): void
    {
        $this->fallback = $enable;
    }

    public function getUsedLocale(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        if (! array_key_exists($locale, $this->values) && $this->fallback) {
            $locale = app()->getFallbackLocale();
        }

        return $locale;
    }

    public function get(?string $locale = null): string|array|null
    {
        return $this->values[$this->getUsedLocale($locale)] ?? null;
    }

    public function set(string|array $value, ?string $locale = null): void
    {
        if (is_string($value)) {
            $this->values[$locale ?? app()->getLocale()] = $value;
        } elseif ($this->isKeyValueArray($value)) {
            $this->values = $value;
        } else {
            throw new InvalidArgumentException('Value must be a string or an associative array');
        }
    }

    public function unset(string $locale): void
    {
        if (array_key_exists($locale, $this->values)) {
            unset($this->values[$locale]);
        }
    }

    public function getLocales(): array
    {
        return array_keys($this->values);
    }

    public function toString(): string
    {
        return $this->get() ?? '';
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    private function isKeyValueArray(array $array): bool
    {
        foreach (array_keys($array) as $key) {
            if (! is_string($key)) {
                return false;
            }
        }

        return true;
    }
}
