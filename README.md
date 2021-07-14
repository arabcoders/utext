# String Manipulation Library

based on the `laravel/support` string class.

The purpose of this library is to abstract away the underlying string functions. instead of relying 
on mbstring or intl functions directly.

## Install

Via Composer

```bash
$ composer require arabcoders/utext
```

## Usage Example.

```php
<?php

require __DIR__ . 'vendor/autoload.php';

use \arabcoders\utext\UText;

echo (new UText('foo'))->upper();

// -- or you could use the shortcut function auto included by the package.
echo u('foo')->upper();
```