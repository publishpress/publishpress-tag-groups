---
title: "Running Maintenance"
order: 65
doc_category: publishpress-tag-groups
---

# Running Maintenance

While [Tag Groups](https://publishpress.com/links/tag-groups) Pro tries to keep track of all changes to posts, tags, groups or taxonomies, it may occasionally happen that some changes happen unnoticed. Also, activating posts or taxonomies or manipulating with languages on multilingual sites can lead to inconsistencies.

In that case, you can run several maintenance scripts that execute the following tasks:

- Remove from post meta non-existing groups, non-existing terms, terms in wrong groups and groups without tags.
- Remove from term meta non-existing groups.
- Empty the transient cache and schedules the rebuilding of post counts.

You find the “Maintenance” button in the “Troubleshooting” section of the Settings, under the “First Aid” tab.

Similarly you can run the [Migration procedures](https://taxopress.com/docs/migrating-your-tags-and-posts/) that give posts and tags the full capacity to deal with groups, particularly filtering. Usually the migration happens automatically, but in some cases it can miss out on some posts.

**It is safe to press all buttons under “First Aid”.** Processing may take a few minutes and rendering the tag clouds may be slower than usual while the cache is being rebuilt.
