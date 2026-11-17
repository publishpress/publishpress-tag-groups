---
title: "How to Change the Length of the Post Excerpts"
order: 35
doc_category: publishpress-tag-groups
---

# How to Change the Length of the Post Excerpts

The excerpt in the post templates of the Toggle Post Filter, Dynamic Post Filter and Post List is taken from one of two places:

- The excerpt that you created with the post.
- If the post doesn’t have an excerpt, it is take from the beginning of the post content.

You can change the excerpt length manually with a line of code.

Add to the file functions.php of your child theme:

```
add_filter( 'excerpt_length', function() {return 20;});
```

Replace “20” by the required number of words.
