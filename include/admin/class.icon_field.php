<?php

/**
 * @package     Tag Groups
 * @license     GPL-3.0+
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (!class_exists('TagGroups_Icon_Field')) {
    /**
     * Icon picker on the "Add New Tag" and "Edit Tag" screens
     */
    class TagGroups_Icon_Field
    {
        /**
         * Nonce action and field name
         */
        public const NONCE_ACTION = 'tag_groups_save_icon';
        public const NONCE_NAME = 'tag_groups_icon_nonce';

        /**
         * @var bool whether the picker CSS and JS were printed
         */
        private static $assets_printed = false;

        /**
         * Registers the field and the save handlers for a taxonomy
         *
         * @param string $taxonomy
         * @return void
         */
        public static function register($taxonomy)
        {
            add_action("{$taxonomy}_add_form_fields", array( __CLASS__, 'render_add_field' ), 20);
            add_action("{$taxonomy}_edit_form_fields", array( __CLASS__, 'render_edit_field' ), 20);
            add_action("created_{$taxonomy}", array( __CLASS__, 'save' ));
            add_action("edited_{$taxonomy}", array( __CLASS__, 'save' ));
        }

        /**
         * Field on the "Add New Tag" form
         *
         * @return void
         */
        public static function render_add_field()
        {
            echo '<div class="form-field term-tag-groups-icon-wrap">';
            echo '<label>' . esc_html__('Icon', 'tag-groups') . '</label>';
            self::render_picker('');
            echo '</div>';
        }

        /**
         * Field on the "Edit Tag" form
         *
         * @param WP_Term $term
         * @return void
         */
        public static function render_edit_field($term)
        {
            echo '<tr class="form-field term-tag-groups-icon-wrap"><th scope="row"><label>' . esc_html__('Icon', 'tag-groups') . '</label></th><td>';
            self::render_picker(TagGroups_Icons::get_term_icon($term->term_id));
            echo '</td></tr>';
        }

        /**
         * Saves the icon when a tag is created or edited
         *
         * @param int $term_id
         * @return void
         */
        public static function save($term_id)
        {
            if (!isset($_POST[ self::NONCE_NAME ], $_POST['tag_groups_icon'])) {
                return;
            }

            if (!wp_verify_nonce(sanitize_key(wp_unslash($_POST[ self::NONCE_NAME ])), self::NONCE_ACTION)) {
                return;
            }

            if (!current_user_can('edit_term', $term_id)) {
                return;
            }

            TagGroups_Icons::set_term_icon($term_id, sanitize_text_field(wp_unslash($_POST['tag_groups_icon'])));
        }

        /**
         * The picker: current icon, search box and a grid of all icons
         *
         * @param string $current icon key
         * @return void
         */
        private static function render_picker($current)
        {
            self::print_assets();

            $icons = TagGroups_Icons::get_all();
            $current_label = ( '' !== $current && isset($icons[ $current ]) ) ? $icons[ $current ][0] : __('No icon', 'tag-groups');

            wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME, false);
            echo '<div class="tag-groups-icon-picker">';
            echo '<input type="hidden" name="tag_groups_icon" class="tag-groups-icon-value" value="' . esc_attr($current) . '">';
            echo '<div class="tag-groups-icon-current">';
            echo '<span class="tag-groups-icon-preview">' . TagGroups_Icons::get_svg($current) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG built from escaped parts
            echo '<span class="tag-groups-icon-current-label">' . esc_html($current_label) . '</span>';
            echo '<button type="button" class="button-link tag-groups-icon-clear"' . ( '' === $current ? ' hidden' : '' ) . '>' . esc_html__('Remove icon', 'tag-groups') . '</button>';
            echo '</div>';
            echo '<input type="search" class="tag-groups-icon-search" placeholder="' . esc_attr__('Search icons', 'tag-groups') . '" aria-label="' . esc_attr__('Search icons', 'tag-groups') . '">';
            echo '<div class="tag-groups-icon-grid">';
            foreach ($icons as $key => $icon) {
                echo '<button type="button" class="tag-groups-icon-option" data-key="' . esc_attr($key) . '" data-label="' . esc_attr($icon[0]) . '" title="' . esc_attr($icon[0]) . '" aria-label="' . esc_attr($icon[0]) . '" aria-pressed="' . ( $key === $current ? 'true' : 'false' ) . '">';
                echo TagGroups_Icons::get_svg($key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG built from escaped parts
                echo '</button>';
            }
            echo '</div>';
            echo '<p class="description">' . esc_html__('Shown before the tag name in tag clouds that have "Show tag icons" turned on.', 'tag-groups') . ' ' . esc_html__('Icons by Font Awesome Free, CC BY 4.0.', 'tag-groups') . '</p>';
            echo '</div>';
        }

        /**
         * CSS and JS of the picker, printed once
         *
         * @return void
         */
        private static function print_assets()
        {
            if (self::$assets_printed) {
                return;
            }
            self::$assets_printed = true;
            ?>
<style>
.tag-groups-icon-picker { max-width: 34em; }
.tag-groups-icon-picker .tag-groups-icon { width: 18px; height: 18px; fill: currentColor; }
.tag-groups-icon-current { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
.tag-groups-icon-preview { display: inline-flex; min-width: 18px; }
.tag-groups-icon-picker .tag-groups-icon-search { width: 100%; margin-bottom: 6px; }
.tag-groups-icon-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(36px, 1fr)); gap: 4px; max-height: 190px; overflow-y: auto; padding: 4px; border: 1px solid #dcdcde; background: #fff; }
.tag-groups-icon-option { display: flex; align-items: center; justify-content: center; height: 36px; padding: 0; border: 1px solid transparent; border-radius: 4px; background: none; color: #1d2327; cursor: pointer; }
.tag-groups-icon-option:hover { border-color: #8c8f94; }
.tag-groups-icon-option:focus-visible { outline: 2px solid #2271b1; outline-offset: 1px; }
.tag-groups-icon-option[aria-pressed="true"] { border-color: #2271b1; background: #2271b1; color: #fff; }
.tag-groups-icon-picker [hidden] { display: none !important; }
</style>
<script>
(function () {
  function setup(picker) {
    var value = picker.querySelector('.tag-groups-icon-value');
    var preview = picker.querySelector('.tag-groups-icon-preview');
    var label = picker.querySelector('.tag-groups-icon-current-label');
    var clear = picker.querySelector('.tag-groups-icon-clear');
    var search = picker.querySelector('.tag-groups-icon-search');
    var options = picker.querySelectorAll('.tag-groups-icon-option');
    var noIcon = <?php echo wp_json_encode(__('No icon', 'tag-groups')); ?>;

    function select(option) {
      for (var i = 0; i < options.length; i++) {
        options[i].setAttribute('aria-pressed', options[i] === option ? 'true' : 'false');
      }
      value.value = option ? option.getAttribute('data-key') : '';
      preview.innerHTML = option ? option.innerHTML : '';
      label.textContent = option ? option.getAttribute('data-label') : noIcon;
      clear.hidden = !option;
    }

    picker.addEventListener('click', function (event) {
      var option = event.target.closest('.tag-groups-icon-option');
      if (option) {
        select(option);
      } else if (event.target.closest('.tag-groups-icon-clear')) {
        select(null);
      }
    });

    search.addEventListener('input', function () {
      var query = search.value.trim().toLowerCase();
      for (var i = 0; i < options.length; i++) {
        options[i].hidden = '' !== query && options[i].getAttribute('data-label').toLowerCase().indexOf(query) === -1;
      }
    });
    // Enter in the search box must not submit the tag form
    search.addEventListener('keydown', function (event) {
      if ('Enter' === event.key) {
        event.preventDefault();
      }
    });

    picker.tagGroupsReset = function () {
      search.value = '';
      search.dispatchEvent(new Event('input'));
      select(null);
    };
  }

  document.addEventListener('DOMContentLoaded', function () {
    var pickers = document.querySelectorAll('.tag-groups-icon-picker');
    for (var i = 0; i < pickers.length; i++) {
      setup(pickers[i]);
    }
    // The "Add New Tag" form is sent with Ajax and reused, so clear the picker after a tag was added
    if (window.jQuery) {
      jQuery(document).ajaxSuccess(function (event, xhr, settings) {
        if (!settings || 'string' !== typeof settings.data || settings.data.indexOf('action=add-tag') === -1) {
          return;
        }
        // Errors such as a duplicate name also arrive as a successful response; WordPress keeps the form then
        var response = xhr && 'string' === typeof xhr.responseText ? xhr.responseText : '';
        if (response.indexOf('<wp_error') !== -1 || response.indexOf('<term') === -1) {
          return;
        }
        var picker = document.querySelector('#addtag .tag-groups-icon-picker');
        if (picker && picker.tagGroupsReset) {
          picker.tagGroupsReset();
        }
      });
    }
  });
})();
</script>
            <?php
        }
    }
}
