---
title: "How to Improve the Speed of Tag Groups"
order: 52
doc_category: publishpress-tag-groups
---

# How to Improve the Speed of Tag Groups

By default in WordPress, terms and posts are processed every time a page is loaded. [Tag Groups](https://publishpress.com/links/tag-groups) Pro avoids this problem by using caching on various levels. That means that once it has finished processing, the result is stored for the next time so that it doesn’t need to do all the work again.

Some forms of caching are always active. In order to benefit from the maximum speed increase, please make sure that also object caching is enabled in the [Tag Groups](https://publishpress.com/links/tag-groups) settings.

![](https://docs-media.publishpress.com/publishpress-tag-groups/settings-cache-1-b934ed9f7b.webp)

The speed improvement should be noticeable starting from the *second* time you load the page after saving the settings.

By default, we empty the cache automatically when you make changes that require to rebuild the cache. If you prefer to do it manually after a series of changes, go to the Tag Groups settings, Front End, Cache and deactivate “Automatically clear cache when needed”. Now you will see a message whenever it is recommended to clear the object cache.
