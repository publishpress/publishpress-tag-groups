<?php

/**
 * @package     Tag Groups
 * @license     GPL-3.0+
 */

defined('ABSPATH') || exit;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (!class_exists('TagGroups_Image_Field')) {
    /**
     * Image picker (media library) on the "Add New Tag" and "Edit Tag" screens
     */
    class TagGroups_Image_Field
    {
        /**
         * Nonce action and field name
         */
        public const NONCE_ACTION = 'tag_groups_save_image';
        public const NONCE_NAME = 'tag_groups_image_nonce';

        /**
         * @var array taxonomies with the field
         */
        private static $taxonomies = array();

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
            if (empty(self::$taxonomies)) {
                add_action('admin_enqueue_scripts', array( __CLASS__, 'enqueue_media' ));
            }
            self::$taxonomies[] = $taxonomy;

            add_action("{$taxonomy}_add_form_fields", array( __CLASS__, 'render_add_field' ), 21);
            add_action("{$taxonomy}_edit_form_fields", array( __CLASS__, 'render_edit_field' ), 21);
            add_action("created_{$taxonomy}", array( __CLASS__, 'save' ));
            add_action("edited_{$taxonomy}", array( __CLASS__, 'save' ));
        }

        /**
         * Loads the media library on the tag screens of the registered taxonomies
         *
         * @param string $hook_suffix
         * @return void
         */
        public static function enqueue_media($hook_suffix)
        {
            if ('edit-tags.php' !== $hook_suffix && 'term.php' !== $hook_suffix) {
                return;
            }

            $screen = get_current_screen();
            if (!is_object($screen) || empty($screen->taxonomy) || !in_array($screen->taxonomy, self::$taxonomies, true)) {
                return;
            }

            wp_enqueue_media();
        }

        /**
         * Field on the "Add New Tag" form
         *
         * @return void
         */
        public static function render_add_field()
        {
            echo '<div class="form-field term-tag-groups-image-wrap">';
            echo '<label>' . esc_html__('Image', 'tag-groups') . '</label>';
            self::render_picker(0);
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
            echo '<tr class="form-field term-tag-groups-image-wrap"><th scope="row"><label>' . esc_html__('Image', 'tag-groups') . '</label></th><td>';
            self::render_picker(TagGroups_Term_Image::get_id($term->term_id));
            echo '</td></tr>';
        }

        /**
         * Saves the image when a tag is created or edited
         *
         * @param int $term_id
         * @return void
         */
        public static function save($term_id)
        {
            if (!isset($_POST[ self::NONCE_NAME ], $_POST['tag_groups_image'])) {
                return;
            }

            if (!wp_verify_nonce(sanitize_key(wp_unslash($_POST[ self::NONCE_NAME ])), self::NONCE_ACTION)) {
                return;
            }

            if (!current_user_can('edit_term', $term_id)) {
                return;
            }

            TagGroups_Term_Image::set_id($term_id, absint(wp_unslash($_POST['tag_groups_image'])));
        }

        /**
         * The picker: preview, "Choose image" and "Remove image"
         *
         * @param int $attachment_id
         * @return void
         */
        private static function render_picker($attachment_id)
        {
            self::print_assets();

            wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME, false);
            echo '<div class="tag-groups-image-picker">';
            echo '<input type="hidden" name="tag_groups_image" class="tag-groups-image-value" value="' . (int) $attachment_id . '">';
            echo '<div class="tag-groups-image-preview">';
            if ($attachment_id) {
                echo wp_get_attachment_image($attachment_id, 'thumbnail', false, array( 'alt' => '' ));
            }
            echo '</div>';
            echo '<button type="button" class="button tag-groups-image-choose">' . esc_html__('Choose image', 'tag-groups') . '</button> ';
            echo '<button type="button" class="button-link tag-groups-image-remove"' . ( $attachment_id ? '' : ' hidden' ) . '>' . esc_html__('Remove image', 'tag-groups') . '</button>';
            echo '<p class="description">' . esc_html__('Shown as the card background in tag clouds that use the "Image Cards" tag style.', 'tag-groups') . '</p>';
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
.tag-groups-image-preview:empty { display: none; }
.tag-groups-image-preview img { display: block; width: 96px; height: 96px; margin-bottom: 8px; object-fit: cover; border-radius: 4px; }
.tag-groups-image-picker [hidden] { display: none !important; }
</style>
<script>
(function () {
  var strings = <?php
    echo wp_json_encode(array(
        'title'  => __('Choose tag image', 'tag-groups'),
        'button' => __('Use this image', 'tag-groups'),
    ));
                ?>;

  function setup(picker) {
    var value = picker.querySelector('.tag-groups-image-value');
    var preview = picker.querySelector('.tag-groups-image-preview');
    var remove = picker.querySelector('.tag-groups-image-remove');
    var frame = null;

    function show(id, url) {
      value.value = id ? String(id) : '0';
      while (preview.firstChild) {
        preview.removeChild(preview.firstChild);
      }
      if (id && url) {
        var img = document.createElement('img');
        img.src = url;
        img.alt = '';
        preview.appendChild(img);
      }
      remove.hidden = !id;
    }

    picker.querySelector('.tag-groups-image-choose').addEventListener('click', function () {
      if (!window.wp || !wp.media) {
        return;
      }
      if (!frame) {
        frame = wp.media({ title: strings.title, button: { text: strings.button }, library: { type: 'image' }, multiple: false });
        frame.on('select', function () {
          var attachment = frame.state().get('selection').first().toJSON();
          var sizes = attachment.sizes || {};
          var url = sizes.thumbnail ? sizes.thumbnail.url : attachment.url;
          show(attachment.id, url);
        });
      }
      frame.open();
    });

    remove.addEventListener('click', function () {
      show(0, '');
    });

    picker.tagGroupsReset = function () {
      show(0, '');
    };
  }

  document.addEventListener('DOMContentLoaded', function () {
    var pickers = document.querySelectorAll('.tag-groups-image-picker');
    for (var i = 0; i < pickers.length; i++) {
      setup(pickers[i]);
    }
    // The "Add New Tag" form is sent with Ajax and kept after an error, so clear the picker only after a tag was created
    if (window.jQuery) {
      jQuery(document).ajaxSuccess(function (event, xhr, settings) {
        if (!settings || 'string' !== typeof settings.data || settings.data.indexOf('action=add-tag') === -1) {
          return;
        }
        var response = xhr && 'string' === typeof xhr.responseText ? xhr.responseText : '';
        if (response.indexOf('<wp_error') !== -1 || response.indexOf('<term') === -1) {
          return;
        }
        var picker = document.querySelector('#addtag .tag-groups-image-picker');
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
