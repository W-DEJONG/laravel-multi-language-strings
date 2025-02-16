<?php

namespace Workbench\App\Models;

use DeJoDev\MultiLanguageStrings\Casts\MultiLanguageStringCast;
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
        'productName' => MultiLanguageStringCast::class,
        'productDescription' => MultiLanguageStringCast::class,
    ];
}
