---
title: "Toggle Post Filter, Posts – Shortcode Parameters"
order: 101
doc_category: publishpress-tag-groups
---

# Toggle Post Filter, Posts – Shortcode Parameters

**Table of Contents**

- [Overview](#overview)
- [Data Types](#data-types)
- [Parameters](#parameters)
  - [Posts](#posts)
  - [default_show_posts](#default_show_posts)
  - [posts_per_page](#posts_per_page)
  - [pager](#pager)
  - [pager_position](#pager_position)
  - [orderby](#orderby)
  - [order](#order)
  - [display_amount](#display_amount)
  - [template](#template)
  - [default_image_src](#default_image_src)
  - [transition](#transition)
  - [Messages and Placeholders](#messages-and-placeholders)
  - [posts_placeholder](#posts_placeholder)
  - [message_nothing_found](#message_nothing_found)
  - [message_load_more](#message_load_more)
  - [message_go_back](#message_go_back)
  - [message_amount_singular](#message_amount_singular)
  - [message_amount_plural](#message_amount_plural)
  - [Theme and Layout](#theme-and-layout)
  - [theme](#theme)
  - [layout](#layout)
- [Advanced Styling](#advanced-styling)
  - [div_id](#div_id)
  - [div_class](#div_class)

This guide shows you how to use and customize the shortcode for posts in the Toggle Post Filter feature in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin.

**Note:**This shortcode or block can only be used once on a page (including widgets), and it cannot be used together with the shortcode or block of the Dynamic Post Filter.

## Overview

You find [here the overview of all shortcodes](https://taxopress.com/docs/toggle-post-filter-parameters/) for the Toggle Post Filter.

## Data Types

- **string:** A text. For identifiers that won’t be visible to visitors you often have to replace spaces with _ or -. If the string contains spaces, you need to wrap it in quotes.
- **integer:** A number without decimal places.
- **0 or 1**: 0 turns the feature off and 1 on.

## Parameters

### Posts

#### default_show_posts

Show a list of posts if no filter is active. This will contribute to the impression that you filter the entirety of posts by reducing their number.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | 0 (=off) | `default_show_posts=1` |

#### posts_per_page

How many posts appear under the filter. Use -1 to show all.

Please note that high numbers of posts may impact the performance.

Tip: If you use the parameter “display_amount” you can reduce the additional database request by using posts_per_page=-1 instead of a high number of posts.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | 5 | `posts_per_page=10` |

#### pager

- 0: off
- 1: Simple pager; visitors can load the next or previous page.
- 2: Pagination; visitors can click on a page number

It is also possible to use the cursor keys left and right to flip through pages.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | 0 | `pager=2` |

#### pager_position

- top
- bottom
- both

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | bottom | `pager_position=both` |

#### orderby

Which field to use for sorting the posts.

| option | notes |
| --- | --- |
| author |  |
| date |  |
| modified |  |
| rand | random order; recommended to use also a small value for caching, for example caching_time=10. Don’t disable caching if you activated paging. |
| title |  |
| comment_count |  |

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | date | `orderby=title` |

#### order

Whether to sort the posts in ascending or descending order. Ascending order also means from oldest to newest.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | DESC | `order=asc` |

#### display_amount

Whether to display how many posts were found. If the pager is off, this will be the number of displayed posts.

- 0: off
- 1: This line will appear in a separate field that you create with the messages shortcode.
- 2: The message will appear for three seconds in an overlay. If you use the messages shortcode, it will also appear there.

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0, 1 or 2 | 0 | `display_amount=1` |

#### template

HTML code that contains some placeholders. If you use only one template on your entire website, it is recommended to leave this parameter empty and edit the default template in the Front End settings.

In order to prevent that WordPress parses the HTML and adds malformed `<p>` tags, it is recommended to encode it before using it.

Possible placeholders are: {post_id}, {post_title}, {permalink}, {post_excerpt} (automatically generated), {post_excerpt_html} (preserves most common html tags), {post_date}, {post_author}, {image_src} or {image_src|image size}, {image_alt}, {post_category}, {post_tags}, {custom_field:`<name>`}

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | see below | `template='JTNDZGl2JTIwY2x'` |

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

#### default_image_src

Here you can insert the full URL to your own default image.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | none | `default_image_src=https://www.example.com/image.jpg` |

#### transition

Transition between loading the list of posts.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | none | `transition=fade` |

### Messages and Placeholders

#### posts_placeholder

Placeholder message for the list of posts.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | “Please select a group.” | `posts_placeholder="Please open the filter."` |

#### message_nothing_found

Message to show if no posts were found.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | “Nothing found.” | `message_nothing_found="No courses available."` |

#### message_load_more

Label of the link to load more posts, if the pager is active.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | “Load more” | `message_load_more="forward"` |

#### message_go_back

Label of the link to go back, if the pager is active.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | “Go back” | `message_go_back="back"` |

#### message_amount_singular

Message if one post was found, if display_amount is active.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | “1 post found.” | `message_amount_singular="One recipe found."` |

#### message_amount_plural

Message if many posts were found, if display_amount is active. May contain the placeholder {count}

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | “{count} posts found.” | `message_amount_plural="We found {count} recipes."` |

### Theme and Layout

#### theme

Available are “light” and “dark”.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | light | `theme="dark"` |

#### layout

- **classic:** Posts appear stacked on each other in a narrow container. If you select “classic” for the posts, also the menu should display as “classic” layout.
- **wide:** Posts appear stacked on each other in a wide container.
- **boxed:** Posts appear in rows. Posts should approximately have the same size, otherwise you will see empty spaces.
- **columns:** Posts appear in 1-3 rows (depending on the screen size), each row from top to bottom.
- **columns-keep-together:** Same as “columns”, but we try to avoid splitting posts over different columns. This layout makes it clearer which parts belong to a post but it might lead to empty spaces at the bottom.
- **masonry:** Posts arrange as tiles, from top to bottom, trying to fill the slots equally. The post order may therefore change.
- **masonry-small:** Masonry with smaller items.
- **masonry-large:** Masonry with larger items.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | classis | layout=”masonry” |

## Advanced Styling

You can set here classes to be referred to in CSS. You need some knowledge of CSS to use these options.

### div_id

Define an id for the enclosing `<div>`.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | tag_groups_dpf_toggle_body | `div_id="something"` |

### div_class

Define a class for the enclosing `<div>`. You can use here a class identifier for a theme. Use the “theme” parameter for built-in themes.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `div_class="my-theme"` |
