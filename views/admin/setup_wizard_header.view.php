<h1><span class="dashicons dashicons-lightbulb" aria-hidden="true"></span>&nbsp;<?php _e('PublishPress Tag Groups Setup Wizard', 'tag-groups') ?></h1>
<div class="tag-groups-wizard-box">
<ul class="tag-groups-steps" aria-label="<?php esc_attr_e('Setup steps', 'tag-groups'); ?>">
  <?php foreach ($steps as $key => $step_info) : ?>
        <?php if (! empty($step_info['title'])) : ?>
      <li<?php if ($key == $step) :
            ?> aria-current="step"<?php
         endif; ?>>
            <?php if ($key < $step) : ?>
          <a href="<?php echo esc_url(add_query_arg('step', $key, admin_url('admin.php?page=tag-groups-settings-setup-wizard'))) ?>">
            <?php endif; ?>
          <h3
            <?php if ($key > $step) :
                ?>style="color:#646970"<?php
            endif; ?>
            <?php if ($key == $step) :
                ?>style="color:#655997" class="tg-current-step"<?php
            endif; ?>>
            <?php if ($key < $step) : ?>
            <span class="dashicons dashicons-yes-alt" aria-hidden="true"></span>
            <?php elseif ($key == $step) : ?>
            <span class="dashicons dashicons-admin-settings" aria-hidden="true"></span>
            <?php else : ?>
            <span class="dashicons dashicons-admin-settings" aria-hidden="true"></span>
            <?php endif; ?>
            <?php echo esc_html($key) ?>. <?php echo esc_html($step_info['title']) ?>
            <?php if ($key < $step) : ?>
            <span class="screen-reader-text"><?php esc_html_e('(completed)', 'tag-groups'); ?></span>
            <?php elseif ($key == $step) : ?>
            <span class="screen-reader-text"><?php esc_html_e('(current step)', 'tag-groups'); ?></span>
            <?php endif; ?>
        </h3>
            <?php if ($key < $step) : ?>
      </a>
            <?php endif; ?>
    </li>
            <?php if ($key < count($steps) - 1) : ?>
      <li aria-hidden="true"><span class="dashicons dashicons-arrow-right-alt" <?php if ($key + 1 > $step) :
            ?>style="color:#646970"<?php
                                                            endif; ?>></span></li>
            <?php endif; ?>
        <?php endif; ?>
  <?php endforeach; ?>
</ul>
</div>
<div class="tag-groups-wizard-box">
