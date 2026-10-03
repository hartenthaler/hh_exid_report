# hh_exid_report

## Purpose

`hh_exid_report` provides a read-only report of GEDCOM 7 `EXID` and legacy
`_EXID` values used in a webtrees family tree. It helps administrators and
editors understand which external-identifier authorities are actually used.

The report scans one selected family tree, counts both spellings separately,
groups entries by their child `TYPE` URI, and lists the GEDCOM record contexts
in which they occur. Identifier values themselves are not displayed. The
module never changes GEDCOM data and does not patch webtrees core.

If the optional `hh_exid` module is active, its public catalogue is used to
show labels and registration status for TYPE URIs. Without `hh_exid`, the
report still works and marks registration status as unknown.

## Requirements

- webtrees 2.2 or 2.3;
- the PHP version supported by the installed webtrees release;
- access to at least one family tree.

## Installation

### Manual installation

1. Download the release archive from GitHub.
2. Extract it into `webtrees/modules_v4/hh_exid_report`.
3. Enable **EXID usage report** in the webtrees module administration.

### Composer / CMM

In a webtrees development installation with Composer support, install the
package with:

```text
composer require hartenthaler/hh-exid-report
```

The `webtrees/module-installer` package places the module in `modules_v4`.

## Usage

Open the report from the webtrees **Reports** menu. Select a family tree and
review:

- the total number of external identifiers;
- the separate `EXID` and `_EXID` counts in the report header;
- the TYPE URI, its catalogue label, occurrence count, and GEDCOM contexts;
- whether the URI is registered in the available `hh_exid` catalogue.

The report is read-only. Data export and correction remain functions of the
`hh_exid` module.

## Documentation

- [Report details](docs/report.md)
- [Issue tracker](https://github.com/hartenthaler/hh_exid_report/issues)
- [Releases](https://github.com/hartenthaler/hh_exid_report/releases)

## Privacy

The report reads GEDCOM data already stored in the selected webtrees database.
It sends no data to external services and does not write to the family tree.

## Translation

Module-specific strings are maintained in `resources/lang/default.pot` and
language catalogues such as `resources/lang/de.po`/`de.mo`. The module supports
the translation APIs of webtrees 2.2 and 2.3. Common webtrees strings such as
“Family tree”, “Label”, and “Count” are deliberately reused from the core
catalogue instead of being duplicated in this module.

## License

GPL-3.0-or-later.

## Credits

Developed by Hermann Hartenthaler for the webtrees community. Thanks to the
webtrees development team and to the maintainers of the external-identifier
standards and catalogues.
