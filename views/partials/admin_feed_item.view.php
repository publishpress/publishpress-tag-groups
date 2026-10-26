<div class="tag_groups_feed_item">
    <div class="tag_groups_feed_image">
        <a href="<?php echo esc_url($link) ?>">
            <img src="<?php echo esc_url($image_src) ?>" alt="<?php echo esc_attr($title) ?>" style="max-height:150px; max-width:300px; box-shadow: 2px 2px 5px #ccc;" />
        </a>
    </div>
    <div class="tag_groups_feed_text">
       <h3>
           <a href="<?php echo esc_url($link) ?>" title="<?php echo esc_attr($title) ?>" target="_blank">
               <?php echo esc_html($title) ?><span class="screen-reader-text"> <?php esc_html_e('(opens in a new tab)', 'tag-groups'); ?></span>
           </a>
       </h3>
       <p>
           <em><?php echo esc_html($date) ?></em>
       </p>
       <p>
           <?php echo wp_kses_post($description) ?>
       </p>
   </div>
    <div style="clear:both"></div>
</div> 
