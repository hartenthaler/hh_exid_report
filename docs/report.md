# EXID usage report

The report is deliberately read-only. It scans the GEDCOM text stored for the
selected tree in the webtrees tables for both `EXID` (GEDCOM 7) and `_EXID`
(legacy/custom spelling). It does not export, rewrite, or otherwise patch
GEDCOM data; correction and export remain the responsibility of `hh_exid`.

For each child `TYPE` URI the report shows the number of occurrences and the
GEDCOM record/context in which it occurs. The header shows the total and the
separate counts for `EXID` and `_EXID`. Identifier values themselves are not
listed.

If `hh_exid` is enabled, the report uses its public catalogue to show a label
and whether a URI is registered. The report still works without that module;
in that case registration is shown as unknown.
