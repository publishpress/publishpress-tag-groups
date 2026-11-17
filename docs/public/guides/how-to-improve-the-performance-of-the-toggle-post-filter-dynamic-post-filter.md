---
title: "How to Improve the Performance of the Toggle Post Filter/Dynamic Post Filter"
order: 41
doc_category: publishpress-tag-groups
---

# How to Improve the Performance of the Toggle Post Filter/Dynamic Post Filter

**Table of Contents**

- [How to add an index to the postmeta table](#how-to-add-an-index-to-the-postmeta-table)
- [Alternative index structure](#alternative-index-structure)

When searching for matching posts in the post filters we make use of the WP_Query class. Usually it performs well, but for larger amounts of posts and complex searches the database may run into memory or performance issues.

To improve performance you can add an index to the postmeta table of your database. This is a common way how WordPress site admins manage to increase performance.

Since this solution changes the structure of the WordPress database, we don’t apply it through the plugin. That means that you need a way how to execute queries on your database – for example with phpMyAdmin.

**These queries are for advanced users. Use them at your own risk!**

## How to add an index to the postmeta table

Before you start, **create a complete backup** of your database that would allow you to restore it if something goes wrong.

Then go to the database that corresponds to your WordPress installation and execute the query below.

Make sure to **replace “wp_postmeta”** with the table name that holds the postmeta. It has a name like abc_postmeta. On multisite installations there may also additionally be abc_1_postmeta, abc_2_postmeta and so on.

```
CREATE INDEX id_key_val ON wp_postmeta (post_id,meta_key,meta_value(255));
```

You should be able to see the result when you view the structure of the postmeta table.

## Alternative index structure

Alternatively you can try the following query. Please be aware that this one does not only add an index but also *modifies the existing indexes* that may be required somewhere else.

```
ALTER TABLE wp_postmeta
DROP PRIMARY KEY,
DROP INDEX post_id,
ADD PRIMARY KEY(post_id, meta_key, meta_id),
ADD INDEX(meta_id);
```
