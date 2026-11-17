---
title: "Debugging a WordPress Ajax Error"
order: 25
doc_category: publishpress-tag-groups
---

# Debugging a WordPress Ajax Error

Table of Contents

- [Find Out The Cause](#0-toc-title)[Check the browser console](#1-toc-title)[Search for the breaking point](#2-toc-title)[Enable debugging](#3-toc-title)
- [Fix The Problem](#4-toc-title)

You probably arrived here because you came across an error message in the diagnostic tool.

Ajax is required for several functions in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin, including the group administration page. In contrast to normal web pages, the communication via Ajax can break when your WordPress site displays a harmless notice that would otherwise disappear in the HTML code.

## Find Out The Cause [#](#0-toc-title)

Any plugin or theme installed on your web site can cause Ajax to break.

There are several things that you can try. Some of them just require you to be the site administrator. For others you need access to the WordPress files.

Go to the “[Tag Groups](https://publishpress.com/links/tag-groups)“, then “Tools”, then “System Information”.

![](https://docs-media.publishpress.com/publishpress-tag-groups/ajax-test-e2fc1491ac.webp)

If you click on the button “Show the response”m you may see some text that comes before the `{"data":"...` part. Try to figure out, which theme or plugin has caused these messages. The plugin’s (or theme’s) name is often somewhere hidden in the path – look at the piece directly after “/plugins/” or “themes”. Here’s an example:

```
Warning: Illegal string offset 'ms_dos' in /homepages/1/abc1234/htdocs/mystore/wp-content/plugins/hello-bully/class-admin.php on line 111
```

### Check the browser console [#](#1-toc-title)

[Open the JavaScript Console in your browser](https://webmasters.stackexchange.com/a/77337), reload that page that doesn’t work and check the error messages below for anything that gives a hint about the cause. If you see an error mentioning JSON, XML, Ajax etc., you could try to click there to see the response that came from the server. Often it contains some lines that shouldn’t be there.

### Search for the breaking point [#](#2-toc-title)

Deactivate all other plugins and switch to a default theme. Then run the test again (or reload the page where you encountered the problem). If now all seems OK, re-activate them one by one to identify the one where things break.

### Enable debugging [#](#3-toc-title)

Add to your wp-config.php file the following lines:  
` define( 'WP_DEBUG', true ); define( 'WP_DEBUG_LOG', true ); `  
Then run the test again (or reload the page where you encountered the problem) and afterwards check the content of the *debug.log* file in the *wp-content* folder of your site if there is any error message that matches the time when you ran the test.

When you’re done, don’t forget to set the *true* values back to *false*, or just delete the lines. It is also recommended to delete the file *debug.log* since it might reveal information about your server.

## Fix The Problem [#](#4-toc-title)

The issue can often be solved only by the author of the plugin or theme that has caused these messages. Alternatively you can hire a developer.

In some cases it may, however, already help to change the PHP version on your server, to [turn off WordPress debugging](https://codex.wordpress.org/WP_DEBUG) or to update, deactivate or replace an outdated or faulty plugin or theme.
