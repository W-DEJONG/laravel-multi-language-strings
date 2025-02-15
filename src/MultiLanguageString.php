<?php

namespace DeJoDev\MultiLanguageStrings;

use InvalidArgumentException;
use Stringable;

class MultiLanguageString implements Stringable
{
    protected static bool $useFallbackLocale = true;

    private array $values;

    private bool $fallback;

    public function __construct(string|array $value = [], ?bool $useFallbackLocale = null)
    {
        $this->fallback = is_null($useFallbackLocale) ? static::$useFallbackLocale : $useFallbackLocale;
        $this->set($value);
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
        return $this->get();
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
