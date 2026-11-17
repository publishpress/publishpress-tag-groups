---
title: "Tag Groups in the WordPress Database"
order: 80
doc_category: publishpress-tag-groups
---

# Tag Groups in the WordPress Database

This guide will introduce you how data for the Tags Groups plugin is stored in the WordPress database.

On the main “Tag Group Administration” screen you will be able to see the Group ID for each Tag Group. In this example, “My Group” has an ID of 1 and there are 3 tags assigned to this group.

![Tag Group Administration](https://docs-media.publishpress.com/publishpress-tag-groups/tag-groups-id-cac93d8591.webp)

*[tag groups](https://publishpress.com/links/tag-groups) id*

In this screenshow below, I’ve used the [Reveal IDs plugin](https://taxopress.com/ids-tags-posts-categories/) to show the IDs of the tags: 54, 55, and 56.

![Term IDs for Tag Groups](https://docs-media.publishpress.com/publishpress-tag-groups/tag-ids-64aad0c368.webp)

If you go to the _termmeta table in your database, you will find how the terms are connected to the [Tag Groups](https://publishpress.com/links/tag-groups). The term_ids below are 54, 55, and 56. For each term, there an array called _cm_term_group_array and the Group ID is stored as 1.

![termmeta table](https://docs-media.publishpress.com/publishpress-tag-groups/tags-term-meta-89c709ced7.webp)

*tags term meta*

The labels for each group are stored together in the _options table.
