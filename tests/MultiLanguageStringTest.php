<?php

use DeJoDev\MultiLanguageStrings\MultiLanguageString;

it('Can instantiate a MultiLanguageString', function () {
    $langStr = MultiLanguageString::create('Hello World!');
    expect($langStr)
        ->toBeInstanceOf(MultiLanguageString::class)
        ->and((string) $langStr)
        ->toBe('Hello World!')
        ->and($langStr->getUsedLocale())
        ->toBe('en');
});

it('Can get a language strings locales', function () {
    $langStr = new MultiLanguageString(['en' => 'Hello World!', 'nl' => 'Hallo Wereld']);
    expect($langStr->get('nl'))->toBe('Hallo Wereld')
        ->and($langStr->get())->toBe('Hello World!')
        ->and($langStr->get('en'))->toBe('Hello World!')
        ->and($langStr->get('fr'))->toBe('Hello World!');
});

it('Can can disable locale fallback', function () {
    $langStr = new MultiLanguageString(['en' => 'Hello World!', 'nl' => 'Hallo Wereld'], useFallbackLocale: false);
    expect($langStr->get('nl'))->toBe('Hallo Wereld')
        ->and($langStr->get())->toBe('Hello World!')
        ->and($langStr->get('en'))->toBe('Hello World!')
        ->and($langStr->get('fr'))->toBeNull();
});

it('Can set a language strings locales', function () {
    $langStr = app(MultiLanguageString::class);
    $langStr->set('Hallo Wereld', 'nl');
    $langStr->set('Hello World', 'en');
    expect($langStr->get('nl'))
        ->toBe('Hallo Wereld')
        ->and($langStr->get('en'))
        ->toBe('Hello World');
    $langStr->set('How are you?');
    expect($langStr->get())
        ->toBe('How are you?');
});

it('Checks if all locales are string in mass assignment', function () {
    new MultiLanguageString(['en' => 'Hello World!', 23 => 'Hallo Wereld']);
})->throws(InvalidArgumentException::class);

it('Can unset a language strings locale', function () {
    $langStr = new MultiLanguageString(['en' => 'Hello World!', 'nl' => 'Hallo Wereld']);
    expect($langStr->get('nl'))->toBe('Hallo Wereld')
        ->and($langStr->get())->toBe('Hello World!');
    $langStr->unset('nl');
    expect($langStr->get('nl'))->toBe('Hello World!');
});

it('Can return all locales in the language string', function () {
    $langStr = new MultiLanguageString(['en' => 'Hello World!', 'nl' => 'Hallo Wereld']);
    expect($langStr->getLocales())
        ->toBe(['en', 'nl']);
});

it('Can set the default use of a fallback locale', function () {
    MultiLanguageString::setUseFallBackLocaleDefault(false);
    expect(MultiLanguageString::getUseFallBackLocaleDefault())
        ->toBeFalse();

    $langStr = new MultiLanguageString;
    expect($langStr->getUseFallbackLocale())
        ->toBeFalse();

    $langStr->setUseFallbackLocale(true);
    expect($langStr->getUseFallbackLocale())
        ->toBeTrue();

    MultiLanguageString::setUseFallBackLocaleDefault(true);

    $langStr = new MultiLanguageString;
    expect($langStr->getUseFallbackLocale())
        ->toBeTrue();

    $langStr->setUseFallbackLocale(false);
    expect($langStr->getUseFallbackLocale())
        ->toBeFalse();
});

it('Can transform a MultiLanguageString to JSON', function () {
    $langStr = new MultiLanguageString(['en' => 'Hello World!', 'nl' => 'Hallo Wereld']);
    expect($langStr->toJson())
        ->toBe('{"en":"Hello World!","nl":"Hallo Wereld"}');
});

it('Can transform a JSON string to a MultiLanguageString', function () {
    $langStr = MultiLanguageString::fromJson('{"en":"Hello World!","nl":"Hallo Wereld"}');
    expect($langStr->get('nl'))
        ->toBe('Hallo Wereld')
        ->and($langStr->get('en'))
        ->toBe('Hello World!');

    $langStr = MultiLanguageString::fromJson('');
    expect($langStr)->toBeInstanceOf(MultiLanguageString::class)
        ->and($langStr->get())->toBeNull()
        ->and((string) $langStr)->toBeEmpty();
});

it('Throws an exception when JSON is invalid', function () {
    MultiLanguageString::fromJson('{"en":1,"nl":"Hallo Wereld"}');
})->throws(InvalidArgumentException::class);
