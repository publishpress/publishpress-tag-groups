---
title: "Instead of the Tag Cloud There’s An Empty Space"
order: 54
doc_category: publishpress-tag-groups
---

# Instead of the Tag Cloud There’s An Empty Space

**Table of Contents**

- [Find out what causes the error](#find-out-what-causes-the-error)

This is usually the result of a JavaScript error on that page. It can be caused by any active plugin or theme on your site. It might also happens if you use a very unusual location for the tag cloud, for example in a menu or inside another widget.

Try first if it helps to turn off the delay for revealing the tag cloud. In the Gutenberg block you find a setting under Tabs or Panels where you can turn off the delay. In the shortcode you add the parameter delay=0.

Example for a shortcode

```
[tag_groups_cloud ... delay=0]
```

## Find out what causes the error

Please try the following:

- Check if you use plugins that minimize or combine JavaScript (Autoptimize, Total Cache and others). Make sure that jQuery is loaded early or even excluded from the processed JavaScript libraries. Turn off all processing of JavaScript, clear the cache and reload the page.
- [Open the Console in your browser](https://webmasters.stackexchange.com/a/77337), reload the page and check for error messages that give a hint about where the error was caused.
- Disable other plugins and check, if the tag cloud now renders correctly. If yes, enable the plugins again one-by-one and keep reloading the page in order to find out where it breaks.
