<?php

declare(strict_types=1);

// Composer installs hh_shared as a dependency of this module. Load the
// module's Composer autoloader first, then the shared library's autoloader,
// as done by the established webtrees shared-code modules.
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/hartenthaler/hh-shared/autoload.php';

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
