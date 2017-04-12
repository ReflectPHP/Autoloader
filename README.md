ReflectPHP Autoloader
====================

[![Latest Stable Version](https://poser.pugx.org/reflect/autoloader/version)](https://packagist.org/packages/reflect/autoloader)
[![Build Status](https://travis-ci.org/ReflectPHP/Autoloader.svg?branch=master)](https://travis-ci.org/ReflectPHP/Autoloader)
[![License](https://poser.pugx.org/reflect/autoloader/license)](https://packagist.org/packages/reflect/autoloader)

Helper library to integrate ReflectPHP kernel with composer autoloader.
 
## Require
- PHP 7.0 or greater

## Installation

[See on Packagist](https://packagist.org/packages/reflect/autoloader)

```bash
composer require reflect/autoloader
```

## Usage

```php
use Reflect\Autoloader\ClassLoader; 

$composer = __DIR__ . '/verndor/autoload.php';

$loader = new ClassLoader($composer); 
$loader->when()
    ->ever()
    ->then(function(string sources): string {
        return '<?php class A { } echo "Class A";';
    });
    
    
new A(); // Class A
```

## Rules

```php
(new ClassLoader($composer))
    ->when()
        ->ever()                    // Every class
        ->name('Foo\\Bar')          // Every class who contains sequence "Foo\Bar"
        ->inNamespace('Foo')        // Every class in "Foo" namespace
        ->className('Bar')          // Every class named "Bar"
        ->matchClass('^B.+')        // Every class matched with given pattern "^B.+"
        ->fileName('Bar.php')       // Every file with name "Bar.php"
        ->matchFile('^bar.*?')      // Every file matched with given pattern "^bar.*?"
        ->comparedBy(new class implements Comparator {
                                    // Custom comparator
        })
        ->then(function(string $sources, ?string $file): string {
            // Execute
        })
    ->when()
        -> // Another rules group
;
```
