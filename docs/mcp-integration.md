# Tag Groups AI actions

Tag Groups registers private WordPress abilities in the `publishpress-tag-groups` namespace. PublishPress Hub selects which abilities a connected AI service can use; the WordPress MCP Adapter supplies transport. Tag Groups also works with another explicitly configured ability server. Registration requires the WordPress Abilities API and does not depend on Hub.

## Permissions and editions

Settings require `manage_options`. Group and global term membership operations require the native configured capabilities (Free defaults to `edit_pages`), with taxonomy and individual term checks for membership changes. Pro global membership propagation also requires permission to edit every affected post. Post assignments require read/edit access to the post, an unlocked WordPress editor, the taxonomy assignment capability and enabled metabox groups/taxonomies. Selecting existing terms does not grant global term-management access. Application-password capability restrictions apply to the authenticated identity.

Pro loads the shared Free integration. Pro-only actions are absent in Free. WooCommerce settings require the active WooCommerce integration. An explicitly false `TAG_GROUPS_REST_API_EDITABLE` configuration locks MCP writes; an undefined constant leaves MCP exposure controlled by the selected ability server. Native REST endpoints retain their own opt-in policy.

## Discovery and changes

Start with `list-features`, `get-plugin-settings`, `list-groups`, `list-terms` or `get-post-groups`. `list-displays` returns active shortcode names, native Gutenberg block identifiers and their registered attribute schemas. Use those displays in the native editor; content editing belongs to the connected server's WordPress content tools.

`get-plugin-settings` returns field schemas, native defaults, current settings (including native verbose diagnostics), locked excerpt fields and effective theme/plugin override values. Switches use JSON booleans and native integer storage. HTML descriptions use an integer: `0` removes HTML, `1` keeps it, and `2` keeps HTML only for viewers with the native `unfiltered_html` capability. Group and term identifiers are integers. `create-terms` validates up to 20 new names before creating terms with native group memberships. Group lists are arrays, not keyed objects; numeric-string IDs and string booleans are rejected. Settings patches are JSON objects. Omitted settings are preserved; an empty patch changes nothing. Native HTML sanitization applies to templates and separators. Enabling mode `1` through MCP requires the connected account's actual `unfiltered_html` capability; an unchanged existing mode remains available. Theme names must be relative folders within uploads, without traversal segments or URL syntax. Keeping HTML or enabling public REST access changes what native frontend/API visitors can see; choose those settings deliberately.

Writes accept `dry_run: true` for a non-persistent preview. Read the current `expected_hash` immediately before saving. Settings, group definitions, term membership and post assignments have their own revisions. Use `get-group` for the deletion revision: it also covers the affected native memberships. Deletion revisions include native translated group labels as well. A stale revision requires another read and preview.

Group `0` is the virtual unassigned group. Free supports one group per term; Pro supports multiple. Parent groups are headings: their position determines which following groups are their children. A populated leaf group must be emptied before conversion into a parent heading. Supply each nonzero identifier once to `set-group-order`.

Pro `set-post-groups` replaces one enabled metabox taxonomy while preserving other taxonomies and restricted hidden groups. Existing terms must already belong to their requested global groups. Global membership changes and post-specific selections are separate operations.

## Bounds and failure handling

Term discovery is paginated, up to 100 per request. Bulk membership updates accept up to 20 terms and validate every input before saving. Pro propagation and deletion are limited to 500 affected posts/terms per operation. Group deletion uses native removal and scoped native repair instead of scheduling unrelated site-wide repair.

Native hooks can change permissions or persistence outcomes. Propagation rechecks each affected post and native metadata write; grouped-post saves also verify core taxonomy relationships and reload persisted memberships rather than trusting intended values in a cache. Check `complete` and the reported applied/failed identifiers. A failure after a native write may leave a partial result; read the current state before retrying. These operations are not database transactions.

Import/export files, reset, migration/repair jobs, cache benchmarks, sample-page generation, licensing and editor rendering remain in native administration. Credentials, license values, internal arbitrary options, arbitrary SQL/PHP and unrestricted method dispatch are not exposed.
