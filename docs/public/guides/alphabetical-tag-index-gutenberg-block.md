---
title: "Alphabetical Tag Index – Gutenberg Block"
order: 9
doc_category: publishpress-tag-groups
---

# Alphabetical Tag Index – Gutenberg Block

Table of Contents

- [Tags and Taxonomies](#0-toc-title)[Include taxonomies](#taxonomy)[Smallest font size](#smallest)[Largest font size](#largest)[Tags per panel](#amount)[Order tags by](#orderby)[Sort order](#order)[Hide unused tags](#hide_empty)[Minimum post count for tags to appear](#threshold)[Prepend](#prepend)[Append](#append)[Show post count in the tooltip](#show_tag_count)[Custom title for post count = 0](#custom_title_zero)[Custom title for post count =1](#custom_title)[Custom title for post count >1](#custom_title_plural)[Link target](#link_target)[Append to link](#link_append)[Add filter to tags for multiple groups](#add_premium_filter)[Use tags of the following post](#tags_post_id)
- [Alphabet](#19-toc-title)[Include letters](#include_letters)[Exclude letters](#exclude_letters)
- [Groups](#groups_and_tabs)[Include groups](#include)
- [Columns](#24-toc-title)[Column count](#column_count)[Gap between columns](#column_gap)[Avoid breaking groups over columns](#keep_together)
- [Advanced Styling](#advanced_styling)[Title element](#h_level)[div id](#div_id)[div class](#div_class)[tags div class](#tags_div_class)[header class](#header_class)

The Alphabetical Tag Index feature in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin allows you to display a tag cloud with your groups as tabs.

This guide will show you how to use the Gutenberg block that is available with the Alphabetical Tag Index feature.

![](https://docs-media.publishpress.com/publishpress-tag-groups/alphabetical-tag-index-b45855d56e.webp)

## Tags and Taxonomies [#](#0-toc-title)

#### Include taxonomies [#](#taxonomy)

Select the “tag type”. By default we use all that are enabled in the [Tag Groups](https://publishpress.com/links/tag-groups) settings.

#### Smallest font size [#](#smallest)

Font size of the tags with the smallest post count.

#### Largest font size [#](#largest)

Font size of the tags with the largest post count.

#### Tags per panel [#](#amount)

Maximum number of tags per panel.

#### Order tags by [#](#orderby)

Field that we should compare when ordering the tag. If you choose “natural sorting” with “Tags per group” then the number will be determined after sorting by name (not natural sorting).

Note on random order: The order only changes when all object and page caches refresh.

#### Sort order [#](#order)

ascending or descending order

#### Hide unused tags [#](#hide_empty)

Hide tags with a post count of zero.

#### Minimum post count for tags to appear [#](#threshold)

Set a minimum count of published posts that use a tag for this tag to appear. Posts are counted across all groups.

#### Prepend [#](#prepend)

Character or text to place before each tag. You can use the placeholder {count} to insert the post count.

#### Append [#](#append)

Character or text to place after each tag. You can use the placeholder {count} to insert the post count.

#### Show post count in the tooltip [#](#show_tag_count)

Show the number of posts that use that tag when hovering the mouse over the tag.

#### Custom title for post count = 0 [#](#custom_title_zero)

The custom title can be configured separately for tags that have a post count of zero. You can use the placeholders {count} to insert the post count, {description} for the tag description and {name} for the tag name.

#### Custom title for post count =1 [#](#custom_title)

Tooltip text when hovering the mouse over the tag. You can use the placeholders {count} to insert the post count, {description} for the tag description and {name} for the tag name.

#### Custom title for post count >1 [#](#custom_title_plural)

The custom title can be configured separately for tags that have a post count > 1 so that you can use here plural in your text. You can use the placeholders {count} to insert the post count, {description} for the tag description and {name} for the tag name.

#### Link target [#](#link_target)

Set the “target” attribute for the links of the tags. Possible values are:

- **_blank:** open in a new tab/window
- **_self:** open in the same frame
- **_parent:** open in the parent frame
- **_top:** open in the full tab/window of this frame

In the shortcode you can also set the name of a frame.

#### Append to link [#](#link_append)

Append something to each tag’s link, for example a URL query.

#### Add filter to tags for multiple groups [#](#add_premium_filter)

When you use the premium plugin, additional query parameters in the links make sure that we can distinguish between the same tag that is used in different groups. If the resulting posts are not complete and troubleshooting doesn’t help, you can disable it here.

#### Use tags of the following post [#](#tags_post_id)

Display only tags that are assigned to the post (or page) with a specified ID. If you need this option, you mostly want to “use this post”. The menu offers also some recent posts by title.

## Alphabet [#](#19-toc-title)

#### Include letters [#](#include_letters)

A list of letters/characters that should be used, if they appear among the first letters. If you leave it empty, we use all initial letters of the tags.

#### Exclude letters [#](#exclude_letters)

A list of letters/characters that should be excluded, even if they are among the initial letters of the tags.

## Groups [#](#groups_and_tabs)

#### Include groups [#](#include)

Tag groups that will be considered in the tag cloud.

## Columns [#](#24-toc-title)

#### Column count [#](#column_count)

The number of columns.

#### Gap between columns [#](#column_gap)

Width of the gap between columns.

#### Avoid breaking groups over columns [#](#keep_together)

Try to keep the group header and its tags together in one block. That makes it easier to understand which tags belong to which group, but it might lead to empty spaces at the bottom of columns.

## Advanced Styling [#](#advanced_styling)

You can set here IDs and classes to be referred to in CSS. You need some knowledge of CSS to use these options.

#### Title element [#](#h_level)

Level of the HTML <h?> element.

#### div id [#](#div_id)

Define an ID for the enclosing <div>.

1. If you display more than one tag cloud on the same page, each shortcode needs a different value. The safest solution is to leave it empty so that div_id automatically receives a random value.
2. Avoid words that are used by WordPress such as “content” or “main”. Each ID must be unique for the entire page.

#### div class [#](#div_class)

Define a class for the enclosing <div>. Here you can apply some themes.

#### tags div class [#](#tags_div_class)

Define a class for the tags container.

#### header class [#](#header_class)

Define a class for the header.
