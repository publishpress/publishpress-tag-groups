<h1><span class="dashicons dashicons-lightbulb"></span>&nbsp;<?php _e('PublishPress Tag Groups Setup Wizard', 'tag-groups') ?></h1>
<div class="tag-groups-wizard-box">
<ul class="tag-groups-steps">
  <?php foreach ($steps as $key => $step_info) : ?>
        <?php if (! empty($step_info['title'])) : ?>
      <li>
            <?php if ($key < $step) : ?>
          <a href="<?php echo esc_url(add_query_arg('step', $key, admin_url('admin.php?page=tag-groups-settings-setup-wizard'))) ?>">
            <?php endif; ?>
          <h3
            <?php if ($key > $step) :
                ?>style="color:#ccc"<?php 
            endif; ?>
            <?php if ($key == $step) :
                ?>style="color:#655997"<?php
            endif; ?>>
            <?php if ($key < $step) : ?>
            <span class="dashicons dashicons-yes-alt"></span>
            <?php elseif ($key == $step) : ?>
            <span class="dashicons dashicons-admin-settings"></span>
            <?php else : ?>
            <span class="dashicons dashicons-admin-settings"></span>
            <?php endif; ?>
            <?php echo esc_html($key) ?>. <?php echo esc_html($step_info['title']) ?>
        </h3>
      </a>
    </li>
            <?php if ($key < count($steps) - 1) : ?>
      <li><span class="dashicons dashicons-arrow-right-alt" <?php if ($key + 1 > $step) :
            ?>style="color:#ccc"<?php 
                                                            endif; ?>></span></li>
            <?php endif; ?>
        <?php endif; ?>
  <?php endforeach; ?>
</ul>
</div>
<div class="tag-groups-wizard-box">
