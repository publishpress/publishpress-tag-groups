---
title: "Post List – Shortcode Parameters"
order: 62
doc_category: publishpress-tag-groups
---

# Post List – Shortcode Parameters

**Table of Contents**

- [Shortcode](#shortcode)
- [Example](#example)
- [Data Types](#data-types)
- [Parameters](#parameters)
  - [Tags and Terms](#tags-and-terms)
  - [taxonomy](#taxonomy)
  - [operator](#operator)
  - [Tag Groups](#tag-groups)
  - [include](#include)
  - [Posts](#posts)
  - [author](#author)
  - [cat](#cat)
  - [caching_time](#caching_time)
  - [posts_per_page](#posts_per_page)
  - [pager](#pager)
  - [pager_position](#pager_position)
  - [orderby](#orderby)
  - [order](#order)
  - [display_amount](#display_amount)
  - [search](#search)
  - [tag](#tag)
  - [tag__and](#tag__and)
  - [tag__in](#tag__in)
  - [tag__not_in](#tag__not_in)
  - [tag_id](#tag_id)
  - [tag_slug__and](#tag_slug__and)
  - [tag_slug__in](#tag_slug__in)
  - [template](#template)
  - [Labels and Messages](#labels-and-messages)
  - [message_nothing_found](#message_nothing_found)
  - [message_load_more](#message_load_more)
  - [message_go_back](#message_go_back)
  - [message_amount_singular](#message_amount_singular)
  - [message_amount_plural](#message_amount_plural)
- [Advanced Styling](#advanced-styling)
  - [div_class](#div_class)
  - [article_class](#article_class)

This is a guide to using shortcodes to display the “[Post List](https://taxopress.com/docs/the-post-list/)” feature in the [Tag Groups](https://publishpress.com/links/tag-groups) Pro plugin.

## Shortcode

```
[tag_groups_post_list]
```

## Example

```
[tag_groups_post_list include=1,2,3,5 display_amount=1]
```

## Data Types

- **string:** A text. For identifiers that won’t be visible to visitors you often have to replace spaces with _ or -. If the string contains spaces, you need to wrap it in quotes.
- **integer:** A number without decimal places.
- **0 or 1**: 0 turns the feature off and 1 on.

## Parameters

### Tags and Terms

#### taxonomy

Restrict the tags only to these taxonomies. Available are only taxonomies that have been activated in the Settings.

| accepted values | default | example shortcode |
| --- | --- | --- |
| comma-separated list of taxonomy names | Taxonomies selected in the settings under “Basics”. | `taxonomy=post_tag,product_tag` |

#### operator

How the tags should be logically connected. Possible values are “OR” (At least one group matches.) or “AND” (All groups match.).

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | OR | `operator="AND"` |

### Tag Groups

#### include

[Tag groups](https://publishpress.com/links/tag-groups) that will be selectable by visitors. In the short code you use the IDs, which you find in the left column in the list of groups. Empty or not used means that all tag groups will be used.

| accepted values | default | example shortcode |
| --- | --- | --- |
| comma-separated list of integers | empty (=all) | `include=1,5,9` |

### Posts

#### author

Filter by author.

| accepted values | default | example shortcode |
| --- | --- | --- |
| author ID or comma-separated list of IDs | off | `author=1,3` |

#### cat

Filter by category.

| accepted values | default | example shortcode |
| --- | --- | --- |
| category ID | off | `cat=5` |

#### caching_time

Time in minutes that results remain in the cache.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | 10 | `caching_time=10` |

#### posts_per_page

How many posts appear under the filter. Use -1 to show all.

Please note that high numbers of posts may impact the performance.

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

Which field to use for sorting.

| option | description |
| --- | --- |
| author |  |
| date |  |
| modified |  |
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

Whether to display a line that shows the total amount of posts. This message appears only if the number is greater than zero.

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0 or 1 | 0 | `display_amount=1` |

#### search

Filter by a search term

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `search=hello` |

#### tag

Filter by a tag slug.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `tag=my-tag` |

#### tag__and

Filter by a list of tag IDs. Combine them with “and”.

| accepted values | default | example shortcode |
| --- | --- | --- |
| comma-separated list of tag IDs | empty | `tag__and=12,45` |

#### tag__in

Filter by a list of tag IDs. Combine them with “or”. (“in” a set of tags)

| accepted values | default | example shortcode |
| --- | --- | --- |
| comma-separated list of tag IDs | empty | tag__in=12,45 |

#### tag__not_in

Filter by a list of tag IDs. Combine them with “nor”. (“not in” a set of tags)

| accepted values | default | example shortcode |
| --- | --- | --- |
| comma-separated list of tag IDs | empty | tag__not_in=12,45 |

#### tag_id

Filter by a tag ID.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | empty | `tag_id=10` |

#### tag_slug__and

Filter by a list of tag slugs. Combine them with “and”.

| accepted values | default | example shortcode |
| --- | --- | --- |
| comma-separated list of tag slugs | empty | tag_slug__and=my-tag,your-tag |

#### tag_slug__in

Filter by a list of tag slugs. Combine them with “or”. (“in” a set of tags)

| accepted values | default | example shortcode |
| --- | --- | --- |
| comma-separated list of tag slugs | empty | tag_slug__in=my-tag,your-tag |

#### template

HTML code that contains some placeholders. If you use only one template on your entire website, it is recommended to leave this parameter empty and edit the default template in the Front End settings.

In order to prevent that WordPress parses the HTML and adds malformed `<p>` tags, it is recommended to encode it before using it.

Possible placeholders are: {post_id}, {post_title}, {post_guid} (link to the post, {post_excerpt} (automatically generated), {post_date}, {post_author}, {image_src} or {image_src|image size} (followed by the dimensions – will create also width and height attributes), {image_alt}, {post_category}, {post_tags}, {custom_field:`<name>`}

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | see below | `template='JTNDZGl2JTIwY2x'` |

Default template:

```
class="tg-entry-header">
<img src="{image_src|100x100}" alt="{image_alt}" class="tg-entry-image" />
<h3 class="tg-entry-title"><a href="{post_guid}" rel="bookmark">{post_title}</a></h3>
Posted on {post_date} by {post_author}.
{post_category}</header>
<div class="tg-entry-content"><p>{post_excerpt}</p></div>
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

### Labels and Messages

#### message_nothing_found

Message to show if no posts were found.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | “Nothing found.” | `message_nothing_found=""` |

#### message_load_more

Label of the link to load more posts, if the pager is active.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | “Load more” | `message_load_more=""` |

#### message_go_back

Label of the link to go back, if the pager is active.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | “Go back” | `message_go_back=""` |

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

## Advanced Styling

You can set here classes to be referred to in CSS. You need some knowledge of CSS to use these options.

### div_class

Define a class for the `<div>`.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `div_class="change-pointer"` |

### article_class

Define a class for the enclosing `<article>`.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | tg-post | `article_class="make-it-dark"` |
