# Installation details

## CMM or Composer installation

The report depends on the shared library
`hartenthaler/hh-shared`. CMM installs this dependency automatically. In a
webtrees development checkout, the equivalent command is:

```text
composer require hartenthaler/hh-exid-report
```

Composer then installs both the report and its shared library. The report's
`autoload.php` loads the generated Composer autoloader and the shared library
autoloader automatically.

## Building a manual package

The normal webtrees administrator does not need Composer. A release archive
should contain the Composer dependencies in its `vendor/` directory and can
be copied directly to `modules_v4/hh_exid_report`.

When preparing a package from a source checkout, run this command in the
module directory before copying it to the server:

```text
composer install --no-dev --prefer-dist
```

Do not remove `vendor/hartenthaler/hh-shared` from the resulting package: it
contains the shared code used by the report. The report does not contain a
second implementation of that code.
