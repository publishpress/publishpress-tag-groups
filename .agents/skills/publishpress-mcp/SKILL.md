---
name: publishpress-mcp
description: Implement or extend Tag Groups Free and Pro MCP abilities using native group, term, taxonomy, post and settings services.
---

# PublishPress Tag Groups MCP integration

Tag Groups owns abilities; Hub owns transport and exposure. Begin with `include/mcp/class.mcp.php`, `class.mcp_settings.php`, `class.mcp_actions.php`, the native entities and helpers. Pro loads the shared Free source through `lib/composer.json`. Do not duplicate the integration or edit vendor copies.

Follow native class/file names, snake-case methods, style and PHP 7.2 compatibility. Register private abilities independently of the current user's permissions. Check the actual native capability at execution; never change the authenticated identity or bypass a denial for Administrators. Respect application-password restrictions, active editions, dependencies and explicit configuration locks.

Trace native fields before exposing settings. HTML description handling is a three-way integer choice, not a boolean. Group IDs are integers; membership lists are arrays. Native membership storage is comma-delimited protected metadata. Parent groups are ordered headings, not an arbitrary parent-ID tree. Use native options, taxonomies, group and term services; preserve unrelated options and objects.

Pro term changes propagate to post metadata. Preflight all affected object permissions and retain the scoped native propagation guard. Deletion uses a fresh membership-aware revision, exact authorized term scope and scoped native repair; do not schedule unrelated site-wide repair. Post assignments preserve other taxonomies and restricted hidden groups. Native excerpt filters can lock settings. Preserve these locks and current effective-value discovery.

Require strict JSON types before native sanitization, previews, revisions, all-target bulk validation and read-back. Report partial writes honestly. Keep license values, credentials and arbitrary options/SQL/PHP out of the contract.

Use `tests/Mcp/smoke.php` through the released Adapter and native persistence. See `docs/mcp-feature-coverage.md` and `docs/mcp-prompt-examples.md`. Distinguish source-assembled Pro tests from packaged distributions and external HTTP sessions. Keep documentation about current usage; do not create audit-report files or modify vendor dependencies.
