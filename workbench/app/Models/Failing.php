<?php

namespace Workbench\App\Models;

use DeJoDev\MultiLanguageStrings\Casts\MultiLanguageStringCast;
use Illuminate\Database\Eloquent\Model;

class Failing extends Model
{
    protected $fillable = [
        'sku',
        'price',
    ];

    protected $casts = [
        'price' => MultiLanguageStringCast::class,
    ];
}
