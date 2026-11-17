---
title: "Shuffle Box – Overview"
order: 68
doc_category: publishpress-tag-groups
---

# Shuffle Box – Overview

Table of Contents

- [How the Shuffle Box Works](#0-toc-title)
- [Shuffle Box Examples](#1-toc-title)
- [Shuffle Box Performance](#2-toc-title)
- [Tags with Multiple Groups](#3-toc-title)

This is a feature of the [Tag Groups](https://publishpress.com/links/tag-groups) Pro plan. The Shuffle Box is a tag cloud that allows you to filter the tags by their names or by their [tag groups](https://publishpress.com/links/tag-groups).

Tags are filtered live after you clicked a button or while you type, and they re-arrange with a stylish animation. Several parameters let you customize labels, included groups, sort order, amount of tags and much more. The Shuffle Box is responsive and even adjusts dynamically when you change the size of the window.

## How the Shuffle Box Works [#](#0-toc-title)

Initially the Shuffle Box loads all applicable tags (according to your block options or shortcode parameters, for example the maximum amount) on the page. The filtering then works on that sample by hiding tags that don’t match the filter.

That means that the filter won’t load any news tags dynamically from the database. When you press a button or enter a text, all displayed tags are taken from the initially loaded sample that you see when the filter is off.

The maximum amount of tags that you can set in the options is therefore the total amount of tags that appear when the filter is off (that means set to “all groups”, with no search text entered). It is not the amount of tags per group.

## Shuffle Box Examples [#](#1-toc-title)

The following screenshot shows a tag cloud that has been filtered by the group “Places”. We are using the parameter “append” to display the post count for each tag and we use the default theme.

![](https://docs-media.publishpress.com/publishpress-tag-groups/shuffle-box-tag-cloud-filter-wordpress-7aada24f51.webp)

The text filter can be used together with a group filter, or applied to all tags in all displayed groups:

![](https://docs-media.publishpress.com/publishpress-tag-groups/shuffle-box-tag-cloud-text-search-wordpress-c108b4e87e.webp)

The options allow you to use only the group filter, only the text filter, or both in combination.

## Shuffle Box Performance [#](#2-toc-title)

Please note that recalculating the new tag positions after each change unavoidably affects the performance for huge numbers of tags.

Try to keep the total number of tags below 200-300. You can do so by displaying only selected groups or setting a maximum amount. If you need to display hundreds of tags, test the Shuffle Box in all common browsers.

## Tags with Multiple Groups [#](#3-toc-title)

If you use the same tag with various groups and want to make sure, that the tag links only to those posts that use this tag with a particular group, you need to activate an additional option.

Use the toggle switch in the Gutenberg block or in the shortcode the parameter:

```
add_premium_filter=1
```

If the resulting list is unexpectedly empty or misses posts, please try to run Maintenance.

If “all groups” is selected, the posts won’t be filtered by tag group.

Please note that the placeholder {count} (used with “prepend”, “append” and “custom_title”) always shows the *total* number of posts for each tag – not per group.
