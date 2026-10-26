<nav class="nav-tab-wrapper tg-nav-tab-wrapper" aria-label="<?php esc_attr_e('Secondary menu', 'tag-groups'); ?>">
  <?php foreach ($tabs as $slug => $label) :
      $settings_url = add_query_arg(array( 'active-tab' => $slug ), menu_page_url($page, false));

        ?>
    <a href="<?php echo esc_url($settings_url) ?>"
    <?php if ($slug == $active_tab) : ?>aria-current="page"<?php endif; ?>
    class="nav-tab
      <?php if ($slug == $active_tab) : ?>
        nav-tab-active
      <?php endif; ?>
      "><?php echo esc_html($label) ?>
    </a>
  <?php endforeach; ?>
</nav>
