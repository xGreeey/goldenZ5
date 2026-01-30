<?php

declare(strict_types=1);

/**
 * Bootstrap: load .env into $_ENV and set defaults.
 * Docker environment variables override .env when set.
 */
$envFile = dirname(__DIR__) . '/.env';
if (is_file($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue;
        }
        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        if (!array_key_exists($name, $_ENV) && getenv($name) === false) {
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}
