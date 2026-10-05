<?php

/**
 * Tag Groups
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * @package    Tag Groups
 *
 * @author     Christoph Amthor
 * @copyright  2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license    see official vendor website
 *
 * @since      1.24.0
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps, WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL
if (! class_exists('TagGroups_Transients')) {
/**
   * The main purpose of this class is to keep all cron-related information in one place.
   *
   */
    class TagGroups_Transients
    {
      /**
       * option name used to save array of used transient names
       */
        public const TRANSIENT_NAMES = 'tag_group_used_transient_names';
        public const MAX_TRACKED_TRANSIENTS = 500;

      /**
       * Retrieves all transients created by Tag Groups Pro and deletes them
       *
       * @param  void
       * @return int
       */
        public static function delete_all_transients_and_log()
        {

            $count = 0;
            TagGroups_Error::verbose_log('[Tag Groups Pro] Purging all transients.');
            $count += self::delete_all_transients();
            TagGroups_Error::verbose_log('[Tag Groups Pro] Purged %d transients.', $count);
            return $count;
        }

      /**
       * retrieve the array of all transient names that were used
       *
       * @return array
       */
        public static function get_used_names()
        {

            if (empty(self::TRANSIENT_NAMES)) {
                TagGroups_Error::log('[Tag Groups] Error: TRANSIENT_NAMES is empty or undefined.');
                return array();
            }
  
            $used_transient_names = get_option(self::TRANSIENT_NAMES, array());
            if (!is_array($used_transient_names)) {
                  TagGroups_Error::log('[Tag Groups] Retrieved transient names are not an array.');
                  $used_transient_names = array();
            }
  
            return $used_transient_names;
        }

      /**
       * set the array of all transient names that were used
       *
       * @param  array  $used_transient_names
       * @return void
       */
        public static function set_used_names($used_transient_names)
        {

            if (self::TRANSIENT_NAMES) {
                $used_transient_names = array_values(array_unique(array_filter((array) $used_transient_names, 'is_string')));
                $used_transient_names = array_slice($used_transient_names, -self::MAX_TRACKED_TRANSIENTS);
                update_option(self::TRANSIENT_NAMES, $used_transient_names, false);

                global $wpdb;
                $wpdb->update(
                    $wpdb->options,
                    array( 'autoload' => 'no' ),
                    array( 'option_name' => self::TRANSIENT_NAMES ),
                    array( '%s' ),
                    array( '%s' )
                );
    // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
    // TagGroups_Error::verbose_log( '[Tag Groups] Saved array of %d transient name(s)', count( $used_transient_names ) );
            } else {
                TagGroups_Error::log('[Tag Groups] Error saving array of transient names');
            }
        }


      /**
       * Wrapper for delete_transient() that keeps track of the used transient names
       *
       * @param [type] $transient
       * @return void
       */
        public static function delete_transient($transient)
        {

            if (delete_transient($transient)) {
                $used_transient_names = self::get_used_names();
                if (!is_array($used_transient_names)) {
                          $used_transient_names = array();
                }

                $key = array_search($transient, $used_transient_names);
                if ($key !== false) {
                      unset($used_transient_names[ $key ]);
                }

                self::set_used_names($used_transient_names);
            }
        }


      /**
       * Wrapper for set_transient() that keeps track of the used transient names
       *
       * @param  string  $transient
       * @param  mixed   $value
       * @param  integer $expiration
       * @return void
       */
        public static function set_transient($transient, $value, $expiration = null)
        {

            if (strlen($transient) > 172) {
                TagGroups_Error::log('[Tag Groups] Transient name %s is too long!', $transient);
            }

            $used_transient_names = self::get_used_names();
            if (! in_array($transient, $used_transient_names, true)) {
                  $used_transient_names[] = $transient;
                  self::set_used_names($used_transient_names);
            }

            set_transient($transient, $value, $expiration);
        }


      /**
       * Wrapper for get_transient() that keeps track of the used transients
       *
       * @param  string  $transient
       * @return mixed
       */
        public static function get_transient($transient)
        {

            $value = get_transient($transient);
            if (false === $value) {
                    $used_transient_names = self::get_used_names();
                    $key = array_search($transient, $used_transient_names);
                if (false !== $key) {
                    unset($used_transient_names[ $key ]);
                    self::set_used_names($used_transient_names);
                }
            }
  
            return $value;
        }


      /**
       * Deletes all transients that we know about
       *
       * @param  string $substring Optional substring to match transient identifiers against
       * @return integer
       */
        public static function delete_all_transients($substring = '')
        {

            $count = 0;
            $used_transient_names = self::get_used_names();
            foreach ($used_transient_names as $key => $transient) {
                if (! empty($substring) && strpos($transient, $substring) === false) {
                    continue;
                }

                if (delete_transient($transient)) {
                    $count++;
                    unset($used_transient_names[ $key ]);
                } else {
          /**
                       * delete_transient may have returned false because that entry didn't exist; if not, use a hack
                       */
                    if (false === get_transient($transient)) {
                        $count++;
                    } else {
                        if (set_transient($transient, false, 1)) {
                            $count++;
                        };
                    }        

                    unset($used_transient_names[ $key ]);
                }
            }

            if ($count) {
                self::set_used_names($used_transient_names);
            }

          /**
           * Make sure no Tag Groups transients are left in the database
           */
            global $wpdb;
            if (! empty($substring)) {
                  $query_string = $wpdb->prepare("DELETE FROM `{$wpdb->options}` WHERE (`option_name` LIKE %s AND `option_name` LIKE %s)", '_transient_tag_groups_%', '%' . $substring . '%');
                  $query_string_timeout = $wpdb->prepare("DELETE FROM `{$wpdb->options}` WHERE (`option_name` LIKE %s AND `option_name` LIKE %s)", '_transient_timeout_tag_groups_%', '%' . $substring . '%');
            } else {
                $query_string = $wpdb->prepare("DELETE FROM `{$wpdb->options}` WHERE `option_name` LIKE %s", '_transient_tag_groups_%');
                $query_string_timeout = $wpdb->prepare("DELETE FROM `{$wpdb->options}` WHERE `option_name` LIKE %s", '_transient_timeout_tag_groups_%');
            }

            $count += (int) $wpdb->query($query_string);
            $wpdb->query($query_string_timeout);
            return $count;
        }


      /**
       * Deletes all expired transients that we know about
       *
       * @param string $substring
       * @return integer
       */
        public static function delete_all_expired_transients($substring = '')
        {

            $count = 0;
            $used_transient_names = self::get_used_names();
            foreach ($used_transient_names as $key => $transient) {
                if (! empty($substring) && strpos($transient, $substring) === false) {
                    continue;
                }

                if (false === get_transient($transient)) {
          // we never save the value false to transients, so here we know that it was expired or non-existent

                    $count++;
                    unset($used_transient_names[ $key ]);
                }
            }

            if ($count) {
                self::set_used_names($used_transient_names);
            }

            return $count;
        }


      /**
       * Since many front-end features depend on transients, we delete them here if needed
       *
       * @return void
       */
        public static function clear_transients_for_frontend_features()
        {

            self::delete_all_transients('tag_groups_group_terms');
  // including variants for languages (appended strings)
        }


      /**
      * Retrieves all transients for post listings and deletes them
      *
      * @param void
      * @return int
      */
        public static function purge_post_list_transients($post_id, $post)
        {

          // omit autosaves
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    // apparently not equivalent to wp_is_post_autosave( $post_id )

                return 0;
            }

          // omit revisions
            if ('revision' == $post->post_type) {
                return 0;
            }

            $count = 0;
            TagGroups_Error::verbose_log('[Tag Groups Pro] Purging post transients.');
  // Toggle Post Filter
            $count += self::delete_all_transients('tpf_result_');
  // Dynamic Post Filter
            $count += self::delete_all_transients('dpf_result_');
  // Post List
            $count += self::delete_all_transients('post_list_result_');
            TagGroups_Error::verbose_log('[Tag Groups Pro] Purged %d post transient(s).', $count);
            return $count;
        }
    }


}
