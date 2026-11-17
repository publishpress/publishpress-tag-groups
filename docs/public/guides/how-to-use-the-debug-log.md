---
title: "How To Use The Debug Log in Tag Groups"
order: 46
doc_category: publishpress-tag-groups
---

# How To Use The Debug Log in Tag Groups

**Table of Contents**

- [Activate debugging](#activate-debugging)
- [Identify messages from Tag Groups](#identify-messages-from-tag-groups)
- [Verbose logging](#verbose-logging)

WordPress provides a debug mode that lets you see notices, warnings and error messages. The [Tag Groups](https://publishpress.com/links/tag-groups) plugin integrates with this mode so that it’s easy to see debug messages for our plugin.

## Activate debugging

You activate it by setting the flags [WP_DEBUG](https://wordpress.org/support/article/debugging-in-wordpress/#wp_debug) and [WP_DEBUG_LOG](https://wordpress.org/support/article/debugging-in-wordpress/#wp_debug_log) in the wp-config.php.

To save you the trouble of editing the wp-config.php file, you can use the plugin [WP Debugging](https://wordpress.org/plugins/wp-debugging/). After installation, go to the plugin screen under the Tools menu and select “Set WP_DEBUG to true”.

The plugin also lets you install the [Debug Bar](https://wordpress.org/plugins/debug-bar/) plugin that adds a menu entry “Debug Quick Look” where you can see the debug messages.

## Identify messages from Tag Groups

The [Tag Groups](https://publishpress.com/links/tag-groups) plugin adds its own messages that appear in the debug.log file prepended by `[Tag Groups]` or `[Tag Groups Pro]`.

![](https://docs-media.publishpress.com/publishpress-tag-groups/screenshot-2020-12-15-view-your-file1-379d7a76ea.webp)

## Verbose logging

By default these messages inform you only about serious issues. If you turn on **verbose logging**, however, you will also receive details about deprecated shortcodes, caching, locale settings that cannot be provided by the operating system, processing of meta etc.. This information can help you understand what is going on under the hood.

Activate verbose logging on the page Troubleshooting -> Debugging

![](https://docs-media.publishpress.com/publishpress-tag-groups/screenshot-2021-01-05-troubleshooting-debug-log-wordpress-a89c388c3b.webp)

or add to wp-config.php:

```
define( 'CM_DEBUG', 'verbose' );
```

The additional messages will also appear in the file debug.log and can be viewed with the [Debug Bar](https://wordpress.org/plugins/debug-bar/) plugin.
