---
title: "Simple Tag Cloud – Gutenberg Block"
order: 18
doc_category: publishpress-tag-groups
---

# Simple Tag Cloud – Gutenberg Block

Table of Contents

- [Tags and Terms](#0-toc-title)[Included taxonomies](#taxonomy)[Smallest font size](#smallest)[Largest font size](#largest)[Tags per group](#amount)[Order tags by](#orderby)[Sort order](#order)[Hide unused tags](#hide_empty)[Minimum post count for tags to appear](#threshold)[Separator](#separator)[Adjust separator size to following tag](#adjust_separator_size)[Separator size](#separator_size)[Prepend](#prepend)[Append](#append)[Show post count in the tooltip](#show_tag_count)[Custom title for post count = 0](#custom_title_zero)[Custom title for post count =1](#custom_title)[Custom title for post count >1](#custom_title_plural)[Append to the link](#link_append)[Link Target](#link_target)[Add filter to tags for multiple groups](#add_premium_filter)[Use tags of the following post](#tags_post_id)
- [Groups](#groups_and_tabs)[Include groups](#include)[Use groups of the following post](#groups_post_id)
- [Advanced Styling](#advanced_styling)[div id](#div_id)[div class](#div_class)

The Simple Tag Cloud feature is part of the [Tag Groups](https://publishpress.com/links/tag-groups) Pro plugin. This feature allows you to display taxonomy terms in a traditional “Tag Cloud” layout, but with many creative and stylish options.

This guide will show you how to use the Gutenberg block that is available with the Simple Tag Cloud.

## Tags and Terms [#](#0-toc-title)

#### Included taxonomies [#](#taxonomy)

Restrict the tags only to these taxonomies (“tag types”). Available are only taxonomies that have been activated in the [Tag Groups](https://publishpress.com/links/tag-groups) Settings.

#### Smallest font size [#](#smallest)

Font-size in pt of the smallest tags.

#### Largest font size [#](#largest)

Font-size in pt of the largest tags.

#### Tags per group [#](#amount)

Maximum amount of tags in one cloud (per group). Pull the slider to zero to show an unlimited number.

#### Order tags by [#](#orderby)

Which field to use for sorting the tags.

Note on using “Natural Sorting”: Since WordPress doesn’t natively offer natural sort order, we have to first order them by name and then by natural sort order. If you limit the number of tags, the amount of tags is therefore determined by cutting off with “name” sorting and then sorting them with “natural”.

Note on random order: The order only changes when all object and page caches refresh.

#### Sort order [#](#order)

Whether to sort the tags in ascending or descending order. Ascending order also means from oldest to newest.

#### Hide unused tags [#](#hide_empty)

Whether to hide or show tags that are not used by any post.

#### Minimum post count for tags to appear [#](#threshold)

Set a minimum count of published posts that use a tag for this tag to appear. Posts are counted across all groups.

#### Separator [#](#separator)

A separator between the tags.

#### Adjust separator size to following tag [#](#adjust_separator_size)

Disable if you want to set a fixed size.

#### Separator size [#](#separator_size)

The size of the separator, if not automatically adjusted to the size of the following tag.

#### Prepend [#](#prepend)

Prepend to each tag label. Can obtain the placeholder {count} that will be replaced with the post count for that tag.

#### Append [#](#append)

Append to each tag label. Can obtain the placeholder {count} that will be replaced with the post count for that tag.

#### Show post count in the tooltip [#](#show_tag_count)

Whether to show the number of posts as tooltip (behind the tag description) when hovering the mouse over the tag.

#### Custom title for post count = 0 [#](#custom_title_zero)

The custom title can be configured separately for tags that have a post count of zero. You can use the placeholders {count} to insert the post count, {description} for the tag description and {name} for the tag name.

#### Custom title for post count =1 [#](#custom_title)

Tooltip text when hovering the mouse over the tag. You can use the placeholders {count} to insert the post count, {description} for the tag description and {name} for the tag name.

#### Custom title for post count >1 [#](#custom_title_plural)

The custom title can be configured separately for tags that have a post count > 1 so that you can use here plural in your text. You can use the placeholders {count} to insert the post count, {description} for the tag description and {name} for the tag name.

#### Append to the link [#](#link_append)

Append something to each tag’s link, for example a query.

#### Link Target [#](#link_target)

Set the “target” attribute for the links of the tags. Possible values are:

- **_blank:** open in a new tab/window
- **_self:** open in the same frame
- **_parent:** open in the parent frame
- **_top:** open in the full tab/window of this frame

In the shortcode you can also set the name of a frame.

#### Add filter to tags for multiple groups [#](#add_premium_filter)

When you use the premium plugin, additional query parameters in the links make sure that we can distinguish between the same tag that is used in different groups. If the resulting posts are not complete and troubleshooting doesn’t help, you can disable it here.

#### Use tags of the following post [#](#tags_post_id)

Display only tags that are assigned to the post (or page) with a specified ID. If you need this option, you mostly want to “use this post”. The menu offers also some recent posts by title.

## Groups [#](#groups_and_tabs)

#### Include groups [#](#include)

Tag groups that will be considered in the tag cloud.

#### Use groups of the following post [#](#groups_post_id)

Display only groups of which at least one assigned tag is also assigned to the selectd post (or page). Matching groups will be added to the list specified by **Include groups**.

## Advanced Styling [#](#advanced_styling)

You can set here IDs and classes to be referred to in CSS. You need some knowledge of CSS to use these options.

#### div id [#](#div_id)

Define an ID for the enclosing <div>.

1. If you display more than one tag cloud on the same page, each shortcode needs a different value. The safest solution is to leave it empty so that div_id automatically receives a random value.
2. Avoid words that are used by WordPress such as “content” or “main”. Each ID must be unique for the entire page.

#### div class [#](#div_class)

Define a class for the enclosing <div>. Here you can apply some themes.
