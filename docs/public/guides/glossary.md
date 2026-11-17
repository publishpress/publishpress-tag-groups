---
title: "Glossary of Useful Words for Tag Groups"
order: 33
doc_category: publishpress-tag-groups
---

# Glossary of Useful Words for Tag Groups

Table of Contents

- [Meta Box](#0-toc-title)
- [Post Type](#post_type)
- [Shortcode](#2-toc-title)
- [Shuffle Box](#3-toc-title)
- [Tag Group](#4-toc-title)
- [Taxonomy](#5-toc-title)
- [Term](#6-toc-title)
- [Block](#7-toc-title)
- [Gutenberg](#8-toc-title)

In this Glossary you find some explanations of relevant terms that are either part of the default WordPress ecosystem or specific to the [Tag Groups](https://publishpress.com/links/tag-groups) plugin.

## Meta Box [#](#0-toc-title)

A meta box is an additional container on the post or page edit screen where you can enter supplemental information or select among options. The information inside the meta box is usually saved together with the post or page.

In the context of [Tag Groups](https://publishpress.com/links/tag-groups) Pro, the meta box holds a tool where you can filter tags by groups and add them to posts. It also includes a button to add all tags of a group to the post.

The Meta Box of Tag Groups Pro is by default off and needs to be activated in the settings. If you still don’t see the expected meta box, you could open the “Screen Options” on the top and check if that particular meta box is enabled.

Meta boxes can be moved around and collapsed.

## Post Type [#](#post_type)

The post type defines whether a piece of content is a post, a page, an attachment, a product, an event, or other built-in or custom types. Some of the built-in post types are used only internally and not directly visible to users.

Post types can be associated with one or more **taxonomies**, such as tags and categories.

## Shortcode [#](#2-toc-title)

A [shortcode](https://codex.wordpress.org/Shortcode) is a code wrapped in square brackets. It enables you to insert content into posts, pages and text widgets that is not part of the default WordPress installation. Shortcodes are therefore mostly provided by plugins and themes and they work only as long as those are active.

Shortcodes start with a pre-defined keyword, optionally followed by one or more parameters. You write and edit the shortcode in the post or page editor. On the front end, however, it will show as a tag cloud, an image, a table, a widget or anything else, depending on the keyword.

Parameters are formatted as *name=value*. These name-value pairs are separated from each other by spaces. That means that if you want to include a space in a value, the entire value must be wrapped in (plain, not formatted) quotes. Some characters that have a special meaning in HTML code cannot be used in shortcodes.

Shortcodes will gradually become obsolete as Gutenberg blocks will take their places. Disadvantages of shortcodes are problems with nested codes, visible codes on the front end if the corresponding plugin is disabled, and frequent malfunctions because users unintentionally copied over invisible formatting.

## Shuffle Box [#](#3-toc-title)

Shuffle Box is a name we use for one of the Pro plugin’s tag clouds. This tag cloud allows users to filter the tags by tag group or by parts of their names. The filtering appears in an animation by re-shuffling the tags – hence the name.

## Tag Group [#](#4-toc-title)

Tag groups are not available with the standard WordPress installation. They can be activated for all non-hierarchical taxonomies, but some features will not work with hidden or custom taxonomies. The plugin allows you to activate also hierarchical taxonomies, this, however, is an experimental feature that you can play with.

Tag Groups are ordered and have names, and they can contain tags. Tags remain non-hierarchical, but they can be sorted into one (or more, with the Pro plugin) groups that therefore form a parent level.

## Taxonomy [#](#5-toc-title)

A [taxonomy](https://codex.wordpress.org/Taxonomies) is basically a *tag type*. Taxonomies help organize posts. Best-known taxonomies are post tags and categories.

The most important distinction is between *hierarchical* and *non-hierarchical* taxonomies. Hierarchical taxonomies, such as categories, can be organized in a tree structure where each category can have a parent, even on multiple levels.

Plugins and themes can create custom taxonomies. For example, the plugin WooCommerce creates *product tags* and *product categories*. Custom taxonomies are only available while the respective plugin is active.

All taxonomies are connected to specific **post types**. Post tags and post categories, for example, are connected to posts, and product tags and product categories are connected to products.

Taxonomies are (out of the box) only available for posts and post-like post types – not pages.

Most taxonomy items are clickable where they appear on pages. Their links take you to an “archive” page where you see all posts of that corresponding post type that belong to that item that you clicked on. That way they work like post filters.

If you use the Tag Group Pro plugin with a different taxonomy than “post_tag”, you will need to add a parameter to the shortcode or block where you specify which taxonomy you want to use.

## Term [#](#6-toc-title)

Term is a general name for **taxonomy** items, such as tags or categories. In the context of this plugin, “term” is almost always synonymous with “tag”.

## Block [#](#7-toc-title)

Blocks are containers in the **Gutenberg** editor. They can be moved around, duplicated, saved with its content as a template, converted and more.

Your readers won’t see on the front end that you used blocks on the back end.

The default Gutenberg installation provides several useful standard blocks. Further blocks can be added by other plugins and themes. Older plugins don’t automatically have blocks. Blocks need to be created by the developers.

## Gutenberg [#](#8-toc-title)

Gutenberg is a new WordPress editor that will gradually be introduced as the default editor for posts and pages. Its main objective is to simplify the creation of layouts, to make the user interface more consistent and to integrate the same editor into many places, for example widget sidebars. Gutenberg aims to show on the editing screen the precise layout of the final page and it makes it easier to provide user-friendly context menus.

Gutenberg is based on **blocks** that hold the content.
