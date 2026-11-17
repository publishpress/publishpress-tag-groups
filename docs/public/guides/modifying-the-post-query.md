---
title: "Modifying the Post Query"
order: 58
doc_category: publishpress-tag-groups
---

# Modifying the Post Query

Table of Contents

- [1st Method: Filter the arguments](#0-toc-title)[Example](#1-toc-title)
- [2nd Method: Filter the query](#2-toc-title)[Example](#3-toc-title)

This feature is still experimental.

In some cases you may want to modify the post query so that it returns only posts that have tags which belong to certain [tag groups](https://publishpress.com/links/tag-groups). You can use the filters **tag_groups_modify_post_query_args** to modify the post arguments that you pass to [WP_Query](https://developer.wordpress.org/reference/classes/wp_query/) or [get_posts](https://developer.wordpress.org/reference/functions/get_posts/) and **tag_groups_modify_post_query** to modify the post query that you pass to [WP_Query](https://developer.wordpress.org/reference/classes/wp_query/).

## 1st Method: Filter the arguments [#](#0-toc-title)

The filter **tag_groups_modify_post_query_args** accepts up to three arguments:

- The array of arguments for the post query. The element with the key “meta_query” will be overwritten.
- An array or a comma-separated list of group IDs.
- The logic relation between the group IDs (default: OR), alternatively AND.

### Example [#](#1-toc-title)

In the following example you show only posts with tags that belong to the groups with the IDs 1 or 5:

```
// create the array of query parameters as usual
$args = array( 'author' => 12 );
// apply the filter on it, with additional filter parameters
$args = apply_filters( 'tag_groups_modify_post_query_args', $args, array(1,5), 'OR');
// use the arguments in your query
$posts = get_posts( $args );
```

## 2nd Method: Filter the query [#](#2-toc-title)

The filter **tag_groups_modify_post_query** accepts up to three arguments:

- The WP Query object. The element with the key “meta_query” will be overwritten.
- An array or a comma-separated list of group IDs.
- The logic relation between the group IDs (default: OR), alternatively AND.

### Example [#](#3-toc-title)

In the following example you show only posts with tags that belong to the groups with the IDs 1 or 5:

```
// instantiate the query object with your query parameters
$post_query = new WP_Query(array( 'author' => 12 ));
// apply the filter on it, with additional filter parameters
$post_query = apply_filters( 'tag_groups_modify_post_query', $post_query, array(1,5), 'OR');
// use the query
```
