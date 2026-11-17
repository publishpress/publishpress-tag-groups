---
title: "Dynamic Post Filter – Gutenberg Block"
order: 28
doc_category: publishpress-tag-groups
---

# Dynamic Post Filter – Gutenberg Block

Table of Contents

- [Tags and Taxonomies](#0-toc-title)[Include taxonomy](#taxonomy)[Logic operator](#operator)
- [Groups](#groups)[Include groups](#include)[Initially open](#open)[Show groups only](#groups_only)[Hide the close buttons](#hide_close_buttons)
- [Posts](#posts)[Posts per page](#posts_per_page)[Use a pager](#pager)[Caching time](#caching_time)[Order posts by](#orderby)[Sort order](#order)[Display how many posts were found](#display_amount)
- [Post template](#template)[Customize the template](#16-toc-title)[Image sizes](#17-toc-title)[Transition](#transition)[Static taxonomy](#static_taxonomy)[Static terms](#static_terms)[Labels and Messages](#labels_and_messages)[Placeholder for tag field](#placeholder)[Placeholder for posts field](#posts_placeholder)[Label for default option](#default_option_label)[Header above groups](#groups_header)[Alert to use only existing tags](#message_only_existing)[Alert to use only tags from correct group](#message_same_group)[Message if nothing was found](#message_nothing_found)[Link to load more posts](#message_load_more)[Link to load previous posts](#message_go_back)[Message for one post in total](#message_amount_singular)[Message for {count} posts in total](#message_amount_plural)
- [Other](#33-toc-title)[Remember selected filter and post](#persistent_filter)[Advanced Styling](#advanced_styling)[Use styling for selector.](#sumoselect)[div class](#div_class)[div id](#div_id)

The Dynamic Post Filter feature allows visitors to select groups and tags and immediately see posts that match their selection.

This guide will show you how to use the Gutenberg block that is available with the Dynamic Post Filter feature.

**Note:**This block can only be used **once** on a page (including widgets), and it cannot be used together with the shortcode or block of the Toggle Post Filter.

![](https://docs-media.publishpress.com/publishpress-tag-groups/dynamic-post-filter-04df9c7052.webp)

## Tags and Taxonomies [#](#0-toc-title)

#### Include taxonomy [#](#taxonomy)

Restrict the tags only to these taxonomies. Available are only taxonomies that have been activated in the Settings.

#### Logic operator [#](#operator)

The operator determines how selected tags and groups should be logically connected when searching for matching posts. Possible values are:

- **any tag matches** (“IN”/”OR”): At least one of the selected tags must be among the post tags for a post to be displayed.
- **all tags match** (“AND”): All selected tags across all groups must be among the post tags of the same group.
- **at least one tag matches from each group** (“IN AND”): At least one selected tag of each group must be among the post tags of the same group.
- **exact match of all selected tags** (“EXACT”): The selection of tags must be identical to the post tags of that same group, not more and not less((If you upgrade from a version before 1.39.0, go to the settings, Troubleshooting and run the maintenance.)) This option is not available for the option where you show groups only.

Groups where no tags are selected won’t be considered. If you use AND or IN AND on five groups and a visitors selects only tags from the first two groups, they may already see posts. The unused other three groups don’t count in.

If you need more precise control with different operators per group, you need to transform the block into a shortcode and then use the parameter “operator”.

## Groups [#](#groups)

#### Include groups [#](#include)

Restrict the posts to those having tags in these groups.

#### Initially open [#](#open)

[Tag groups](https://publishpress.com/links/tag-groups) that are initially open.

#### Show groups only [#](#groups_only)

Skip the tag selection and display all products that match the selected groups.

#### Hide the close buttons [#](#hide_close_buttons)

Disables the possibility to remove the input fields of groups. You can use that option if you want to show a fixed set of groups that cannot be altered by visitors.

## Posts [#](#posts)

#### Posts per page [#](#posts_per_page)

How many posts appear under the filter.

#### Use a pager [#](#pager)

If the pager is enabled then visitors can load the next or previous page.

#### Caching time [#](#caching_time)

Time in minutes that results remain in the cache on the server.

#### Order posts by [#](#orderby)

Which field to use for sorting.

#### Sort order [#](#order)

Whether to sort the posts in ascending or descending order. Ascending order also means from oldest to newest.

#### Display how many posts were found [#](#display_amount)

This line will appear in a separate field that you create with the messages block. The message itself can be customized in the “Labels and Messages” menu.

From version 1.28.0: You can optionally display the message in an overlay notification that remains visible for three seconds. This is particularly useful for mobile devices, because your visitors will receive a feedback of their search even if the message and posts blocks are outside of their screen (viewport). You can select this option and omit the message block to show only overlay notifications.

When you use a pager, the number will be the total amount. Otherwise it’ll be the amount of visible posts, that is maximum what you choose for **Posts per Page**.

## Post template [#](#template)

The post template defines how each post is displayed. The default template shows the featured images on the left, the post title and meta on the right and the post excerpt below.

Starting from version 1.30.0, you can choose among popular templates.

#### Customize the template [#](#16-toc-title)

Customize the post template how you need it. In the text area you see the HTML of the last selected post template. Please be aware that when you return to one of the preset templates, your custom changes will be lost.

Possible placeholders are: {post_id}, {post_title}, {permalink}, {post_excerpt} (automatically generated), {post_excerpt_html} (preserves most common html tags), {post_date}, {post_author}, {image_src} or {image_src|image size}, {image_alt}, {post_category}, {post_tags}, {custom_field:<name>}((A alue from the post meta <name>.

Please note that the HTML will be sanitized, i.e. some tags and attributes might be removed. In that case try to use a class instead.

#### Image sizes [#](#17-toc-title)

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

#### Transition [#](#transition)

Transition between loading the list of posts.

#### Static taxonomy [#](#static_taxonomy)

Optionally filter also by terms of an additional taxonomy (not necessarily one of the taxonomies used for [tag groups](https://publishpress.com/links/tag-groups)). Used together with static terms.

#### Static terms [#](#static_terms)

Optionally filter also by terms of an additional taxonomy (not necessarily one of the taxonomies used for tag groups). Used together with static taxonomy.

### Labels and Messages [#](#labels_and_messages)

#### Placeholder for tag field [#](#placeholder)

Placeholder message in the tag input field. It disappears as soon as you enter tags.

#### Placeholder for posts field [#](#posts_placeholder)

Placeholder message for the list of posts.

#### Label for default option [#](#default_option_label)

What the pull-down menu should show if no group is selected.

#### Header above groups [#](#groups_header)

Header above the list of selected groups.

#### Alert to use only existing tags [#](#message_only_existing)

Error message if user tries to enter a tag that doesn’t exist.

#### Alert to use only tags from correct group [#](#message_same_group)

Error message if user tries to enter a tag from another group.

#### Message if nothing was found [#](#message_nothing_found)

Message to show if no posts were found. This will also be used for the overlay notification.

#### Link to load more posts [#](#message_load_more)

Label of the link to load more posts, if the pager is active.

#### Link to load previous posts [#](#message_go_back)

Label of the link to go back, if the pager is active.

#### Message for one post in total [#](#message_amount_singular)

Message if one post was found, if “Display the total amount of posts” is active.

#### Message for {count} posts in total [#](#message_amount_plural)

Message if many posts were found, if “Display the total amount of posts” is active. May contain the placeholder {count}

## Other [#](#33-toc-title)

#### Remember selected filter and post [#](#persistent_filter)

The Dynamic Post Filter can optionally remember the selection of groups and tags so that your visitors who clicked on an article and then return to this page will see the same list of articles again. After loading, the page will scroll to the group selection menu. (Otherwise the browser might go to a random post, depending on the loading speed.)

The data is saved in a browser cookie. If you use several Dynamic Post Filters on your site, you should give each a different div_id so that each will have its own cookie.

The value is the time in minutes. Zero means off.

### Advanced Styling [#](#advanced_styling)

#### Use styling for selector. [#](#sumoselect)

Changes the default select menu to a more stylish one.

You can set here classes to be referred to in CSS. You need some knowledge of CSS to use these options.

#### div class [#](#div_class)

Set a class for the <div>.

#### div id [#](#div_id)

Set an ID for the enclosing <div>.
