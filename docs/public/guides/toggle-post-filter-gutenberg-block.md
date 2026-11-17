---
title: "Toggle Post Filter, Menu – Gutenberg Blocks"
order: 96
doc_category: publishpress-tag-groups
---

# Toggle Post Filter, Menu – Gutenberg Blocks

Table of Contents

- [Available Blocks](#0-toc-title)
- [Block Options - Menu](#tags_and_terms)
- [Tags and Taxonomies](#2-toc-title)[Include taxonomies](#taxonomy)[Include tags](#include_terms)[Exclude tags](#exclude_terms)[Logic operator](#operator)[Order tags by](#term_orderby)[Tag order](#term_order)[Hide unused tags](#hide_empty)[Preset tags](#preset_tags)[Static taxonomy](#static_taxonomy)[Static terms](#static_terms)
- [Groups](#groups)[Include groups](#include)[Groups where only one tag can be selected](#one_only_groups)
- [Text](#16-toc-title)[Text search](#text_search)
- [Caching and Performance](#18-toc-title)[Caching time](#caching_time)[Remember selected filter and post (persistent filter)](#persistent_filter)[Timeout before search](#timeout)[Labels and Messages](#labels_and_messages)[Title for text search](#title_text_search)[Placeholder for search field](#placeholder_text_search)
- [Layout and Theme](#25-toc-title)[Menu layout](#layout)[Icon](#icon)[Slider width](#slider_width)[Tag color](#tag_color)[Selected tag color](#selected_tag_color)[Theme](#theme)[Hide toggles in an accordion](#accordion)
- [Advanced Styling](#advanced_styling)[div_class](#div_class)[div_id](#div_id)

The Toggle Post Filter is a frontend feature in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin. Visitors can select tags and see a list of posts that match their search criteria.

This guide will show you how to use the Gutenberg blocks that are available with the Toggle Post Filter feature.

**Note:**The Toggle Post Filter can only be used once on a page (including widgets), and it cannot be used together with the shortcode or block of the Dynamic Post Filter.

## Available Blocks [#](#0-toc-title)

This feature consists of several blocks that you can place at different locations of the page, including widgets. The main part is the menu block and here you supply almost all parameters.

- [Toggle Post Filter – Menu](https://taxopress.com/docs/toggle-post-filter-gutenberg-block/) (required)
- [Toggle Post Filter – Posts](https://taxopress.com/docs/toggle-post-filter-body-gutenberg-block/) (required)
- [Toggle Post Filter – Text Search](https://taxopress.com/docs/toggle-post-filter-text-search-gutenberg-block/) (optional)
- [Toggle Post Filter – Slider Button](https://taxopress.com/docs/toggle-post-filter-slider-button-gutenberg-block/) (optional, only for the slider layouts)
- [Toggle Post Filter – Order menu](https://taxopress.com/docs/toggle-post-filter-order-menu-gutenberg-block/) (optional)
- Toggle Post Filter – Reset Button (optional)
- Toggle Post Filter – Message Field (optional)

![](https://docs-media.publishpress.com/publishpress-tag-groups/toggle-post-filter-blocks-e7a6f903e2.webp)

Please make sure you don’t use the “legacy” menu block. This block is still functional but its options have been migrated to the posts block.

## Block Options – Menu [#](#tags_and_terms)

The following options appear in the editor side panel after you selected a block. Some of them appear only when you activated the corresponding function. For example, you can set the title of the search field only after you activated text search.

You find post-related options in the [post (body) block](https://taxopress.com/docs/toggle-post-filter-body-gutenberg-block/).

![](https://docs-media.publishpress.com/publishpress-tag-groups/post-filter-menu-block-c82724c568.webp)

## Tags and Taxonomies [#](#2-toc-title)

#### Include taxonomies [#](#taxonomy)

Use only tags from selected taxonomies (“tag types”). Available are only taxonomies that have been enabled in the Settings. If you leave this option empty, we use all enabled taxonomies.

#### Include tags [#](#include_terms)

Restricts the tags that should be used. If you leave this option empty, we use all tags of selected groups and taxonomies.

#### Exclude tags [#](#exclude_terms)

Restricts the tags that should be excluded.

#### Logic operator [#](#operator)

The operator determines how selected tags and groups should be logically connected when searching for matching posts. Possible values are:

- **any tag matches** (“IN”/”OR”): At least one of the selected tags must be among the post tags for a post to be displayed.
- **all tags match** (“AND”): All selected tags across all groups must be among the post tags of the same group.
- **at least one tag matches from each group** (“IN AND”): At least one selected tag of each group must be among the post tags of the same group.
- **exact match of all selected tags** (“EXACT”): The selection of tags must be identical to the post tags of that same group, not more and not less. (Note: If you upgraded from a version before 1.39.0, go to the settings, Troubleshooting and run the maintenance.)

Groups where no tags are selected won’t be considered. If you use AND or IN AND on five groups and a visitors selects only tags from the first two groups, they may already see posts. The unused other three groups don’t count in.

If you need more precise control with different operators per group, you need to transform the block into a shortcode and then use the parameter operator.

#### Order tags by [#](#term_orderby)

Lets you determine the order of the tags (terms).

#### Tag order [#](#term_order)

Whether to sort the tags in ascending or descending order.

#### Hide unused tags [#](#hide_empty)

Hide tags that are not being used in (public) posts.

#### Preset tags [#](#preset_tags)

Lets you initialize the filter with a selected toggle set to on.

#### Static taxonomy [#](#static_taxonomy)

Optionally filter also by terms of an additional taxonomy (not necessarily one of the taxonomies used for [tag groups](https://publishpress.com/links/tag-groups)). You can, for example, show only posts that belong to a particular category.

#### Static terms [#](#static_terms)

See **static taxonomy** above. Here you select the terms (tags, categories).

## Groups [#](#groups)

#### Include groups [#](#include)

Select tag groups that will be shown to visitors. By default all your groups will appear.

#### Groups where only one tag can be selected [#](#one_only_groups)

Please note that more tags might still be selected if this selection is carried over by the persistent filter setting from another page.

## Text [#](#16-toc-title)

#### Text search [#](#text_search)

Adds a text search field at the beginning of the menu. The entered word can be prepended with a minus so that it will be excluded. The logic connection with other search criteria is always AND, which means that the text search can narrow down the list of posts that was determined by the other search criteria.

Options:

- **off:** no text field appears
- **on enter:** Search begins when you press ‘enter’.
- **on enter or timed:** Search begins when you press ‘enter’ or when you pause typing.

## Caching and Performance [#](#18-toc-title)

#### Caching time [#](#caching_time)

Time in minutes that the selection of posts that match the search criteria should remain available for faster access on the server.

#### Remember selected filter and post (persistent filter) [#](#persistent_filter)

The Toggle Post Filter can optionally remember the selection of groups and tags so that your visitors who clicked on an article and then return to this page will see the same list of articles again. If no post has been clicked, the page will scroll to the group selection menu.

This option requires that visitors accept cookies in their browsers. If you are using this filter more than once on your site, you should use different values for div_id so that each filter uses a different cookie.

#### Timeout before search [#](#timeout)

We try to avoid search requests each time a visitor toggles a button or types a letter in the text field because the search should be launched only when the visitor is done selecting tags and typing text. We achieve this through a timeout of inactivity before the request is sent to the server. This timeout is by default 1000ms (= 1 second) and we dynamically increase it (plus up to 2 seconds) when we assume that a visitor needs more time selecting menu options. We reset this increased timeout when the posts load.

You can set here the (minimum) timeout to find a good balance between user experience (with a responsive search) and database load. Factors to be considered are the habits and needs of typical visitors, the number of visitors who simultaneously search, the size of your database (posts and tags) and the power of your database engine.

### Labels and Messages [#](#labels_and_messages)

#### Title for text search [#](#title_text_search)

Title above the text search field.

#### Placeholder for search field [#](#placeholder_text_search)

Placeholder message for the text search field.

## Layout and Theme [#](#25-toc-title)

#### Menu layout [#](#layout)

Layout of the **menu part**, containing the toggles.

- **classic:** The menu items appear vertically stacked in a narrow container. Posts display on the right side. If you select “classic” for the menu, also the body part with the posts should use the “classic” layout.
- **buttons:** Like the classic layout, but instead of toggles we use buttons. Optionally show an icon on active buttons.
- **classic with tags:** The menu is arranged like the “classic” layout. Tags appear in a tag shape and line up in rows (like in tag clouds).
- **wide:** Groups are lined up horizontally. Posts should display below the menu. If you select “wide”, the post layout can be any of wide, boxed, columns, columns-avoid-break and masonry.
- **wide with buttons:** Like the “wide” layout, but instead of toggles we use buttons. Optionally show an icon on active buttons.
- **wide with tags:** The menu is arranged like the wide layout. Tags appear in a tag shape and line up in rows (like in tag clouds).
- **slider left:** The menu slides in from the left side, covering the contents of the page. The slider remains open while you click on the buttons so that you scroll through the search results while the menu remains open. Consider composing the page in a way that the slider doesn’t cover the posts.  
You close the menu by clicking on the X or outside the slider. For the slider you will also need the button to open the slider. The slider uses buttons with optional icons.
- **slider left with tags:** The menu is arranged like the “slider left” layout. Tags appear in a tag shape and line up in rows (like in tag clouds).
- **slider right:** The same as the left slider, opening from the right.
- **slider right with tags:** The menu is arranged like the “slider right” layout. Tags appear in a tag shape and line up in rows (like in tag clouds).

#### Icon [#](#icon)

Place an icon on active buttons. (Not available with toggles.) Some common icons are available as buttons. For other icons you need to convert the block to a shortcode.

#### Slider width [#](#slider_width)

Set the width of the slider menu in pixels. Only available with the “slider left with tags” and the “slider right with tags” menu layouts.((In the other slider layouts the width is fixed.))

#### Tag color [#](#tag_color)

Set the background color of the tags. Only available with the “slider left with tags” and the “slider right with tags” menu layouts.

#### Selected tag color [#](#selected_tag_color)

Set the background color of the tags that are toggled *on* in the filter. Only available with the “classic with tags”, “wide with tags”, “slider left with tags” and the “slider right with tags” menu layouts.

#### Theme [#](#theme)

The plugin comes with two default themes, light and dark.

#### Hide toggles in an accordion [#](#accordion)

Display each group as an accordion where toggles are hidden under the group label. If you use the option “Remember selected filter and post”, all groups that contain active toggles will be open.

Please note that also hidden toggle switches are effective.

## Advanced Styling [#](#advanced_styling)

You can set here classes to be referred to in CSS. You need some knowledge of CSS to create your own theme.(

#### div_class [#](#div_class)

Define a class for the <div>.

#### div_id [#](#div_id)

Define an ID for the enclosing <div>.
