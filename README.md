# Laravel Multi Language strings package
Laravel Package that adds a Multilanguage string class and an Attribute cast for adding language support to Models.

## Use in Models
```php
<?php

namespace App\Models;

use DeJoDev\MultiLanguageStrings\MultiLanguageString;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'productName',
        'productDescription',
        'price',
    ];

    protected $casts = [
        'productName' => MultiLanguageString::class,
        'productDescription' => MultiLanguageString::class,
    ];
}
```

### Migration
```php
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->json('productName');
            $table->json('productDescription')->nullable();
            $table->integer('price');
            $table->timestamps();
        });
    }
```

(c)MIT 2025 Wouter de Jong
