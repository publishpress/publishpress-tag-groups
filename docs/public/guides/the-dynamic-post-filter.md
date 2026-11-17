---
title: "Dynamic Post Filter – Overview"
order: 90
doc_category: publishpress-tag-groups
---

# Dynamic Post Filter – Overview

Table of Contents

- [Displaying Posts Filtered by Groups and Tags](#0-toc-title)
- [Performance](#1-toc-title)
- [Alternatives](#2-toc-title)
- [Common Issues and Solutions](#3-toc-title)

This is a feature of the [Tag Groups](https://publishpress.com/links/tag-groups) Pro plan. The Dynamic Post Filter feature allows visitors to select groups and tags and immediately see posts that match their selection.

This shortcode or block can only be used once on a page (including widgets), and it cannot be used together with the shortcode or block of the Toggle Post Filter.

## Displaying Posts Filtered by Groups and Tags [#](#0-toc-title)

Imagine you write posts about your travels and you tag these posts with the names of the countries. Now, you may want to assemble all those country tags under a tag group called “Countries”. Using the Dynamic Group Filter, your visitors can now open the group “Countries” and then select tags of your countries. The list of posts refreshes automatically and displays all posts that match one of the selected country tags.

![](https://docs-media.publishpress.com/publishpress-tag-groups/dynamic-post-filter-3-e8d06b4b2e.webp)

*Example of the 2-layered Dynamic Post Filter: First select the group, then the tags.*

## Performance [#](#1-toc-title)

Please note that it is unavoidable that complex queries take some time to process in the database. That is particularly the case if many tags are selected in multiple groups. It is therefore highly recommended to use the parameter caching_time (in minutes) to speed up queries that have appeared recently before.

## Alternatives [#](#2-toc-title)

If you don’t want your visitors to enter the tags into the tag field, but select them with a toggle (on-off) switch, you could take a look at the Toggle Post Filter.

If you don’t need the interactivity of the Dynamic Post Filter, you may want to check out the Post List.

You can also use one of the tag clouds. They are highly customizable so that you can, for example, display all tags in the same size or prepend to each the same character. Using the tag clouds, however, visitors don’t see immediately the list of matching posts. Instead, a click on the tag takes them to a “tag page”, where all posts are listed that have this tag.

## Common Issues and Solutions [#](#3-toc-title)

If no tags or no posts appear, please make sure that

- you have selected the correct taxonomy. If you use something else than the default post_tag, use the *taxonomy* parameter.
- you have assigned your tags to groups and used these tags in public posts.
