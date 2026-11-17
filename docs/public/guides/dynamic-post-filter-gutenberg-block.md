---
title: "Dynamic Post Filter – Gutenberg Block"
order: 28
doc_category: publishpress-tag-groups
---

# Dynamic Post Filter – Gutenberg Block

**Table of Contents**

- [Tags and Taxonomies](#tags-and-taxonomies)
  - [Include taxonomy](#include-taxonomy)
  - [Logic operator](#logic-operator)
- [Groups](#groups)
  - [Include groups](#include-groups)
  - [Initially open](#initially-open)
  - [Show groups only](#show-groups-only)
  - [Hide the close buttons](#hide-the-close-buttons)
- [Posts](#posts)
  - [Posts per page](#posts-per-page)
  - [Use a pager](#use-a-pager)
  - [Caching time](#caching-time)
  - [Order posts by](#order-posts-by)
  - [Sort order](#sort-order)
  - [Display how many posts were found](#display-how-many-posts-were-found)
- [Post template](#post-template)
  - [Customize the template](#customize-the-template)
  - [Image sizes](#image-sizes)
  - [Transition](#transition)
  - [Static taxonomy](#static-taxonomy)
  - [Static terms](#static-terms)
  - [Labels and Messages](#labels-and-messages)
  - [Placeholder for tag field](#placeholder-for-tag-field)
  - [Placeholder for posts field](#placeholder-for-posts-field)
  - [Label for default option](#label-for-default-option)
  - [Header above groups](#header-above-groups)
  - [Alert to use only existing tags](#alert-to-use-only-existing-tags)
  - [Alert to use only tags from correct group](#alert-to-use-only-tags-from-correct-group)
  - [Message if nothing was found](#message-if-nothing-was-found)
  - [Link to load more posts](#link-to-load-more-posts)
  - [Link to load previous posts](#link-to-load-previous-posts)
  - [Message for one post in total](#message-for-one-post-in-total)
  - [Message for {count} posts in total](#message-for-count-posts-in-total)
- [Other](#other)
  - [Remember selected filter and post](#remember-selected-filter-and-post)
  - [Advanced Styling](#advanced-styling)
  - [Use styling for selector.](#use-styling-for-selector)
  - [div class](#div-class)
  - [div id](#div-id)

The Dynamic Post Filter feature allows visitors to select groups and tags and immediately see posts that match their selection.

This guide will show you how to use the Gutenberg block that is available with the Dynamic Post Filter feature.

**Note:**This block can only be used **once** on a page (including widgets), and it cannot be used together with the shortcode or block of the Toggle Post Filter.

![](https://docs-media.publishpress.com/publishpress-tag-groups/dynamic-post-filter-04df9c7052.webp)

## Tags and Taxonomies

### Include taxonomy

Restrict the tags only to these taxonomies. Available are only taxonomies that have been activated in the Settings.

### Logic operator

The operator determines how selected tags and groups should be logically connected when searching for matching posts. Possible values are:

- **any tag matches** (“IN”/”OR”): At least one of the selected tags must be among the post tags for a post to be displayed.
- **all tags match** (“AND”): All selected tags across all groups must be among the post tags of the same group.
- **at least one tag matches from each group** (“IN AND”): At least one selected tag of each group must be among the post tags of the same group.
- **exact match of all selected tags** (“EXACT”): The selection of tags must be identical to the post tags of that same group, not more and not less((If you upgrade from a version before 1.39.0, go to the settings, Troubleshooting and run the maintenance.)) This option is not available for the option where you show groups only.

Groups where no tags are selected won’t be considered. If you use AND or IN AND on five groups and a visitors selects only tags from the first two groups, they may already see posts. The unused other three groups don’t count in.

If you need more precise control with different operators per group, you need to transform the block into a shortcode and then use the parameter “operator”.

## Groups

### Include groups

Restrict the posts to those having tags in these groups.

### Initially open

[Tag groups](https://publishpress.com/links/tag-groups) that are initially open.

### Show groups only

Skip the tag selection and display all products that match the selected groups.

### Hide the close buttons

Disables the possibility to remove the input fields of groups. You can use that option if you want to show a fixed set of groups that cannot be altered by visitors.

## Posts

### Posts per page

How many posts appear under the filter.

### Use a pager

If the pager is enabled then visitors can load the next or previous page.

### Caching time

Time in minutes that results remain in the cache on the server.

### Order posts by

Which field to use for sorting.

### Sort order

Whether to sort the posts in ascending or descending order. Ascending order also means from oldest to newest.

### Display how many posts were found

This line will appear in a separate field that you create with the messages block. The message itself can be customized in the “Labels and Messages” menu.

From version 1.28.0: You can optionally display the message in an overlay notification that remains visible for three seconds. This is particularly useful for mobile devices, because your visitors will receive a feedback of their search even if the message and posts blocks are outside of their screen (viewport). You can select this option and omit the message block to show only overlay notifications.

When you use a pager, the number will be the total amount. Otherwise it’ll be the amount of visible posts, that is maximum what you choose for **Posts per Page**.

## Post template

The post template defines how each post is displayed. The default template shows the featured images on the left, the post title and meta on the right and the post excerpt below.

Starting from version 1.30.0, you can choose among popular templates.

### Customize the template

Customize the post template how you need it. In the text area you see the HTML of the last selected post template. Please be aware that when you return to one of the preset templates, your custom changes will be lost.

Possible placeholders are: {post_id}, {post_title}, {permalink}, {post_excerpt} (automatically generated), {post_excerpt_html} (preserves most common html tags), {post_date}, {post_author}, {image_src} or {image_src|image size}, {image_alt}, {post_category}, {post_tags}, {custom_field:`<name>`}(a value from the post meta `<name>`).

Please note that the HTML will be sanitized, i.e. some tags and attributes might be removed. In that case try to use a class instead.

### Image sizes

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

### Transition

Transition between loading the list of posts.

### Static taxonomy

Optionally filter also by terms of an additional taxonomy (not necessarily one of the taxonomies used for [tag groups](https://publishpress.com/links/tag-groups)). Used together with static terms.

### Static terms

Optionally filter also by terms of an additional taxonomy (not necessarily one of the taxonomies used for tag groups). Used together with static taxonomy.

### Labels and Messages

#### Placeholder for tag field

Placeholder message in the tag input field. It disappears as soon as you enter tags.

#### Placeholder for posts field

Placeholder message for the list of posts.

#### Label for default option

What the pull-down menu should show if no group is selected.

#### Header above groups

Header above the list of selected groups.

#### Alert to use only existing tags

Error message if user tries to enter a tag that doesn’t exist.

#### Alert to use only tags from correct group

Error message if user tries to enter a tag from another group.

#### Message if nothing was found

Message to show if no posts were found. This will also be used for the overlay notification.

#### Link to load more posts

Label of the link to load more posts, if the pager is active.

#### Link to load previous posts

Label of the link to go back, if the pager is active.

#### Message for one post in total

Message if one post was found, if “Display the total amount of posts” is active.

#### Message for {count} posts in total

Message if many posts were found, if “Display the total amount of posts” is active. May contain the placeholder {count}

## Other

### Remember selected filter and post

The Dynamic Post Filter can optionally remember the selection of groups and tags so that your visitors who clicked on an article and then return to this page will see the same list of articles again. After loading, the page will scroll to the group selection menu. (Otherwise the browser might go to a random post, depending on the loading speed.)

The data is saved in a browser cookie. If you use several Dynamic Post Filters on your site, you should give each a different div_id so that each will have its own cookie.

The value is the time in minutes. Zero means off.

### Advanced Styling

#### Use styling for selector.

Changes the default select menu to a more stylish one.

You can set here classes to be referred to in CSS. You need some knowledge of CSS to use these options.

#### div class

Set a class for the `<div>`.

#### div id

Set an ID for the enclosing `<div>`.
