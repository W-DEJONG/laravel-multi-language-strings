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

    /**
     * Create a new MultiLanguageString instance.
     *
     * @param  string|array<string, string>  $value
     */
    public static function create(string|array $value = [], ?bool $useFallbackLocale = null): self
    {
        return new self($value, $useFallbackLocale);
    }

    /**
     * Create a new MultiLanguageString instance from a JSON string.
     */
    public static function fromJson(string $json): self
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

    /**
     * Convert the MultiLanguageString instance to a JSON string.
     */
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

    /**
     * Set the default value for the useFallbackLocale property.
     */
    public static function setUseFallBackLocaleDefault(bool $enable): void
    {
        self::$useFallbackLocale = $enable;
    }

    /**
     * Get the default value for the useFallbackLocale property.
     */
    public static function getUseFallBackLocaleDefault(): bool
    {
        return self::$useFallbackLocale;
    }

    /**
     * Get the instance value for the useFallbackLocale property.
     */
    public function getUseFallbackLocale(): bool
    {
        return $this->fallback;
    }

    /**
     * Set the instance value for the useFallbackLocale property.
     */
    public function setUseFallbackLocale(bool $enable): void
    {
        $this->fallback = $enable;
    }

    /**
     * Get the locale that is used to get the value and use the fallback locale when enabled.
     *
     * @param  string|null  $locale  Use this locale to get the value. If null, the current laravel locale will be used.
     */
    public function getUsedLocale(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        if (! array_key_exists($locale, $this->values) && $this->fallback) {
            $locale = app()->getFallbackLocale();
        }

        return $locale;
    }

    /**
     * Get the value for the given locale. If the locale is null, the current laravel locale will be used.
     */
    public function get(?string $locale = null): ?string
    {
        return $this->values[$this->getUsedLocale($locale)] ?? null;
    }

    /**
     * Set the value for the given locale.
     * If the value is an array, it will be set as the values array.
     *
     * @param  string|array<string, string>  $value
     */
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

    /**
     * Unset the value for the given locale.
     */
    public function unset(string $locale): void
    {
        if (array_key_exists($locale, $this->values)) {
            unset($this->values[$locale]);
        }
    }

    /**
     * Get all locales
     *
     * @return array<string>
     */
    public function getLocales(): array
    {
        return array_keys($this->values);
    }

    /**
     * Return the value for the current locale.
     */
    public function toString(): string
    {
        return $this->get() ?? '';
    }

    /**
     * Stringable support for (string) casting.
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Check if the given array is a valid array.
     */
    private function isKeyValueArray(array $array): bool
    {
        foreach ($array as $key => $value) {
            if (! is_string($key) || ! is_string($value)) {
                return false;
            }
        }

        return true;
    }
}
