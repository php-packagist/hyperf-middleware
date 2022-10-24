# hyperf-middleware

## Installation

```bash
composer require php-packagist/hyperf-middleware
```

## Usage

```php
<?php

declare(strict_types=1);

return [
    'http' => [
        \PhpPackagist\HyperfMiddleware\RequestIdMiddleware::class,
    ],
];
```

## Features

- [RequestIdMiddleware](src/RequestIdMiddleware.php) - RequestId Middleware

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
