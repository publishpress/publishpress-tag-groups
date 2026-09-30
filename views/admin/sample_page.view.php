<p>
  <?php /* translators: %s is the author display name */ printf(esc_html__('%s created this sample page in the Setup Wizard of the <b>PublishPress Tag Groups</b> plugin. You can safely edit and delete it or keep it for future reference.', 'tag-groups'), esc_html($author_display_name)) ?>
</p>

<p>
  <?php _e('The following shortcodes use a variety of parameters so that you can get an idea of the options. Feel free to generate a new sample page with more features after upgrading the plugin.', 'tag-groups') ?>
</p>

<p>
  <?php /* translators: %s is the href attribute with URL */ printf(esc_html__('Please find links to the documentation in the <a %s>Tag Groups settings</a>.', 'tag-groups'), 'href="' . esc_url($tag_groups_settings_link) . '"') ?>
</p>
<hr />

<h2><?php _e('Tabbed Tag Cloud', 'tag-groups') ?></h2>
<p>
  [tag_groups_cloud custom_title="We have {count} posts for this tag." hide_empty=0 hide_empty_tabs=1] 
</p>
<hr />

<h2><?php _e('Accordion Tag Cloud', 'tag-groups') ?></h2>
<p>
  [tag_groups_accordion separator="|" prepend="#" hide_empty=0 mouseover=1 heightstyle=content hide_empty_content=1]
</p>
<hr />

<h2><?php _e('Alphabetical Tag Cloud', 'tag-groups') ?></h2>
<p>
  [tag_groups_alphabet_tabs exclude_letters="äöüß" hide_empty=0 smallest=16 largest=24]
</p>
<hr />

<h2><?php _e('Tag List', 'tag-groups') ?></h2>
<p>
  [tag_groups_tag_list prepend="#" hide_empty=0 smallest=16 largest=16]
</p>
<hr />

<h2><?php _e('Alphabetical Tag Index', 'tag-groups') ?></h2>
<p>
  [tag_groups_alphabetical_index exclude_letters="0123456789" hide_empty=0 smallest=16 largest=16]
</p>
<hr />

<p>Created by the <a href="https://publishpress.com/tag-groups/" target="_blank" rel="noopener noreferrer">PublishPress Tag Groups plugin</a></p>
