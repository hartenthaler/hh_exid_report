# Changelog

## Unreleased

- Fixed automatic loading of the required `hh_shared` library through the
  module's Composer autoloader. The report no longer contains a duplicate
  fallback implementation.
- Moved Composer/CMM and manual package preparation details from the README to
  `docs/installation.md`.
- Removed the duplicate main-menu entry; the report is available through the
  standard webtrees Reports menu only.
- Reordered the report columns so the catalogue label appears before the TYPE
  URI, and made every report column sortable.

## 2.2.6.0

- Added the first usable EXID usage report with separate `EXID`/`_EXID`
  counts, TYPE-URI grouping, GEDCOM contexts, and optional `hh_exid` catalogue
  status.
- Added the webtrees 2.2/2.3 translation loader and German catalogue.
- Expanded the README with installation, usage, documentation, privacy,
  translation, license, and credits sections.

## 0.1.0

- Initial read-only report for `EXID` and `_EXID` usage.
