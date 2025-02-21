# Laravel Multi Language strings package

The Laravel Multi Language Strings package allows you to easily store and manage multilanguage strings in a JSON field
within a database table. It provides a `MultiLanguageString` class and an attribute cast for Eloquent models, which are
aware of Laravel locale and fallback locale settings. This package simplifies the process of setting and retrieving
multilanguage strings, ensuring seamless localization support in your Laravel applications.

## Installation

Install the package via composer:

```bash
  composer require dejodev/laravel-multi-language-strings
```

Add a JSON field to your database migration to store the multilanguage strings.

```php
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->json('productName'); // Add this line
            $table->timestamps();
        });
    }
``` 

Add the MultiLanguageString class to your model's `$casts` property.

```php
<?php

namespace App\Models;

use DeJoDev\MultiLanguageStrings\MultiLanguageString;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $casts = [
        'productName' => MultiLanguageString::class,
    ];
}
```

## Usage

You can now use the `MultiLanguageString` class to store multilanguage strings in your Eloquent models. 
```php
$product = new Product();

// Set the value for the current locale
$product->productName = 'Product name';

// Or use the set method
$product->productName->set('Product name');

// Set the value for a specific locale
$product->productName->set('Product name', 'nl'); 

// Set the value for all locales a once. (all existing locales will be cleared)
$product->productName->set([
    'en' => 'Product name',
    'nl' => 'Product naam',
]);

// Or set another MultiLanguageString as value
$product->productName = MultiLanguageString::create([
    'en' => 'Product name',
    'nl' => 'Product naam',
]);

// Unset the value for a specific locale
$product->productName->unset('nl');


```

To get a multilanguage string use the `get` method.

```php
$product = Product::find(1);

// Get the value for the current locale, if the value is not set for the
// current locale the value for the fallback locale will be returned.
$product->productName->get();

// Get the value for a specific locale
$product->productName->get('nl');

// Multilanguage strings implements Stringable and can be cast to a string.
$s = (string) $product->productName; 
print($product->productName); 

// Alias for get();
$product->productName->toString();

```

## Advanced usage
The `MultiLanguageString` class is a wrapper around an associative array that maps locale codes to string values.
The class is aware of the current locale and fallback locale settings in Laravel, and it provides a convenient API for
setting and retrieving multilanguage strings.

The `MultiLanguageString` class can be used independently of Eloquent models. 

```php
// Create a new `MultiLanguageString` instance.
$mls = MultiLanguageString::create([
    'en' => 'Hello World!',
    'nl' => 'Hallo Wereld!',
]);

// Create a new `MultiLanguageString` instance from a JSON string.
$mls = MultiLanguageString::fromJson('{"en":"Hello World!","nl":"Hallo Wereld!"}');

// Convert the `MultiLanguageString` instance to a JSON string.
$json = $mls->toJson();
```

## Locale handling
The `MultiLanguageString` class is aware of the current locale and fallback locale settings in Laravel. 
By default, the class uses the current locale to get the value of a multilanguage string. 
If the value is not set for the current locale, the class will use the fallback locale to get the value. 
You can enable or disable the fallback locale for a specific instance of the `MultiLanguageString` class.

```php
// Set fallback option explicit upon instance creation().
$mls = MultiLanguageString::create([
    'en' => 'Hello World!',
    'nl' => 'Hallo Wereld!',
], useFallbackLocale: false);

// Set fallback option on existing instance.
$mls->setUseFallbackLocale(false);

// Get fallback option on existing instance. 
$default = $mls->getUseFallbackLocale();

// Set the default value for the `useFallbackLocale` property.
// This will be used for all new instances.
MultiLanguageString::setUseFallBackLocaleDefault(false);

// Get the default value for the `useFallbackLocale` property.
$default = MultiLanguageString::getUseFallBackLocaleDefault();

// Gets the locale that is used to get the value, using the fallback locale when enabled.
$locale = $mls->getUsedLocale(): string;
$locale = $mls->getUsedLocale('nl'): string;

// Gets an array of the locales stored in the MultiLanguageString.
$locales = $mls->getLocales(); // e.g. ['en', 'nl']
```
---

(c) 2025 Wouter de Jong
