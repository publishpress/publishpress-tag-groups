---
title: "Dynamic Post Filter – Shortcode Parameters"
order: 29
doc_category: publishpress-tag-groups
---

# Dynamic Post Filter – Shortcode Parameters

**Table of Contents**

- [Shortcode](#shortcode)
- [Example](#example)
- [Data Types](#data-types)
- [Parameters](#parameters)
  - [Tags and Terms](#tags-and-terms)
  - [taxonomy](#taxonomy)
  - [operator](#operator)
  - [Groups](#groups)
  - [include](#include)
  - [open](#open)
  - [hide_close_buttons](#hide_close_buttons)
  - [sumoselect](#sumoselect)
  - [groups_only](#groups_only)
  - [Posts](#posts)
  - [caching_time](#caching_time)
  - [posts_per_page](#posts_per_page)
  - [pager](#pager)
  - [orderby](#orderby)
  - [order](#order)
  - [display_amount](#display_amount)
  - [template](#template)
  - [transition](#transition)
  - [static_taxonomy](#static_taxonomy)
  - [static_terms](#static_terms)
- [Other](#other)
  - [persistent_filter](#persistent_filter)
- [Labels and Messages](#labels-and-messages)
  - [placeholder](#placeholder)
  - [posts_placeholder](#posts_placeholder)
  - [default_option_label](#default_option_label)
  - [groups_header](#groups_header)
  - [message_only_existing](#message_only_existing)
  - [message_same_group](#message_same_group)
  - [message_nothing_found](#message_nothing_found)
  - [message_load_more](#message_load_more)
  - [message_go_back](#message_go_back)
  - [message_amount_singular](#message_amount_singular)
  - [message_amount_plural](#message_amount_plural)
- [Advanced Styling](#advanced-styling)
  - [div_class](#div_class)
  - [div_id](#div_id)

This guide shows you how to use and customize the shortcode for the Dynamic Post Filter feature in the [Tag Groups](https://publishpress.com/links/tag-groups) Pro plugin.

**Note:**This shortcode or block can only be used **once** on a page (including widgets), and it cannot be used together with the shortcode or block of the Dynamic Post Filter With Toggles.

## Shortcode

```
[tag_groups_dpf]
```

You can optionally display posts separated from the menu. Simply use in this shortcode separate=1 and add a second shortcode:

```
[tag_groups_dpf_body]
```

## Example

```
[tag_groups_dpf include=1,2,3,5 open=1,2,3,5 caching_time=60 persistent_filter=60 pager=1 display_amount=1 transition=fade]
```

## Data Types

- **string:** A text. For identifiers that won’t be visible to visitors you often have to replace spaces with _ or -. If the string contains spaces, you need to wrap it in quotes.
- **integer:** A number without decimal places.
- **0 or 1**: 0 turns the feature off and 1 on.

## Parameters

### Tags and Terms

#### taxonomy

Restrict the tags only to these taxonomies. Available are only taxonomies that have been activated in the Settings.

| accepted values | default | example |
| --- | --- | --- |
| comma-separated list of taxonomy names | Taxonomies selected in the settings under “Basics”. | `taxonomy=post_tag,product_tag` |

#### operator

The operator determines how selected tags and groups should be logically connected when searching for matching posts. Possible values are “IN”/”OR” (at least one tag is among the post tags), “AND” (all tags are among the post tags of the same group), “IN AND” (at least one tag from each group is among the post tags), “EXACT” (the selection of tags must be identical to the post tags of that same group, not more and not less; this option is not available for the option groups_only=1), or a per-group definition:

```
operator="all:OPERATOR|group ID:OPERATOR|group ID:OPERATOR| ..."
```

Remarks about the per-group notation:

- You need at least one entry with the keyword “all”. The corresponding operator determines how the groups are connected.
- *OPERATOR* can be AND, OR or EXACT (upper or lower case). IN is an alias of OR.
- The order of groups doesn’t matter.
- If you use this format of the operator, all groups that you don’t specify will fall back to the operator OR.

**Note for the operator “exact”:** If you upgrade from a version before 1.39.0, go to the settings, Troubleshooting and run the maintenance.

| accepted values | default | example |
| --- | --- | --- |
| string | IN/OR | `operator="IN AND"`  
`operator="all:and\|1:or\|5:exact\|3:and"` |

### Groups

#### include

[Tag groups](https://publishpress.com/links/tag-groups) that will be selectable by visitors. In the short code you use the IDs, which you find in the left column in the list of groups. Empty or not used means that all tag groups will be used.

| accepted values | default | example |
| --- | --- | --- |
| comma-separated list of integers | empty (=all) | `include=1,5,9` |

#### open

Tag groups that are initially open. If you use include, then all selected groups must also be included. Empty or not used means that all tag groups will be open.

| accepted values | default | example |
| --- | --- | --- |
| comma-separated list of integers | empty (=all) | `open=1,9` |

#### hide_close_buttons

Disables the possibility to remove the input fields of groups. You can use that option together with the include and open parameters if you want to show a fixed set of groups that cannot be altered by visitors.

| accepted values | default | example |
| --- | --- | --- |
| integer | 0 | `hide_close_buttons=1` |

#### sumoselect

Changes the default select menu to a more stylish one.

| accepted values | default | example |
| --- | --- | --- |
| integer | 1 | `sumoselect=0` |

#### groups_only

Skip the tag selection and display all products that match the selected groups.

| accepted values | default | example |
| --- | --- | --- |
| integer | 0 | `groups_only=1` |

### Posts

#### caching_time

Time in minutes that results remain in the cache.

| accepted values | default | example |
| --- | --- | --- |
| integer | 10 | `caching_time=10` |

#### posts_per_page

How many posts appear under the filter. Use -1 to show all.

Please note that high numbers of posts may impact the performance.

Tip: If you use the parameter “display_amount” you can reduce the additional database request by using posts_per_page=-1 instead of a high number of posts.

| accepted values | default | example |
| --- | --- | --- |
| integer | 5 | `posts_per_page=10` |

#### pager

If the pager is enabled then visitors can load the next or previous page.

| accepted values | default | example |
| --- | --- | --- |
| integer | 0 | `pager=1` |

#### orderby

Which field to use for sorting.

| option | notes |
| --- | --- |
| author |  |
| date |  |
| modified |  |
| rand | random order; recommended not to use with a pager/pagination |
| title |  |
| comment_count |  |

| accepted values | default | example |
| --- | --- | --- |
| string | date | `orderby=title` |

#### order

Whether to sort the posts in ascending or descending order. Ascending order also means from oldest to newest.

| accepted values | default | example |
| --- | --- | --- |
| string | DESC | `order=asc` |

#### display_amount

Whether to display a line that shows the total amount of posts.

| accepted values | default | example |
| --- | --- | --- |
| 0 or 1 | 0 | `display_amount=1` |

#### template

HTML code that contains some placeholders. If you use only one template on your entire website, it is recommended to leave this parameter empty and edit the default template in the Front End settings.

In order to prevent that WordPress parses the HTML and adds malformed `<p>` tags, it is recommended to encode it before using it.

Possible placeholders are: {post_id}, {post_title}, {permalink}, {post_excerpt} (automatically generated), {post_excerpt_html} (preserves most common html tags), {post_date}, {post_author}, {image_src} or {image_src|image size}, {image_alt}, {post_category}, {post_tags}, {custom_field:`<name>`}(a value from the post meta `<name>`).

image_src also accepts percentages or “auto” as values. If you want to use pixels, you enter the number without any “px”

| accepted values | default | example |
| --- | --- | --- |
| string | see below | template='JTNDZGl2JTIwY2xh' |

Default template:

```

{post_title} #
Posted on {post_date} by {post_author}.
{post_category}
{post_excerpt}
```

**Image sizes**

The following formats are possible:

- keywords like “thumbnail”, “medium”, “large” or any custom image size((Custom sizes may be registered by your theme or another plugin.)). **This method provides the best performance when loading an image.** You can change the sizes in the general Media settings of your WordPress site and then use a plugin such as Regenerate Thumbnails to generate the new sizes.
- width and height, separated by “x”. On both sides you can use numbers (sizes in pixel), percentages or the keyword “auto”. The image loaded by the browser may actually be larger and scaled down.

Examples for the image tag:

```

```

```

```

```

```

#### transition

Transition between loading the list of posts.

| accepted values | default | example |
| --- | --- | --- |
| string | none | `transition=fade` |

#### static_taxonomy

Optionally filter also by terms of an additional taxonomy (not necessarily one of the taxonomies used for tag groups). Used together with [static_terms](#static_terms).

| accepted values | default | example |
| --- | --- | --- |
| string | none | `static_taxonomy=category` |

#### static_terms

Optionally filter also by terms of an additional taxonomy (not necessarily one of the taxonomies used for tag groups). Used together with [static_taxonomy](#static_taxonomy).

| accepted values | default | example |
| --- | --- | --- |
| comma-separated list of integers | none | `static_terms=42,255` |

## Other

### persistent_filter

The Dynamic Post Filter can optionally remember the selection of groups and tags so that your visitors who clicked on an article and then return to this page will see the same list of articles again. After loading, the page will scroll to the group selection menu. (Otherwise the browser might go to a random post, depending on the loading speed.)

The data is saved in a browser cookie. If you use several Dynamic Post Filters on your site, you should give each a different div_id so that each will have its own cookie.

The value is the time in minutes. Zero means off.

| **accepted values** | **default** | **example** |
| --- | --- | --- |
| integer | 0 (off) | ` persistent_filter=30` |

## Labels and Messages

### placeholder

Placeholder message in the tag input field. It disappears as soon as you enter tags.

| accepted values | default | example |
| --- | --- | --- |
| string | “Please enter your tags here.” | `placeholder=""` |

### posts_placeholder

Placeholder message for the list of posts.

| accepted values | default | example |
| --- | --- | --- |
| string | “Please select a group.” | `posts_placeholder=""` |

### default_option_label

What the pull-down menu should show if no group is selected.

| accepted values | default | example |
| --- | --- | --- |
| string | “Filter by group” | `default_option_label=""` |

### groups_header

Header above the list of selected groups.

| accepted values | default | example |
| --- | --- | --- |
| string | “Selected groups:” | `groups_header=""` |

### message_only_existing

Error message if user tries to enter a tag that doesn’t exist.

| accepted values | default | example |
| --- | --- | --- |
| string | 0 | `message_only_existing=""` |

### message_same_group

Error message if user tries to enter a tag from another group.

| accepted values | default | example |
| --- | --- | --- |
| string | 0 | `message_same_group=""` |

### message_nothing_found

Message to show if no posts were found.

| accepted values | default | example |
| --- | --- | --- |
| string | “Nothing found.” | `message_nothing_found=""` |

### message_load_more

Label of the link to load more posts, if the pager is active.

| accepted values | default | example |
| --- | --- | --- |
| string | “Load more” | `message_load_more=""` |

### message_go_back

Label of the link to go back, if the pager is active.

| accepted values | default | example |
| --- | --- | --- |
| string | “Go back” | `message_go_back=""` |

### message_amount_singular

Message if one post was found, if display_amount is active.

| accepted values | default | example |
| --- | --- | --- |
| string | “1 post found.” | `message_amount_singular="One recipe found."` |

### message_amount_plural

Message if many posts were found, if display_amount is active. May contain the placeholder {count}

| accepted values | default | example |
| --- | --- | --- |
| string | “{count} posts found.” | `message_amount_plural="We found {count} recipes."` |

## Advanced Styling

You can set here classes to be referred to in CSS. You need some knowledge of CSS to use these options.

### div_class

Define a class for the `<div>`.

| accepted values | default | example |
| --- | --- | --- |
| string | empty | `div_class="change-pointer"` |

### div_id

Define an ID for the enclosing `<div>`.

| accepted values | default | example |
| --- | --- | --- |
| string |  | `div_id="my_id"` |
