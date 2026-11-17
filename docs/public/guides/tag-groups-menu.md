---
title: "Tag Groups Menu – Overview"
order: 84
doc_category: publishpress-tag-groups
---

# Tag Groups Menu – Overview

The [Tag Groups](https://publishpress.com/links/tag-groups) Pro plugin gives you the possibility to filter posts by tag group. The filter is applied by adding a parameter to the URL where you list posts. [You can read more about it here](https://taxopress.com/docs/using-the-post-filter-on-the-front-end/).

Since you don’t want to let your visitors have to modify the URL manually, you can use the URL with that parameter in a menu, a button and so on.

Instead of creating that menu yourself, you can use a shortcode that will display a drop-down menu. Visitors select a group and are then redirected to the list of posts that is filtered by the selected tag group.

Please note that this page where you list your posts must be set in your WordPress Settings under “Reading”. By default it is your front page. If you, however, use a static page as front page, you need to choose another page for your posts.

The default format for this shortcode is:

```
[tag_groups_menu]
```

Parameters allow you to

- show or hide a submit button. If it is hidden, the filtering is triggered by selecting an option from the menu;
- set a taxonomy (such as product_tag);
- search among WooCommerce products; and
- give a custom URL where posts will be listed.
