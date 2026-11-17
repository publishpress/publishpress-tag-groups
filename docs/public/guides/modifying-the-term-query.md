---
title: "Modifying the Term Query"
order: 59
doc_category: publishpress-tag-groups
---

# Modifying the Term Query

This feature is still experimental.

In some cases you may want to modify the term query so that it returns only tags that belong to certain [tag groups](https://publishpress.com/links/tag-groups). You can use the filter **tag_groups_modify_term_query_args** to modify the array of arguments that you pass to [WP_Term_Query](https://developer.wordpress.org/reference/classes/wp_term_query/) (or [get_terms](https://developer.wordpress.org/reference/functions/get_terms/)).

It accepts up to three arguments:

- The array of arguments that you will use for the term query. The element with the key “meta_query” will be overwritten.
- An array or a comma-separated list of group IDs.
- The logic relation between the group IDs (default: OR), alternatively AND.

In the following example you show only tags that belong to the groups with the IDs 1 or 5:

```
// define your query parameters as usual
$args = array( 'taxonomy' => 'post_tag' );
// apply the filter on them, with additional filter parameters
$args = apply_filters( 'tag_groups_modify_term_query_args', $args, array(1,5), 'OR');
// use the resulting arguments in your query
$tags = get_terms( $args );
```
