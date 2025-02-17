# Laravel Multi Language strings package
Laravel Package that can be used to easily store multilanguage strings in a JSON field in a database table.

It contains a Multilanguage string class and an Attribute cast that can be used in Eloquent models 
other classes that is aware of the Laravel locale and fallback locale.

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
To set a multilanguage string use the `set` method on the MultiLanguageString object.

```php
$product = new Product();

// Set the value for the current locale
$product->productName->set('Product name');

// Set the value for a specific locale
$product->productName->set('Product name', 'nl'); 

// Set the value for all locales a once. (all existing locales will be cleared)
$product->productName->set([
    'en' => 'Product name',
    'nl' => 'Product naam',
]);
```

To get a multilanguage string use the `get` method on the MultiLanguageString object.

```php
$product = Product::find(1);

// Get the value for the current locale, if the value is not set for the
// current locale the value for the fallback locale will be returned.
$product->productName->get();

// Get the value for a specific locale
$product->productName->get('nl');

// Multilanguage strings implements Stringable and can be cast to a string
$s = (string) $product->productName; // Will store the value for the current locale in $s
print($product->productName); // Will print the value for the current locale

```
(c) 2025 Wouter de Jong
