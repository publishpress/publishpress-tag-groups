<?php defined('ABSPATH') || exit;
?>
<h2 class="nav-tab-wrapper">
  <?php foreach ($tabs as $slug => $label) :
        $settings_url = add_query_arg(array( 'active-tab' => $slug ), menu_page_url($page, false));

        ?>
    <a href="<?php echo esc_url($settings_url) ?>" 
    aria-current="<?php echo esc_attr($slug == $active_tab ? 'true' : 'false'); ?>"
    class="nav-tab
        <?php if ($slug == $active_tab) : ?>
        nav-tab-active
        <?php endif; ?>
      "><?php echo esc_html($label) ?>
    </a>
  <?php endforeach; ?>
</h2>
