# php-getenv

# Loads environment variables from .env file

```php
<?php

require_once "vendor/autoload.php";

use EnesEkinci\GetEnv\Config;

$file_env = __DIR__ . '/' . '.env';

Config::run($file_env);

$all = Config::get('all');

$app_url = Config::get('APP_URL');

```
