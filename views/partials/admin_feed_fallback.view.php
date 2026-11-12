<?php defined('ABSPATH') || exit;
?>
<div style="padding:10px 10px 10px; text-align:center; float:left;">
    <?php /* translators: %1$s is the href attribute with URL, %2$s is the link text */ echo wp_kses_post(sprintf(__('Please visit <a %1$s>%2$s</a>.', 'tag-groups'), ' href="' . esc_url($posts_url_campaign) . '" target="_blank" ', esc_url($posts_url))) ?>
</div>
