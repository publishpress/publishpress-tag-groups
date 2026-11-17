---
title: "Tag List – Gutenberg Block"
order: 86
doc_category: publishpress-tag-groups
---

# Tag List – Gutenberg Block

Table of Contents

- [Tags and Taxonomies](#0-toc-title)[Include taxonomies](#taxonomy)[Smallest font size](#smallest)[Largest font size](#largest)[Tags per group](#amount)[Order tags by](#orderby)[Sort order](#order)[Hide unused tags](#hide_empty)[Minimum post count for tags to appear](#threshold)[Prepend](#prepend)[Append](#append)[Show post count in the tooltip](#show_tag_count)[Custom title for post count = 0](#custom_title_zero)[Custom title for post count =1](#custom_title)[Custom title for post count >1](#custom_title_plural)[Link target](#link_target)[Append to link](#link_append)[Add filter to tags for multiple groups](#add_premium_filter)[Use tags of the following post](#tags_post_id)
- [Groups](#groups_and_tabs)[Hide empty groups](#hide_empty_content)[Include groups](#include)[Use groups of the following post](#groups_post_id)
- [Columns](#23-toc-title)[Column count](#column_count)[Gap between columns](#column_gap)[Avoid breaking groups over columns](#keep_together)

With the Tag List feature, terms are displayed in columns under their group names. You can choose which groups to show and you can customize how the tags layout.

This guide will show you how to use the Gutenberg block that is available with the Tag List feature.

![Tag List - Gutenberg Block](https://docs-media.publishpress.com/publishpress-tag-groups/tag-list-gutenberg-10e3b6558f.webp)

## Tags and Taxonomies [#](#0-toc-title)

#### Include taxonomies [#](#taxonomy)

Select the “tag type”. By default we use all that are enabled in the [Tag Groups](https://publishpress.com/links/tag-groups) settings.

#### Smallest font size [#](#smallest)

Font size of the tags with the smallest post count.

#### Largest font size [#](#largest)

Font size of the tags with the largest post count.

#### Tags per group [#](#amount)

Maximum number of tags per group.

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

## Groups [#](#groups_and_tabs)

#### Hide empty groups [#](#hide_empty_content)

Whether to hide groups without tags.

#### Include groups [#](#include)

[Tag groups](https://publishpress.com/links/tag-groups) that will be considered in the tag cloud.

#### Use groups of the following post [#](#groups_post_id)

Display only groups of which at least one assigned tag is also assigned to the selectd post (or page). Matching groups will be added to the list specified by **Include groups**.

## Columns [#](#23-toc-title)

#### Column count [#](#column_count)

The number of columns.

#### Gap between columns [#](#column_gap)

Width of the gap between columns.

#### Avoid breaking groups over columns [#](#keep_together)

Try to keep the group header and its tags together in one block. That makes it easier to understand which tags belong to which group, but it might lead to empty spaces at the bottom of columns.
