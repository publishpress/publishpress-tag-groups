<?php defined('ABSPATH') || exit;
?>
<div class="tg_settings_tabs_content">
  <form method="POST" action="<?php /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized */ echo isset($_SERVER['REQUEST_URI']) ? esc_url(wp_unslash($_SERVER['REQUEST_URI'])) : ''; ?>">
<ul>
<?php

if ($debug_is_on) : ?>
  <li><?php _e('Debug mode is on.', 'tag-groups')?></li>
  <li>&nbsp;</li>
  
    <?php

    if ($verbose_is_on) : ?>
        <?php
        if ($verbose_is_on_hardcoded) : ?>
  <li><?php _e('Verbose logging is enabled with CM_DEBUG.', 'tag-groups')?></li>
        <?php else : ?>
    <li><?php _e('Verbose logging is on.', 'tag-groups')?>
    <span style="margin: 10px;">
        <button class="button-primary" type="submit" name="verbose_debug" value="0"><?php _e('Turn off ', 'tag-groups')?></button>
  </span>
  </li>
        <?php endif;?>
    <?php else : ?>
    <li><?php _e('Verbose logging is off.', 'tag-groups')?>
    <span style="margin: 10px;">
        <button class="button-primary" type="submit" name="verbose_debug" value="1"><?php _e('Turn on', 'tag-groups')?></button>
  </span>
  </li>
    <?php endif;?>
<?php else : ?>
  <li><?php _e('Debug mode is off.', 'tag-groups')?></li>
<?php endif;?>
</ul>

  <?php echo wp_nonce_field('tag-groups-debug', 'tag-groups-debug-nonce', true, false); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
  <input type="hidden" id="action" name="tg_action" value="debug">

</form>
<p>&nbsp;</p>
  <p><?php /* translators: %s is the href attribute with URL */ echo wp_kses_post(sprintf(__('Learn more <a %s>about debugging</a>.', 'tag-groups'), 'href="' . esc_url($help_url) . '" target="_blank"')); ?></p>

</div>
