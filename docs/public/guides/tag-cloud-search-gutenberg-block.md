---
title: "Tag Cloud Search – Gutenberg Block"
order: 76
doc_category: publishpress-tag-groups
---

# Tag Cloud Search – Gutenberg Block

**Table of Contents**

- [Connection](#connection)
  - [Class name used in the tag cloud(s)](#class-name-used-in-the-tag-clouds)
- [Filter](#filter)
  - [Search also in tag description](#search-also-in-tag-description)
- [Labels and Messages](#labels-and-messages)
  - [Label](#label)
  - [Placeholder](#placeholder)
- [Advanced Styling](#advanced-styling)
  - [div class](#div-class)

Tag Cloud Search is a feature of the [Tag Groups](https://publishpress.com/links/tag-groups) Pro plugin. The Tag Cloud Search is an element that you can add into a page to let visitors filter tag clouds by tag names.

This guide will show you how to use the Gutenberg block that is available with the Tag Cloud Search feature.

![](https://docs-media.publishpress.com/publishpress-tag-groups/tag-cloud-search-block-1928480160.webp)

## Connection

### Class name used in the tag cloud(s)

This parameter is a class name (selector) that creates the connection between the search field and one or more [supported tag clouds](https://taxopress.com/docs/tag-cloud-search-overview/). By default it connects to all supported tag clouds on the same page.

Enter the same class name that you use in the tag cloud as “div_class” (or “table_class”). Example:

```
searchable-tag-cloud
```

If you also need other class names in the tag cloud, you simply enter all of them in the tag cloud as “div class”, separated by spaces. Example:

```
green-tags other-styling searchable-tag-cloud
```

In the Tag Cloud block you enter only “searchable-tag-cloud” – just the part that makes the connection.

## Filter

### Search also in tag description

If you activate this option, the filter will also show tags where a part of the “tag description” matches the words in the text field.

The description can be added to tags when you create a new tag or click on a tag name to edit its information. It’s not editable via “Quick Edit” in the tag list.

Filtering by description works only if you display the description in the “custom title” (aka tooltip, visible when you hold your mouse pointer over the tag). Use the placeholder `{description}` in the custom title field of the connected tag cloud.

## Labels and Messages

### Label

An optional label, displayed above or left of the input field.

### Placeholder

The text that appears in the empty input field.

## Advanced Styling

### div class

You can use this to access the input field with CSS.
