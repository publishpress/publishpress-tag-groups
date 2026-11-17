---
title: "How to Display Tags in a Custom Sort Order?"
order: 40
doc_category: publishpress-tag-groups
---

# How to Display Tags in a Custom Sort Order?

Table of Contents

- [Step #1. Sort tags with a 3rd-party plugin](#0-toc-title)
- [Step #2. Use "term order"](#1-toc-title)
- [Other notes](#2-toc-title)

Most front end features of the [Tag Groups](https://publishpress.com/links/tag-groups) plugin allow you to set the sort order of tags with the parameters **orderby** (the property used for sorting) and **order** (up or down). The tags in the menu of the Toggle Post Filter use **term_orderby** and **term_order**.

WordPress lets you sort by name, post count etc. out of the box. Sometimes, however, you need a custom sort order. Imagine, for example, the following list of tags:

- 10 minutes
- 30 minutes
- 1 hour
- 15 hours
- 1 day

The default sort options won’t let you sort these tag names in chronological order. While it was always possible to use another field of the tag data where you entered numbers, you can also sort by drag and drop.

This process requires two steps that we explain below:

## Step #1. Sort tags with a 3rd-party plugin [#](#0-toc-title)

First you need to install a 3rd-party plugin that lets you drag and drop the tags into the desired order. We tested:

- [Simple Custom Post Order](https://wordpress.org/plugins/simple-custom-post-order/)
- [Custom Taxonomy Order](https://wordpress.org/plugins/custom-taxonomy-order-ne/)

Other plugins might work as well.((They must save the order as integer in the *term_order* column of the database table where terms are stored. If you cannot verify that, simply give it a try.))

**Note:** If the plugin lets you sort the tags directly in the tag list and you notice that the tag order is not consistent across groups, you may have to disable the filtering by tag group while sorting.

## Step #2. Use “term order” [#](#1-toc-title)

Then you only need to use **orderby=term_order** (or **term_orderby**=term_order****) in the shortcode.

Example:

```
[tag_groups_cloud orderby=term_order]
```

In the Gutenberg block go to “Order tags by” and select “Term Order”:

![](https://docs-media.publishpress.com/publishpress-tag-groups/tags-custom-order-gutenberg-block-wordpress-726ae62351.webp)

## Other notes [#](#2-toc-title)

- [Tag Groups](https://publishpress.com/links/tag-groups) can use the *term_order* even after the 3rd-party plugin has been deactivated. Just make sure it doesn’t remove its data when you uninstall it. It depends on the particular plugin how they handle that.
- If for some reason you cannot use a 3rd-party plugin or it cannot extend the database tables, there is a fallback solution: Write the custom order as numbers into the description field of the tags. Then order them by description. Don’t forget to use custom_title=”” in the tag clouds to hide the description, which by default is displayed as “tooltip”.
