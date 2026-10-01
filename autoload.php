<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $prefix = 'Hartenthaler\\Webtrees\\Module\\ExidReportModule\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = __DIR__ . '/src/' . str_replace('\\', '/', $relative) . '.php';

    if (is_file($file)) {
        require_once $file;
    }
});
