<?php

/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2020 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

defined('ABSPATH') || exit;

if (! class_exists('TagGroups_Import')) {
/**
   *
   * @since 1.38.0
   */
    // phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps -- Legacy class structure
    class TagGroups_Import
    {
      /**
       * File contents for import
       *
       * @var string
       */
        private $contents;
/**
       * type of data in the imported file
       *
       * @var string
       */
        private $file_type;
/**
       * whether an error occured
       *
       * @var integer
       */
        private $error;

        // phpcs:ignore Squiz.Scope.MethodScope.Missing -- Legacy code
        function __construct()
        { 

            $this->error = false;
        }


      /**
       * Determines the type of imported file
       *
       * @return void
       */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps, Squiz.Scope.MethodScope.Missing -- Legacy method naming
        function determine_file_type()
        {

            // phpcs:ignore WordPress.Security.NonceVerification.Missing -- File upload verification
            if (! isset($_FILES['settings_file'])) {
                die("File missing");
            }

          // Check file name, but allow for some additional characters in file name since downloading multiple times may add something to the original name.
          // Allow extension txt for backwards compatibility
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- File upload validation
            preg_match('/^tag_groups_settings-\w{10}[\w,\s-]*\.((txt)|(json))$/', $_FILES['settings_file']['name'], $matches_settings);
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- File upload validation
            preg_match('/^tag_groups_terms-\w{10}[\w,\s-]*\.json$/', $_FILES['settings_file']['name'], $matches_terms);
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- File upload validation
            if (! empty($matches_settings) && ! empty($matches_settings[0]) && $matches_settings[0] == $_FILES['settings_file']['name']) {
                  $this->file_type = 'settings';
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- File upload validation
            } elseif (! empty($matches_terms) && ! empty($matches_terms[0]) && $matches_terms[0] == $_FILES['settings_file']['name']) {
                $this->file_type = 'terms';
            } else {
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing -- File upload validation
                if (! empty($_FILES['settings_file']['name'])) {
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing -- File upload validation
                    $file_info = ' ' . $_FILES['settings_file']['name'];
                } else {
                    $file_info = '';
                }
        
                $this->error = true;
                TagGroups_Admin_Notice::add('error', __('Error uploading the file.', 'tag-groups') . $file_info);
            }
        }


      /**
       * Reads the contents
       *
       * @return void
       */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps, Squiz.Scope.MethodScope.Missing -- Legacy method naming
        function read_file()
        {

            if ($this->error) {
                return;
            }

            // phpcs:ignore WordPress.Security.NonceVerification.Missing -- File upload verification
            if (! isset($_FILES['settings_file'])) {
                die("File missing");
            }

            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- File upload
            $this->contents = @file_get_contents($_FILES['settings_file']['tmp_name']);
            if (false === $this->contents) {
                  $this->error = true;
                  TagGroups_Admin_Notice::add('error', __('Error reading the file.', 'tag-groups'));
            }
        }


      /**
       * Parses the content and saves the imported data to the database, depending on the file type
       *
       * @return void
       */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps, Squiz.Scope.MethodScope.Missing -- Legacy method naming
        function parse_and_save()
        {

            if ($this->error) {
                return;
            }

            if ('settings' == $this->file_type) {
                $this->parse_and_save_options();
            } elseif ('terms' == $this->file_type) {
                $this->parse_and_save_terms();
            }
        }


      /**
       * Parses the content and saves the imported data to the options
       *
       * @return void
       */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps, Squiz.Scope.MethodScope.Missing -- Legacy method naming
        function parse_and_save_options()
        {

            $options = @json_decode($this->contents, true);
            if (empty($options) || ! is_array($options) || $options['name'] != 'tag_groups_options') {
                    $this->error = true;
                    TagGroups_Admin_Notice::add('error', __('Error parsing the file.', 'tag-groups'));
                    return;
            }

            $available_options = TagGroups_Options::get_available_options();
            $count_changed = 0;
  // import only whitelisted options
            foreach ($available_options as $key => $value) {
                if ($available_options[ $key ]['export'] && isset($options[ $key ])) {
                        $result =  TagGroups_Options::update_option($key, $options[ $key ]) ? 1 : 0;
                    if (1 == $result) {
                        TagGroups_Error::verbose_log('[Tag Groups] We updated ' . $key);
                    }

                        $count_changed += $result;
                }
            }

            if (! isset($options['date'])) {
                $options['date'] = ' - ' . __('date unknown', 'tag-groups') . ' - ';
            }

            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.NonceVerification.Missing -- File upload validation
            TagGroups_Admin_Notice::add('success', sprintf(
            /* translators: 1: Filename, 2: Plugin version, 3: Export file creation date. */
                __('Your settings and groups have been imported from the file %1$s (created with plugin version %2$s on %3$s).', 'tag-groups'),
                '<b>' . $_FILES['settings_file']['name'] . '</b>', //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.NonceVerification.Missing
                $options['version'],
                $options['date']
            ) . '</p><p>' .
            /* translators: %d is the number of options */
            sprintf(_n('%d option was added or changed.', '%d options were added or changed.', $count_changed, 'tag-groups'), $count_changed));
            do_action('tag_groups_settings_imported');
        }


      /**
       * Parses the content and saves the imported data to the terms
       *
       * @return void
       */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps, Squiz.Scope.MethodScope.Missing -- Legacy method naming
        function parse_and_save_terms()
        {

            $terms = @json_decode($this->contents, true);
            if (empty($terms) || ! is_array($terms) || $terms['name'] != 'tag_groups_terms') {
                    $this->error = true;
                    TagGroups_Admin_Notice::add('error', __('Error parsing the file.', 'tag-groups'));
                    return;
            }

            $count_processed = 0;
            $count_changed = 0;
            $count_saved = 0;
            remove_all_filters('pre_insert_term');
            remove_all_filters('wp_insert_term_data');
            remove_all_actions('edit_terms');
            remove_all_actions('edited_terms');
            remove_all_actions('edit_term_taxonomy');
            remove_all_actions('edited_term_taxonomy');
            remove_all_actions('edit_term');
            remove_all_actions('edited_term');
            foreach ($terms['terms'] as $term) {
                  $is_term_saved = false;
                  $wp_term = get_term($term['term_id']);
                  // change only terms with the same name, else create new one
                if (empty($wp_term)) {
// check by name - maybe it has a different ID
                    $term_by_name = get_term_by('name', $term['name'], $term['taxonomy']);
                    if ($term_by_name && is_object($term_by_name)) {
                        $term['term_id'] = $term_by_name->term_id;
                    } else {
                        $inserted_term = wp_insert_term($term['name'], $term['taxonomy']);
                        if (is_array($inserted_term)) {
                            $is_term_saved = true;
                            $term['term_id'] = $inserted_term['term_id'];
                        } else {
      // an error occured

                            TagGroups_Error::log('[Tag Groups] Problem inserting ' . $term['name']);
                            continue;
                        }
                    }

                    $wp_term = get_term($term['term_id']);
                }

                    $tg_term = new TagGroups_Term($term['term_id']);
                if (! $tg_term->has_exactly_groups($term['term_group'])) {
                    $tg_term->set_group($term['term_group'])->save();
                    $count_changed++;
                }


                if (
                    $term['name'] != $wp_term->name ||
                    $term['slug'] != $wp_term->slug ||
                    $term['taxonomy'] != $wp_term->taxonomy ||
                    $term['description'] != $wp_term->description || // might lead to "false positives" if description contains invisible HTML
                    $term['parent'] != $wp_term->parent 
                ) {
          // We update the default term data, except for term_group
                    unset($term['term_group']);
                    remove_all_actions("edit_{$term['taxonomy']}");
                    remove_all_actions("edited_{$term['taxonomy']}");
                    $result = wp_update_term($term['term_id'], $term['taxonomy'], $term);
                    if (is_array($result)) {
                        $is_term_saved = true;
                    }
                }

                if ($is_term_saved) {
                    $count_saved++;
                }

                $count_processed++;
            }


            if (! isset($terms['date'])) {
                $terms['date'] = ' - ' . __('date unknown', 'tag-groups') . ' - ';
            }

            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- File upload validation
            TagGroups_Admin_Notice::add('success', sprintf(
            /* translators: 1: Filename, 2: Plugin version, 3: Export file creation date. */
                __('Your terms have been imported from the file %1$s (created with plugin version %2$s on %3$s).', 'tag-groups'),
                '<b>' . $_FILES['settings_file']['name'] . '</b>', //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.NonceVerification.Missing
                $terms['version'],
                $terms['date']
            ) . '</p><p>' .
            /* translators: %d is the number of terms processed */
            sprintf(_n('We processed %d term.', 'We processed %d terms.', $count_processed, 'tag-groups'), $count_processed) . '</p><p>' .
            /* translators: %d is the number of terms saved */
            sprintf(_n('We saved %d term.', 'We saved %d terms.', $count_saved, 'tag-groups'), $count_saved) . '</p><p>' .
            /* translators: %d is the number of terms updated */
            sprintf(_n('The group info of %d term was updated.', 'The group info of %d terms was updated.', $count_changed, 'tag-groups'), $count_changed));
            do_action('tag_groups_terms_imported');
        }
    }


}
