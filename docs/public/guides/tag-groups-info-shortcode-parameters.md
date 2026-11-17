---
title: "Tag Groups Info – Shortcode Parameters"
order: 82
doc_category: publishpress-tag-groups
---

# Tag Groups Info – Shortcode Parameters

Table of Contents

- [Shortcode](#0-toc-title)
- [Example](#1-toc-title)
- [Data Types](#data-types)
- [Parameters](#3-toc-title)[group_id](#group_id)[taxonomy](#taxonomy)[html_id](#html_id)[html_class](#html_class)[info](#info)

This guide shows you how to use and customize the shortcode for the [Tag Groups](https://publishpress.com/links/tag-groups) Info feature in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin.

## Shortcode [#](#0-toc-title)

```
[tag_groups_info]
```

## Example [#](#1-toc-title)

```
[tag_groups_info group_id=3 info=label]
```

## Data Types [#](#data-types)

- **string:** A text. For identifiers that won’t be visible to visitors you often have to replace spaces with _ or -. If the string contains spaces, you need to wrap it in quotes.
- **integer:** A number without decimal places.
- **0 or 1**: 0 turns the feature off and 1 on.

## Parameters [#](#3-toc-title)

#### group_id [#](#group_id)

Determines which groups to display. If you select more than one group, the result will be displayed in a table.

| accepted values | default | example shortcode |
| --- | --- | --- |
| One group ID, a comma-separated list of IDs or the keyword “all” | all | `group_id=1,3,6` |

#### taxonomy [#](#taxonomy)

Restrict the information only to these taxonomies. Available are only taxonomies that have been activated in the Settings.

| accepted values | default | example shortcode |
| --- | --- | --- |
| comma-separated list of taxonomy names | Taxonomies selected in the settings under “Basics”. | `taxonomy=post_tag,product_tag` |

#### html_id [#](#html_id)

Value for id in the enclosing

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `html_id="my_info_div"` |
| accepted values | default | example shortcode |
| string | empty | `html_class="make-it-count"` |
| accepted values | default | example shortcode |
| string, one of the options below | “number_of_tags” | `info=label` |
| value | description |
| number_of_tags | Shows the number of assigned tags. For a single tag group, the output is only a number. For multiple tag groups, the output is a table with the group names on the left side. |
| label | Shows the label (name) of this group, or a table of group names for multiple groups. |
| link | (only available in the premium version) |
