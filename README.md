# hyperf-middleware

[![Latest Stable Version](https://poser.pugx.org/php-packagist/hyperf-middleware/v/stable)](https://packagist.org/packages/php-packagist/hyperf-middleware)
[![Total Downloads](https://poser.pugx.org/php-packagist/hyperf-middleware/downloads)](https://packagist.org/packages/php-packagist/hyperf-middleware)
[![License](https://poser.pugx.org/php-packagist/hyperf-middleware/license)](https://packagist.org/packages/php-packagist/hyperf-middleware)

## Installation

```bash
composer require php-packagist/hyperf-middleware
```

> Version `^2.0` requires Hyperf >=2.0.0 or Higher.

## Usage

```php
<?php

declare(strict_types=1);

return [
    'http' => [
        \PhpPackagist\HyperfMiddleware\RequestIdMiddleware::class,
        \PhpPackagist\HyperfMiddleware\SlowLogMiddleware::class,
    ],
];
```

## Features

- [RequestIdMiddleware](src/RequestIdMiddleware.php) - RequestId Middleware
- [SlowLogMiddleware](src/SlowLogMiddleware.php) - SlowLog Middleware

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
