# Tag Groups MCP feature coverage

| Native area | MCP access | Native persistence and permissions |
| --- | --- | --- |
| General taxonomy settings | Discover/read/update | `TagGroups_Taxonomy::update_enabled`, `manage_options`, public taxonomy choices |
| Themes, appearance, shortcode behavior, Gutenberg rendering, multilingual synchronization and verbose diagnostics | Discover/read/update | `TagGroups_Options`, native types and sanitization, `manage_options` |
| Native public REST controls and uninstall preference | Discover/read/update | Explicit settings fields; independent of private ability exposure |
| Cache backend and Pro automatic cache clearing | Discover/read/update | Native cache choices and Pro cache purge on backend change |
| Groups, names, positions | Discover/create/update/order/delete | `TagGroups_Group`, `TagGroups_Groups`, configured native capability |
| Pro parent headings | Discover/create/update/order | Native ordered-heading hierarchy; no arbitrary parent-ID model |
| Global term membership | Discover/create terms; replace memberships singly or in bulk | `TagGroups_Term::save`, enabled taxonomy, term permissions; Pro affected-post checks |
| Pro metabox options and default groups | Discover/read/update | Native options, declared group/taxonomy choices |
| Pro permission controls | Discover/read/update | Native capability choices, `manage_options` |
| Pro post-specific grouped terms | Read/replace one taxonomy | `TagGroups_Premium_Post`, native metabox groups/taxonomies, editor locks, post and taxonomy assignment permissions |
| Pro post display and filter templates | Discover/read/update | Native HTML sanitizer; excerpt overrides remain locked |
| Pro WooCommerce display settings | Discover/read/update when active | Native options, WooCommerce dependency, `manage_options` |
| Free and Pro frontend shortcodes/blocks | Discover native display metadata | Active native registrations; native editor/content tools own placement and rendering |
| WPML/Polylang | Current native language context | Native group-label option and native term/post services |
| Import/export, reset, migration/repair, cache benchmark, sample-page tools | Native administration | No file upload, global maintenance or arbitrary dispatcher in MCP |
| License and updater | Native administration | License keys and credentials excluded |

See [AI action usage](mcp-integration.md) for revisions, previews, limits and partial failures.

Taxonomy term definition renaming/deletion, arbitrary post content editing and widget placement use WordPress native administration or the connected server's core content tools. The Tag Groups actions do not duplicate those general WordPress operations.
