<?php

use DeJoDev\MultiLanguageStrings\MultiLanguageString;
use Workbench\App\Models\Failing;
use Workbench\App\Models\Product;

it('casts MultiLanguageString to json', function () {
    $product = Product::create([
        'sku' => '123456',
        'productName' => new MultiLanguageString([
            'en' => 'Product Name',
            'es' => 'Nombre del Producto',
        ]),
        'productDescription' => new MultiLanguageString([
            'en' => 'Product Description',
            'es' => 'Descripción del Producto',
        ]),
        'price' => 1000,
    ]);

    $product = Product::find($product->id);

    expect($product->productName)->toBeInstanceOf(MultiLanguageString::class)
        ->and($product->productDescription)->toBeInstanceOf(MultiLanguageString::class)
        ->and((string) $product->productName)->toBe('Product Name')
        ->and((string) $product->productDescription)->toBe('Product Description')
        ->and($product->price)->toBe(1000)
        ->and($product->productName->get('es'))->toBe('Nombre del Producto')
        ->and($product->productDescription->get('es'))->toBe('Descripción del Producto');
});

it('Throws an exception when trying to set a non MultiLanguageString instance', function () {
    $product = Product::create([
        'sku' => '123456',
        'productName' => 'Product Name',
        'productDescription' => 'Product Description',
        'price' => 1000,
    ]);
})->throws(InvalidArgumentException::class, 'The given value is not a MultiLanguageString instance.');

it('Returns an empty MultiLanguageString instance when the value is null', function () {
    $product = Product::create([
        'sku' => '123456',
        'productName' => new MultiLanguageString([
            'en' => 'Product Name',
            'es' => 'Nombre del Producto',
        ]),
        'price' => 1000,
    ]);
    expect($product->productDescription)->toBeInstanceOf(MultiLanguageString::class)
        ->and($product->productDescription->get())->toBeNull()
        ->and((string) $product->productDescription)->toBeEmpty();
});

it('Throws an exception when trying to get a non MultiLanguageString JSON string', function () {
    DB::insert('INSERT INTO products (id, sku, productName, productDescription, price) VALUES (?, ?, ?, ?, ?)', [
        1,
        '123456',
        'Product Name',
        'Product Description',
        1000,
    ]);
    $product = Product::find(1);
    $name = $product->productName;

})->throws(InvalidArgumentException::class);

it('Throws an exception when the database column is not a JSON string', function () {
    DB::insert('INSERT INTO failings (id, sku, price) VALUES (?, ?, ?)', [
        1,
        '123456',
        1000,
    ]);
    $failure = Failing::find(1);
    $price = $failure->price;
})->throws(InvalidArgumentException::class);
