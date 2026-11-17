---
title: "How to Reduce the Number of Loaded Scripts for Better Speed and SEO"
order: 43
doc_category: publishpress-tag-groups
---

# How to Reduce the Number of Loaded Scripts for Better Speed and SEO

Table of Contents

- [Try to load scripts only where you need them](#0-toc-title)
- [Combine and defer scripts](#1-toc-title)
- [3. Bonus hint: Deactivate debugging mode on your live site](#2-toc-title)

Most scripts in WordPress, and therefore in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin are provided in separate files. They load automatically in the background when a visitor views a page. Scripts are necessary for functionality (mainly JavaScript) and styling (CSS).

Since loading these files takes time and extra requests from the browser, we try to reduce their number and size. The tricky part is to identify which scripts are required on which pages. Sometimes we can figure this out automatically, but considering all the cases with various themes, widgets and other plugins that add their own modifications it is unavoidable that you optimize your site manually.

## Try to load scripts only where you need them [#](#0-toc-title)

Deselect the option “Always load shortcode scripts.” in the [Tag Groups](https://publishpress.com/links/tag-groups) settings -> Front End. Then empty the cache (if you use a plugin or service for caching) and reload all pages where you use Tag Groups features.

![](https://docs-media.publishpress.com/publishpress-tag-groups/front-end-settings-load-scripts-4ac3dcc6a1.webp)

With this option deactivated, the plugin tries to automatically identify the posts and pages where scripts need to be loaded. For that to work, however, the shortcode or block must be present in the *content* part of this page or post. If you insert it in a widget or if your theme adds them later, you need to keep this option activated. The only way to find out is by trying.

If you have to keep this option activated, you could then try a plugin such as [Asset CleanUp: Page Speed Booster](https://wordpress.org/plugins/wp-asset-clean-up/) to load only required scripts per page. For all tabs and accordions you will need the jQuery UI scripts and `.../frontent.min.css`. Otherwise you have to identify the minimal required set of scripts by successively allowing scripts and checking the page. The file names of the scripts often give hints where they are needed. The [JavaScript console](https://webmasters.stackexchange.com/questions/8525/how-do-i-open-the-javascript-console-in-different-browsers#77337) on the public page should not report any errors.

## Combine and defer scripts [#](#1-toc-title)

There are plugins like [Autoptimize](https://wordpress.org/plugins/autoptimize/) that let you combine JavaScript or CSS files and change the time when they are loaded. Make sure to exclude jQuery so that it always loads early. Autoptimize lets you exclude certain patterns – add there: `js/jquery/jquery.min.js,js/jquery/jquery.js`

If you load the pages [with your JavaScript console open](https://webmasters.stackexchange.com/questions/8525/how-do-i-open-the-javascript-console-in-different-browsers#77337), you can identify errors caused by dependencies – when one script needs another one, which should be loaded earlier.

## 3. Bonus hint: Deactivate debugging mode on your live site [#](#2-toc-title)

If you don’t use your site in [debugging mode](https://taxopress.com/docs/how-to-use-the-debug-log/), the Tag Groups plugin always uses minimized scripts. You can identify them in the HTML code by their extensions .min.js and .min.css. By default, debugging mode is off.
