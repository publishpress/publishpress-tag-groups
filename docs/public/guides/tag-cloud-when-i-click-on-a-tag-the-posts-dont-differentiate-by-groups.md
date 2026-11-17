---
title: "Tag Cloud: When I Click on a Tag, the Posts Don’t Differentiate by Groups"
order: 79
doc_category: publishpress-tag-groups
---

# Tag Cloud: When I Click on a Tag, the Posts Don’t Differentiate by Groups

**Table of Contents**

- [Shortcode](#shortcode)
- [Gutenberg Block](#gutenberg-block)

If you use the same tag in *more than one* group, clicking on the *same* tag in a tag cloud should show *different* posts, depending on the group where you clicked. That means that the list of posts must be filtered by group.

Here is an example: Imagine you use the tag “Charlie Chaplin” under two groups: “Actors” and “Directors”. By default, clicking on the tag “Charlie Chaplin” will show all movies where he was involved as actor or as director. In some cases, however, you want to differentiate between acting and directing.

For this case you need to turn on an additional filter.

If all your tags show the correct posts, you won’t have to use this feature.

## Shortcode

Add an additional parameter to the shortcode:

```
add_premium_filter=1
```

## Gutenberg Block

Turning on the option **“Add filter to tags for multiple groups.”** in the Gutenberg block.
