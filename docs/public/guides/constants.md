---
title: "Useful Constants for the Tag Groups Plugin"
order: 21
doc_category: publishpress-tag-groups
---

# Useful Constants for the Tag Groups Plugin

Table of Contents

- [CM_DEBUG](#0-toc-title)
- [TAG_GROUPS_CHUNK_SIZE](#1-toc-title)
- [TAG_GROUPS_CHUNK_TIMEOUT](#2-toc-title)
- [TAG_GROUPS_TASK_TIMEOUT](#3-toc-title)
- [TAG_GROUPS_REST_API_EDITABLE](#4-toc-title)
- [TAG_GROUPS_DISABLE_CACHE_REBUILD](#5-toc-title)
- [TAG_GROUPS_ADDITIONAL_TERM_ARGS](#6-toc-title)
- [TAG_GROUPS_ALLOW_ALL_HTML_IN_TERM_DESCRIPTION](#7-toc-title)
- [TAG_GROUPS_AUTO_POST_COUNTS](#8-toc-title)

The [Tag Groups](https://publishpress.com/links/tag-groups) plugin provides some useful tools to debug or optimize the plugin. Add these lines of code to the wp-config.php file to make changes to how [Tag Groups](https://publishpress.com/links/tag-groups) runs on your site.

### CM_DEBUG [#](#0-toc-title)

Use this constant to enable [verbose logging](https://taxopress.com/docs/how-to-use-the-debug-log/). WordPress error logs must be enabled.

```
define( 'CM_DEBUG', 'verbose' );
```

### TAG_GROUPS_CHUNK_SIZE [#](#1-toc-title)

Define the number of items that are processed in one chunk (i.e. in one Ajax call) during the troubleshooting routines. The default is 30.

```
define( 'TAG_GROUPS_CHUNK_SIZE', 10 );
```

### TAG_GROUPS_CHUNK_TIMEOUT [#](#2-toc-title)

Define the maximum time in milliseconds that one chunk is allowed to take. The default is 10 * 1000 (10 seconds).

```
define( 'TAG_GROUPS_CHUNK_TIMEOUT', 20000 );
```

### TAG_GROUPS_TASK_TIMEOUT [#](#3-toc-title)

Define the maximum time in milliseconds that one task is allowed to take. The default is 5 * 60 * 1000 (5 minutes).

```
define( 'TAG_GROUPS_TASK_TIMEOUT', 500000 );
```

### TAG_GROUPS_REST_API_EDITABLE [#](#4-toc-title)

By default the REST API is read-only. Set this constant to TRUE to make it editable (experimental).

```
define( 'TAG_GROUPS_REST_API_EDITABLE', TRUE );
```

### TAG_GROUPS_DISABLE_CACHE_REBUILD [#](#5-toc-title)

By default Tag Groups rebuilds some caches after it has been invalidated by registering a new CRON job. Use this constant to disable this behavior.

```
define( 'TAG_GROUPS_DISABLE_CACHE_REBUILD', TRUE );
```

### TAG_GROUPS_ADDITIONAL_TERM_ARGS [#](#6-toc-title)

This constant lets you define additional fields that will be whitelisted when saving a term (tag) on the backend.

If you set it to TRUE, the following fields will be allowed:

```
action
_wp_original_http_referer
_wpnonce
_wp_http_referer
term-group
tag-groups-nonce
tag-groups-taxonomy
```

You can also use an array to set any name and the corresponding sanitation callback:

```
define( 'TAG_GROUPS_ADDITIONAL_TERM_ARGS', array(
    'tag-image' => 'sanitize_text_field'
));
```

Permitted callbacks are:

```
intval
sanitize_email
sanitize_file_name
sanitize_html_class
sanitize_key
sanitize_meta
sanitize_mime_type
sanitize_option
sanitize_sql_orderby
sanitize_text_field
sanitize_textarea_field
sanitize_title
sanitize_title_for_query
sanitize_title_with_dashes
sanitize_user
```

### TAG_GROUPS_ALLOW_ALL_HTML_IN_TERM_DESCRIPTION [#](#7-toc-title)

If you choose((**Front End** settings, **Themes and Appearance**)) to keep HTML in the tag descriptions, this HTML will be sanitized. That means that only the same HTML tags are allowed that WordPress allows in posts. If you need the unsanitized HTML, set this constant to TRUE. You should only do this if you trust all users that can edit terms/tags.

```
define( 'TAG_GROUPS_ALLOW_ALL_HTML_IN_TERM_DESCRIPTION', TRUE );
```

### TAG_GROUPS_AUTO_POST_COUNTS [#](#8-toc-title)

If you activate the Tag Meta Box, post counts that are displayed in tag clouds and tag lists are calculated by group. If you want to show general post counts, as provided by WordPress, or if you find that processing the post counts uses too much system resources, you can disable it with a constant.

```
define( 'TAG_GROUPS_AUTO_POST_COUNTS', FALSE );
```

You may have to clear the cache after changing this constant.
