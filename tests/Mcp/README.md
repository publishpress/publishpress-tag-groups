# Native Adapter regression tests

Run `php tests/Mcp/smoke.php /absolute/path/to/wp-load.php /absolute/path/to/mcp-adapter` against a disposable WordPress installation with the plugin activated. The database name must start with `taggroups_mcp_`; fixtures create and modify data. Set `TAGGROUPS_MCP_MODE=free` or `pro`. The Adapter directory must contain the released 0.7.1 Composer autoloader. Pro must load the current shared Free source through normal packaging.

The suite uses actual native services, options, metadata, terms and post relationships through `McpTool::fromAbility()` and `execute()`. It checks settings discovery/preview/save/read-back, strict types, malformed mixed patches, permissions, stale revisions, ordering, parent headings, Pro propagation and cross-taxonomy preservation, native editor locks, metadata-hook permission revocation, non-admin propagation, cache invalidation and silent persistence failures. Synthetic excerpt filters exercise unlocked round trips and locked native controls.

`TAGGROUPS_MCP_CAPABILITIES_FILE` optionally points to the existing Capabilities application-password component for real capability-denial tests; this is component coverage, not a full Capabilities distribution test. External mail and network access should be blocked in the disposable environment.

Source-assembled Pro installations test actual Free/Pro source together. They do not validate a packaged release ZIP, browser editor, external AI-client HTTP session, WooCommerce distribution or WPML/Polylang distribution.
