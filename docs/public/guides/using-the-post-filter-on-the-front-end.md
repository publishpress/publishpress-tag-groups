---
title: "Using the Post Filter on Frontend URLs"
order: 108
doc_category: publishpress-tag-groups
---

# Using the Post Filter on Frontend URLs

**Table of Contents**

- [Filtering by tag group and tag](#filtering-by-tag-group-and-tag)
- [Filtering by tag group and category](#filtering-by-tag-group-and-category)
- [Other notes](#other-notes)

On default WordPress pages that show lists of posts you can filter that list by term group, thanks to the [Tag Groups](https://publishpress.com/links/tag-groups) plugin.

To add filters to WordPress URLs, append the query parameter “term_group” with the group’s ID to the URL of that page. Here’s an example:

```
https://www.example.com/?term_group=3
```

## Filtering by tag group and tag

You can also filter by a tag (term) and show only posts that use this tag in a particular group. In that case use “term_group” and “term_id”. Example:

```
https://www.example.com/?term_id=195&term_group=3
```

If you want to filter by tag *and* group, you alway have to use both parameters “term_group” and “term_id”. It is not sufficient to use only “term_group” on a tag archive page (…/tag/my-tag/?term_group=3 won’t work).

## Filtering by tag group and category

Simply use a category page and add the parameter term_group with a group’s ID. Example for WooCommerce categories:

```
https://www.ecample.com/product-category/accessories/?term_group=3
```

## Other notes

- This filter won’t be effective on custom lists created by other themes or plugins or when they modify the post query. [Tag Groups](https://publishpress.com/links/tag-groups) makes use of the “pre_get_posts” action hook of the default WordPress database query. If any other plugin or theme removes it, the filter won’t work. It is therefore recommended to test the filter in your particular environment.
- Please note that in URLs the first parameter is always appended with a “?”, while all following parameters use a “&”.
- If you need to filter just by a tag without a group, you simply use the standard Tag page that is part of every theme.
- If the filtered list of posts is unexpectedly empty, you can try the tools on the Settings page under the Support tab.
