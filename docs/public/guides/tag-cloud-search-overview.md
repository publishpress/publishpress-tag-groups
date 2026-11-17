---
title: "Tag Cloud Search – Overview"
order: 77
doc_category: publishpress-tag-groups
---

# Tag Cloud Search – Overview

Table of Contents

- [Tag Cloud Search Integrations](#0-toc-title)
- [Connect the Search to the Tag Cloud](#1-toc-title)
- [Notes on the Tag Cloud Search](#2-toc-title)

Tag Cloud Search is a feature of the [Tag Groups](https://publishpress.com/links/tag-groups) Pro plugin. The Tag Cloud Search is an element that you can add into a page to let visitors filter tag clouds by tag names.

Here’s a screenshot of the Tag Cloud Search feature. This display combines a search box with a tabbed display. We’ve added a color scheme, plus text after each tag.

![](https://docs-media.publishpress.com/publishpress-tag-groups/tag-cloud-search-ea97add2dc.webp)

## Tag Cloud Search Integrations [#](#0-toc-title)

The Tag Cloud Search feature works together with the following tag clouds:

- [Tabbed Tag Cloud](https://taxopress.com/docs/tabbed-tag-cloud-overview/)
- [Accordion Tag Cloud](https://taxopress.com/docs/accordion-tag-cloud-gutenberg-block-2/)
- [Alphabetical Tags](https://taxopress.com/docs/alphabetical-tag-index-overview/)
- [Table Tag Cloud](https://taxopress.com/docs/table-tag-cloud/)
- [Simple Tag Cloud](https://taxopress.com/docs/combined-tag-cloud/)

The Shuffle Box has its own text search field.

## Connect the Search to the Tag Cloud [#](#1-toc-title)

You simply add this feature as shortcode or Gutenberg block to the same page where you use one or more of these tag clouds. It will generate an input field where visitors can enter a search term. The filtering will happen live while they type.

In order to tell the Tag Cloud Search which tag clouds to search, you need to connect them. Simply enter the same class name under “Connection” in the block or as tag_cloud_class that you have used in the corresponding tag cloud(s). You don’t need to replace existing class names in the tag cloud. It is sufficient to append it, separated by a space.

Example for shortcodes:

```
[tag_groups_cloud_search tag_cloud_class="searchable-tag-cloud-1"]
```

On the same page you use a tag cloud with the same class name “tag-groups-cloud-1” among its classes:

```
[tag_groups_cloud div_class="tag-groups-theme-green searchable-tag-cloud-1" prepend="#" append="{count}" separator="+"]
```

Now the search will filter the tags in this cloud.

## Notes on the Tag Cloud Search [#](#2-toc-title)

- The filter always works on the tag names. You can chose to filter additionally by tag descriptions. For that to work you need to display the description in the “title” attribute (i.e. in the tooltip): Use the placeholder `{description}` in the “custom title” field or parameter.
- If you use the filter with the Tabbed Tag Cloud or the Alphabetical Tabs, we automatically open the tab of the first tag that matches the filter. This, however, doesn’t work if you connect the Tag Cloud Search with more than one tag clouds.
- The filter is case-insensitive: When you type “apple”, you see both the tags “Big Apple” and “pineapple”.
- Please note that – with or without the Tag Cloud Search – none of these tag clouds dynamically loads tags from the server. The filtering affects only tags that were *initially* loaded.
