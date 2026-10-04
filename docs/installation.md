# Installation details

## Composer installation

The report depends on the shared library `hartenthaler/hh-shared`.
In a webtrees development environment you can use the command:

```text
composer require hartenthaler/hh-exid-report
```

Composer then installs both the report and its shared library. The report's
`autoload.php` loads the generated Composer autoloader and the shared library
autoloader automatically.

## Building a release package

The release archive contains the runtime Composer dependencies in its
`vendor/` directory and can be copied directly to
`modules_v4/hh_exid_report`.

When preparing a package from a source checkout, run this command in the
module directory before copying it to the server:

```text
composer install --no-dev --prefer-dist
```

The resulting package includes `vendor/hartenthaler/hh-shared`, which provides
the shared code used by the report.
