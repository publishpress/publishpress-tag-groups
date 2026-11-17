---
title: "Post List – Gutenberg Block"
order: 61
doc_category: publishpress-tag-groups
---

# Post List – Gutenberg Block

Table of Contents

- [Tags and Taxonomies](#0-toc-title)
- [Groups](#groups)
- [Posts](#posts)
- [Post Template](#3-toc-title)[Customize the template](#4-toc-title)[Image sizes](#5-toc-title)[Default image src](#default_image_src)
- [Labels and Messages](#labels_and_messages)
- [Advanced Styling](#advanced_styling)

This is a guide to using “Post List” block in the [Tag Groups](https://publishpress.com/links/tag-groups) Pro plugin. The following options appear in the editor side panel after you select the block:

![Post List - Gutenberg Block](https://docs-media.publishpress.com/publishpress-tag-groups/post-list-gutenberg-20e8f6e1bb.webp)

## Tags and Taxonomies [#](#0-toc-title)

- **Include taxonomy**: Restrict the tags only to these taxonomies. Available are only taxonomies that have been activated in the Settings.
- **Logic operator**: How the tags should be logically connected. Possible values are “IN” (At least one tag matches.), “AND” (All tags match.) and “IN AND” (At least one tag from each group matches).

## Groups [#](#groups)

- **Include groups**: Restrict the posts to those having tags in these groups.

## Posts [#](#posts)

- **Posts per page**: How many posts appear under the filter. Use -1 to show all. Please note that high numbers of posts may impact the performance.
- **Use a pager**: If the pager is enabled then visitors can load the next or previous page or click on a page number. It is also possible to use the cursor keys left and right to flip through pages. Please note that for a random post order the posts will be randomized for each page and the same post might therefore appear on different pages.
- **Pager position**: top, bottom, both
- **Caching time**: Time in minutes that results remain in the cache on the server.
- **Order posts by**: Which field to use for sorting.
- **Sort order**: Whether to sort the posts in ascending or descending order. Ascending order also means from oldest to newest.
- **Display the total amount of posts**: Whether to display a line that shows the total amount of posts. This message appears only if the number is greater than zero.

## Post Template [#](#3-toc-title)

The post template defines how each post is displayed. The default template shows the featured images on the left, the post title and meta on the right and the post excerpt below.

##### Customize the template [#](#4-toc-title)

Customize the post template how you need it. In the text area you see the HTML of the last selected post template. Please be aware that when you return to one of the preset templates, your custom changes will be lost.

Possible placeholders are: {post_id}, {post_title}, {permalink}, {post_excerpt} (automatically generated), {post_excerpt_html} (preserves most common html tags), {post_date}, {post_author}, {image_src} or {image_src|image size}, {image_alt}, {post_category}, {post_tags}, {custom_field:<name>}

Please note that the HTML will be sanitized, i.e. some tags and attributes might be removed. In that case try to use a class instead.

##### Image sizes [#](#5-toc-title)

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

#### Default image src [#](#default_image_src)

Here you can enter the path to an image that will be used as default, if the post doesn’t have a featured image. Leave empty to use the plugin’s default.

## Labels and Messages [#](#labels_and_messages)

- **Message if nothing was found**: Message to show if no posts were found.
- **Link to load more posts**: Label of the link to load more posts, if the pager is active.
- **Link to load previous posts**: Label of the link to go back, if the pager is active.
- **Message for one post in total**: Message if one post was found, if “Display the total amount of posts” is active.
- **Message for {count} posts in total**: Message if many posts were found, if “Display the total amount of posts” is active. May contain the placeholder {count}

## Advanced Styling [#](#advanced_styling)

You can set here classes to be referred to in CSS. You need some knowledge of CSS to use these options.

- **div**id: Define an ID for the <div>.
- **div class**: Define a class for the <div>.
- **article class**: Define a class for the enclosing <article>.
