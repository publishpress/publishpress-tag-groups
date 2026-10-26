<fieldset>
  <div class="inline-edit-col">
    <label><span class="title"><?php _e('Groups', 'tag-groups') ?></span>
      <span class="input-text-wrap">

        <select id="term-group-option" name="term-group" class="ptitle" autocomplete="off">
          <option value="0" ><?php _e('not assigned', 'tag-groups') ?></option>
          <?php foreach ($term_groups as $term_group) :
                ?>
            <option value="<?php echo esc_attr($term_group['term_group']); ?>"><?php echo esc_html($term_group['label']); ?></option>
          <?php endforeach; ?>
        </select>

        <?php // id must be "tag-groups-option-nonce" because otherwise identical with "Add New Tag" form on the left side. ?>
        <input type="hidden" name="tag-groups-nonce" id="tag-groups-option-nonce" value="" />
        <input type="hidden" name="tag-groups-taxonomy" id="tag-groups-option-taxonomy" value="<?php echo esc_attr($screen->taxonomy); ?>" />
      </span>
    </label>
  </div>
</fieldset>
