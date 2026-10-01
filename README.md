# hh_exid_report

Read-only webtrees report for the use of GEDCOM 7 `EXID` and legacy `_EXID` values.

The report scans one selected family tree, counts both spellings separately, groups entries by their child `TYPE` URI, and lists the GEDCOM record contexts in which they occur. It never changes the tree and does not patch webtrees core. If the optional `hh_exid` module is active, its catalogue is used to mark registered TYPE URIs; otherwise registration is reported as unknown.

The module supports webtrees 2.2 and 2.3. Install it in `modules_v4/hh_exid_report` or with Composer in a webtrees development installation.

## License

GPL-3.0-or-later.
