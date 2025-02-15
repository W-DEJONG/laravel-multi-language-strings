# MultiLanguageStr package
Laravel Package that adds a Multilanguage string class and an Attribute cast for adding language support to Models.

Usage
```php

<?php

use DeJoDev\Stringable\Stringable;

require __DIR__.'/../vendor/autoload.php';

setLocale('en')
$langStr = new MultiLanguageStr('Hello World!');
echo $langStr; // Hello World!
echo $langStr->toString(); // Hello World!

$langStr->set('Hallo Wereld!', 'nl');
echo $langStr; // Hello World!
echo $langStr->get('nl'); // Hallo Wereld!

print_r($langStr->get()) // ['en'=> 'Hello World!', 'nl' => 'Hallo Wereld!']

// Remove Dutch 
$langStr->unset('nl');

// Set all languages in one call
$langStr->set(['en' => 'Hello World!', 'nl' => 'Hallo Wereld!']);


```

2025 Wouter de Jong
