<div class="tg_settings_tabs_content">
  <div class="tg_admin_accordion" >
    <h3>WordPress</h3>
    <table class="widefat fixed">
      <tr><td><?php _e('WordPress Version', 'tag-groups'); ?></td><td><?php
        echo esc_html(get_bloginfo('version'));
        ?></td></tr>
      <tr><td><?php _e('Site URL', 'tag-groups'); ?></td><td><?php
        echo esc_url(site_url());
        ?></td></tr>
      <tr><td><?php _e('Home URL', 'tag-groups'); ?></td><td><?php
        echo esc_url(home_url());
        ?></td></tr>

      <tr><td><?php _e('Active Theme', 'tag-groups'); ?></td><td><?php
        echo esc_html($active_theme->get('Name'));
        ?> (<?php _e('Version', 'tag-groups'); ?> <?php
echo esc_html($active_theme->get('Version'));
?>)</td></tr>

      <tr><td><?php _e('Ajax Test', 'tag-groups'); ?></td><td>
        <span id="ajax_test_field"><?php
        _e('Checking...', 'tag-groups');
        ?></span>
        <input type="button" id="chatty-mango-help-button-ajax" class="button button-primary chatty-mango-help-icon" style="display: none; float: right;" value="<?php
        _e('Show the Response', 'tag-groups');
        ?>" data-topic="ajax">
        <div id="ajax_error_field" class="chatty-mango-help-container chatty-mango-help-container-ajax" style="display: none;"></div>
      </td></tr>

      <?php

        if (defined('ICL_SITEPRESS_VERSION')) {
            ?>
        <tr><td>WPML Version</td><td><?php
            echo esc_html(ICL_SITEPRESS_VERSION);
        ?></td></tr>
            <?php
        }

        ?>

      <?php
        if (function_exists('pll_the_languages')) {
            ?>
        <!-- TODO: Can we get the version? -->
        <tr><td>Polylang detected</td><td></td></tr>
              <?php
        }
        ?>

    </table>
  </div>

  <script>
  jQuery(document).ready(function(){
    jQuery.ajax({
      url: "<?php echo esc_url($ajax_test_url);
        ?>",
      data: {
        action: "tg_ajax_manage_groups",
        tag_groups_task: "test",
        nonce: "<?php echo esc_attr(wp_create_nonce('tg_groups_management')); ?>"
      },
      method: "post",
      dataType: 'text',
    })
    .done(function(data){
      try {
        var dataParsed = JSON.parse(data);
        if (typeof dataParsed.data !== 'undefined' && dataParsed.data === 'success'){
          jQuery("#ajax_test_field").html("<span class=\"dashicons dashicons-yes\" style=\"color:green;\" title=\"<?php
            _e('passed', 'tag-groups');
            ?>\"></span>");
        } else {
          if (dataParsed.data) {
            tg_ajaxFailure(dataParsed.data);
          } else {
            tg_ajaxFailure(data);
          }
        }
      } catch(e) {
        tg_ajaxFailure(data);
      }
    })
    .fail(function(response){
      tg_ajaxFailure(response.responseText);
    });
  });

  function tg_ajaxFailure(text) {
    jQuery("#ajax_error_field").text(text);

    jQuery("#chatty-mango-help-button-ajax").show();

    let learnHowToFixLink = " <a href=\"https://publishpress.com/tag-groups/\" target=\"_blank\" rel=\"noopener noreferrer\">Learn more</a>";
    jQuery("#ajax_test_field").html("<span class=\"dashicons dashicons-no\" style=\"color:red;\" title=\"<?php
    _e('failed', 'tag-groups');
    ?>\"></span> " + learnHowToFixLink);
  }
  </script>

  <div class="tg_admin_accordion" >
    <h3><?php
    _e('Benchmarks', 'tag-groups');
    ?></h3>

    <table class="widefat fixed">
      <?php

        if (!empty($benchmarks)) {
            ?>
                <?php
                foreach ($benchmarks as $benchmark) {
                    ?>
          <tr><td><?php echo esc_html($benchmark['name']); ?></td><td><?php echo esc_html($benchmark['value']); ?></td></tr>
                    <?php
                }
                ?>
            <?php
        }

        ?>
      <tr><td id="cache_benchmark_name"><?php
        _e('Cache', 'tag-groups');
        ?></td><td id="cache_benchmark_value"><?php
_e('Tag groups pro only', 'tag-groups');
?></td></tr>
    </table>
    <script>
    jQuery(document).ready(function(){
      jQuery.ajax({
        url: "<?php echo esc_url($ajax_test_url);
        ?>",
        data: {
          action: "tg_ajax_benchmark",
          task: "cache",
          nonce: <?php echo wp_json_encode(wp_create_nonce('tag_groups_cache_benchmark')); ?>
        },
        method: "post",
        dataType: 'text',
      }).done(function(data){
        var dataParsed = JSON.parse(data);
        jQuery('#cache_benchmark_name').html(dataParsed.name);
        jQuery('#cache_benchmark_value').html(dataParsed.value);
      });
    });
    </script>
  </div>

  <div class="tg_admin_accordion" >
    <h3><?php
    _e('Server', 'tag-groups');
    ?></h3>
    <table class="widefat fixed">

      <tr><td>PHP Version</td><td><?php
        echo esc_html($phpversion);
        ?>
        <?php

        if ($php_upgrade_recommendation) {
            ?>
                  <?php
                    printf(' <a href="%s" target="_blank">%s</a>', 'https://wordpress.org/support/upgrade-php/', '<span class="dashicons dashicons-warning"></span>');
                    ?>
                <?php
        }

        ?>
      </td></tr>
      <tr><td>PHP Memory Limit</td><td><?php
        echo esc_html(ini_get('memory_limit'));
        ?></td></tr>
      <tr><td>PHP Max Execution Time</td><td><?php
        echo esc_html(ini_get('max_execution_time'));
        ?> secs</td></tr>
      <tr><td>PHP Post Max Size</td><td><?php
        echo esc_html(ini_get('post_max_size'));
        ?></td></tr>
      <tr><td>Database</td><td><?php
        echo esc_html($db_info);
        ?></td></tr>
    </table>
  </div>

  <div class="tg_admin_accordion" >
    <h3><?php
    _e('Constants', 'tag-groups');
    ?></h3>

    <table class="widefat fixed">

      <?php
        foreach ($wp_constants as $wp_constant) {
            ?>
                <?php

                if (isset($constants[$wp_constant])) {
                    ?>
          <tr><td><?php echo esc_html($wp_constant); ?></td><td><?php echo esc_html($constants[$wp_constant]); ?></td></tr>
                    <?php
                } else {
                    ?>
          <tr><td><?php echo esc_html($wp_constant); ?></td><td>not set</td></tr>
                    <?php
                }

                ?>
            <?php
        }
        ?>

      <?php
        foreach ($constants as $key => $value) {
            ?>
                <?php

                if (preg_match("/^TAG_GROUPS_/", $key) == 1) {
                    ?>
          <tr><td><?php echo esc_html($key); ?></td><td><?php echo esc_html($value); ?></td></tr>
                    <?php
                }

                ?>
            <?php
        }
        ?>
    </table>
  </div>
</div>
