---
title: "Tag Cloud Search – Shortcode Parameters"
order: 78
doc_category: publishpress-tag-groups
---

# Tag Cloud Search – Shortcode Parameters

Table of Contents

- [Shortcode](#0-toc-title)
- [Example](#1-toc-title)
- [Parameters](#2-toc-title)[tag_cloud_class](#tag_cloud_class)[search_title](#search_title)[label](#label)[placeholder](#placeholder)[div_class](#div_class)

This guide shows you how to use and customize the shortcode for the Tag Cloud Search feature in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin.

## Shortcode [#](#0-toc-title)

```
[tag_groups_cloud_search]
```

## Example [#](#1-toc-title)

```
[tag_groups_cloud_search tag_cloud_class="my-tag-cloud" label="Looking for a name?" placeholder="Type here"]
```

## Parameters [#](#2-toc-title)

#### tag_cloud_class [#](#tag_cloud_class)

This parameter is a class name (selector) that creates the connection between the search field and one or more [supported tag clouds](https://taxopress.com/docs/tag-cloud-search-overview/). By default it connects to all supported tag clouds on the same page.

Enter the same class name that you use in the tag cloud as “div_class” (or “table_class”). Example:

```
[tag_groups_cloud_search tag_cloud_class="searchable-tag-cloud"]
[tag_groups_cloud div_class="searchable-tag-cloud"]
```

If you also need other class names in the tag cloud, you simply enter all of them in the tag cloud as “div_class”, separated by spaces. Example:

```
[tag_groups_cloud_search tag_cloud_class="searchable-tag-cloud"]
[tag_groups_cloud div_class="green-tags other-styling searchable-tag-cloud"]
```

| accepted values | default | example shortcode |
| --- | --- | --- |
| string (class name) | tag-groups-cloud | `tag_cloud_class=searchable-tag-cloud-2` |

#### *search_title* [#](#search_title)

If you activate this option, the filter will also show tags where a part of the “tag description” matches the words in the text field.

The description can be added to tags when you create a new tag or click on a tag name to edit its information. It’s not editable via “Quick Edit” in the tag list.

Filtering by description works only if you display the description in the “custom title” (aka tooltip, visible when you hold your mouse pointer over the tag). Use the placeholder `{description}` in the custom title field of the connected tag cloud.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer (0 or 1) | 0 (off) | `search_title=1` |

#### label [#](#label)

An optional label, displayed above or left of the input field.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `label="Search:"` |

#### placeholder [#](#placeholder)

The text that appears in the empty input field.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | Search a tag | `placeholder="Start typing"` |

#### div_class [#](#div_class)

You can use this to access the input field with CSS.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `div_class="my-custom-styling"` |
