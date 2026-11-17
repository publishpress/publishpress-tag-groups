---
title: "Toggle Post Filter, Posts – Gutenberg Block"
order: 95
doc_category: publishpress-tag-groups
---

# Toggle Post Filter, Posts – Gutenberg Block

Table of Contents

- [Posts](#0-toc-title)[Show all posts when no filter is activated.](#default_show_posts)[Posts per page](#posts_per_page)[Use a pager](#pager)[Pager position](#pager_position)[Order posts by](#orderby)[Sort order](#order)[Display how many posts were found](#display_amount)[Transition](#transition)
- [Post template](#template)[Customize the template](#10-toc-title)[Image sizes](#11-toc-title)[Default image source](#default_image_src)
- [Post Layout and Theme](#13-toc-title)[Post layout](#layout_body)[Theme](#theme)
- [Labels and Messages](#labels_and_messages)[Placeholder for posts field](#posts_placeholder)[Message if nothing was found](#message_nothing_found)[Link to load more posts](#message_load_more)[Link to load previous posts](#message_go_back)[Message for one post in total](#message_amount_singular)[Message for {count} posts in total](#message_amount_plural)
- [Advanced Styling](#advanced_styling)[div class](#div_class)[div id](#div_id)

The Toggle Post Filter is a frontend feature in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin. Visitors can select tags and see a list of posts that match their search criteria.

This guide will show you how to use the Gutenberg block that is available with Toggle Post Filter to show a list of posts.

**Note:**The Toggle Post Filter can only be used once on a page (including widgets), and it cannot be used together with the shortcode or block of the Dynamic Post Filter.

![](https://docs-media.publishpress.com/publishpress-tag-groups/toggle-post-filter-body-block-19c2c42011.webp)

## Posts [#](#0-toc-title)

#### Show all posts when no filter is activated. [#](#default_show_posts)

Show a list of posts if all toggles are off and not text search is in use. This will contribute to a real “filter” impression. That means that you start out from the entirety of posts and use search criteria to narrow them down.

#### Posts per page [#](#posts_per_page)

Set the maximum number of posts per page.

#### Use a pager [#](#pager)

If the pager is enabled then visitors can load the next or previous page or click on a page number. It is also possible to use the cursor keys left and right to flip through pages.

Please note that for a random post order the posts will be randomized for each page and the same post might therefore appear on different pages.

#### Pager position [#](#pager_position)

- top
- bottom
- both

#### Order posts by [#](#orderby)

Which field to use for sorting posts.

#### Sort order [#](#order)

Whether to sort the posts in ascending or descending order. Ascending order also means from oldest to newest.

#### Display how many posts were found [#](#display_amount)

This line will appear in a separate field that you create with the messages block. The message itself can be customized in the “Labels and Messages” menu.

From version 1.28.0: You can optionally display the message in an overlay notification that remains visible for three seconds. This is particularly useful for mobile devices, because your visitors will receive a feedback of their search even if the message and posts blocks are outside of their screen (viewport). You can select this option and omit the message block to show only overlay notifications.

When you use a pager, the number will be the total amount. Otherwise it’ll be the amount of visible posts, that is maximum what you choose for **Posts per Page**.

#### Transition [#](#transition)

Transition when loading the list of posts.

## Post template [#](#template)

The post template defines how each post is displayed. The default template shows the featured images on the left, the post title and meta on the right and the post excerpt below.

Starting from version 1.30.0, you can choose among popular templates.

##### Customize the template [#](#10-toc-title)

Customize the post template how you need it. In the text area you see the HTML of the last selected post template. Please be aware that when you return to one of the preset templates, your custom changes will be lost.

Possible placeholders are: {post_id}, {post_title}, {permalink}, {post_excerpt} (automatically generated), {post_excerpt_html} (preserves most common html tags), {post_date}, {post_author}, {image_src} or {image_src|image size}, {image_alt}, {post_category}, {post_tags}, {custom_field:<name>}

Please note that the HTML will be sanitized, i.e. some tags and attributes might be removed. In that case try to use a class instead.

##### Image sizes [#](#11-toc-title)

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

#### Default image source [#](#default_image_src)

Here you can insert the full URL to your own default image.

## Post Layout and Theme [#](#13-toc-title)

#### Post layout [#](#layout_body)

Layout of the **body part**, containing the posts.

- **classic:** Posts appear stacked on each other in a narrow container. If you select “classic” for the posts, also the menu should display as “classic” layout. You will also need a page layout that is wide enough to accommodate both menu and body next to each other.
- **wide:** Posts appear stacked on each other in a wide container.
- **boxed:** Posts appear in rows. Posts should approximately have the same size, otherwise you will see empty spaces.
- **columns:** Posts appear in 1-3 rows (depending on the screen size), each row from top to bottom.
- **columns (avoid splitting posts):** Same as “columns”, but we try to avoid splitting posts over different columns. This layout makes it clearer which parts belong to a post but it might lead to empty spaces at the bottom.
- **masonry:** Posts arrange as tiles, from top to bottom, trying to fill the slots equally. The post order may therefore change.
- **masonry (small):** Masonry with smaller items.
- **masonry (large):** Masonry with larger items.

#### Theme [#](#theme)

The plugin comes with two default themes, light and dark.

## Labels and Messages [#](#labels_and_messages)

#### Placeholder for posts field [#](#posts_placeholder)

Placeholder message for the list of posts.

#### Message if nothing was found [#](#message_nothing_found)

Message to show if no posts were found. This will also be used for the overlay notification.

#### Link to load more posts [#](#message_load_more)

Label of the link to load more posts, if the pager is active.

#### Link to load previous posts [#](#message_go_back)

Label of the link to go back, if the pager is active.

#### Message for one post in total [#](#message_amount_singular)

Message if one post was found, if the option “Display the total amount of posts” is enabled.

#### Message for {count} posts in total [#](#message_amount_plural)

Message if many posts were found, if the option “Display the total amount of posts” is enabled. May contain the placeholder {count}

## Advanced Styling [#](#advanced_styling)

You can set here classes to be referred to in CSS. You need some knowledge of CSS to create your own theme.

#### div class [#](#div_class)

Define a class for the <div>.

#### div id [#](#div_id)

Define an ID for the enclosing <div>.
