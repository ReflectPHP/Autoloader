ReflectPHP Autoloader
====================

[![Latest Stable Version](https://poser.pugx.org/ReflectPHP/Autoloader/version)](https://packagist.org/packages/ReflectPHP/Autoloader)
[![Build Status](https://travis-ci.org/ReflectPHP/Autoloader.svg?branch=master)](https://travis-ci.org/ReflectPHP/Autoloader)
[![License](https://poser.pugx.org/ReflectPHP/Autoloader/license)](https://packagist.org/packages/ReflectPHP/Autoloader)

Helper library to integrate ReflectPHP kernel with composer autoloader. 
 
## Require
- PHP 7.0 or greater

## Installation

[See on Packagist](https://packagist.org/packages/reflect/autoloader)

```bash
composer require reflect/autoloader
```

## Usage

### Restreaming example

```php
use Reflect\Autoloader\ClassLoader;

$composer = require __DIR__ . '/vendor/autoload.php';

(new ClassLoader($composer))
    ->class('TestClass')
    ->restream(function (string $path) {
        return '/path/to/cached/' . $path;
    });
    
new TestClass(); // Will be included from "/path/to/cached/[...]/TestClass.php"
```

## Autoloading rules

### Register loader

```php
use Reflect\Autoloader\ClassLoader;

$loader = ClassLoader(require __DIR__ . '/vendor/autoload.php');
```

### Class matcher

Will works for all classes named "SomeName".

```php
$loader->class('SomeName')->...;
```

### Namespace matcher

Will works for all namespaces with prefix "Some/Any" like "Some/Any/ClassName" or "Some/Any/Olololo/Asdasd" classes.

```php
$loader->namespace('Some/Any')->...;
```

### Pattern matcher

Will works if class path (namespace + class) matched target pattern:
 
```php
$loader->match('\w+Test')->...;
```

### Custom comparator

```php
use Reflect\Autoloader\Matcher\Comparator;

$loader->compare(new class extends Comparator {
    public function compare(string $classPath): bool
    {
        return true; // or false
    }
})->...;
```