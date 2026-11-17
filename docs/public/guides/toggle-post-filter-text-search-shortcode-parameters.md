---
title: "Toggle Post Filter, Text Search – Shortcode Parameters"
order: 106
doc_category: publishpress-tag-groups
---

# Toggle Post Filter, Text Search – Shortcode Parameters

**Table of Contents**

- [Shortcode](#shortcode)
- [Example](#example)
- [Data Types](#data-types)
- [Parameters](#parameters)
  - [placeholder](#placeholder)
  - [search_trigger](#search_trigger)

This input field can be used for text search. This guide shows you how to use and customize the shortcode for the text search option for Toggle Post Filter in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin.

## Shortcode

```
[tag_groups_tpf_text_search]
```

## Example

```
[tag_groups_tpf_text_search placeholder="Type here"]
```

## Data Types

- **string:** A text. For identifiers that won’t be visible to visitors you often have to replace spaces with _ or -. If the string contains spaces, you need to wrap it in quotes.
- **integer:** A number without decimal places.
- **0 or 1**: 0 turns the feature off and 1 on.

## Parameters

This overview assumes that you know how to use shortcodes. Shortcodes are the traditional WordPress way how to add features into posts, pages and widgets.

### placeholder

Default text of the input field

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| “Reset filter” | string | `placeholder="Type here"` |

### search_trigger

1: on enter; 2: enter or timed

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| “light” | string | `search_trigger=1` |
