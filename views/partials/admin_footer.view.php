<?php defined('ABSPATH') || exit;
?>
<div id="tg_reset_filter_button" style="display: inline; z-index:999; margin: 20px 0;">
  <form method="POST" action="<?php echo esc_url($reset_url) ?>">
    <input class="button-primary" type="submit" name="reset" value="<?php _e('Reset Tag Filter', 'tag-groups') ?>" id="submitbutton" />
  </form>
</div>
