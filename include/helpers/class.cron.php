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

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
defined('ABSPATH') || exit;

if (! class_exists('TagGroups_Cron')) {
/**
   * The main purpose of this class is to keep all cron-related information in one place.
   *
   */
    class TagGroups_Cron
    {
      /**
       * Registers the CRON identifiers and connects them to a method.
       *
       * @param boolean $test Set to true if you need to run this method in a test environment
       * @return void
       */
        public static function register_cron_handlers($test = false)
        {

            if (defined('CM_UNIT_TESTING ') && ! $test) {
                return;
            }

          /**
           * Task to migrate tags from the base to the premium plugin in the background
           */
            add_action('tag_groups_run_term_migration', array( 'TagGroups_Cron_Handlers', 'run_term_migration' ));
  /**
           * Task to check if we need to migrate the tags (regular task that might launch a one-time task)
           */
            add_action('tag_groups_check_tag_migration', array( 'TagGroups_Cron_Handlers', 'maybe_schedule_term_migration' ));
  /**
           * Task to check if the automatic migrations have completed
           */
            add_action('tag_groups_check_migrations_done', array( 'TagGroups_Cron_Handlers', 'tag_groups_check_migrations_done' ));
  /**
           * Task to clear the transient cache tag_groups_group_terms
           */
            add_action('tag_groups_clear_tag_groups_group_terms', array( 'TagGroups_Cron_Handlers', 'clear_tag_groups_group_terms' ));
  /**
           * Task to purge expired transients
           */
            add_action('tag_groups_purge_expired_transients', array( 'TagGroups_Cron_Handlers', 'purge_expired_transients' ));
        }

      /**
       * Schedules a single event
       *
       * @param  int     $seconds_from_now Time in seconds after which to execute the task.
       * @param  string  $identifier       What we used in register_cron_handlers();
       * @return boolean Whether the event was properly scheduled.
       */
        public static function schedule_in_secs($seconds_from_now, $identifier)
        {

          /**
           * We never schedule the same taske twice. Instead we postpone the execution.
           */

            if ($timestamp = wp_next_scheduled($identifier)) {
                wp_unschedule_event($timestamp, $identifier);
            }

            $cron_result = wp_schedule_single_event(time() + $seconds_from_now, $identifier);
            if (false === $cron_result) {
                  TagGroups_Error::log('[Tag Groups Pro] Error scheduling single event %s', $identifier);
                  return false;
            }

            TagGroups_Error::verbose_log('[Tag Groups Pro] Successfully scheduled single event %s after %d seconds', $identifier, $seconds_from_now);
            return true;
        }

      /**
       * Schedules a regular event
       *
       * @param  string  $recurrence 'hourly', 'twicedaily' or 'daily'
       * @param  string  $identifier What we used in register_cron_handlers();
       * @return boolean Whether the event was properly scheduled.
       */
        public static function schedule_regular($recurrence, $identifier)
        {

            if (wp_next_scheduled($identifier)) {
                return true;
            }

            $cron_result = wp_schedule_event(time(), $recurrence, $identifier);
            if (false === $cron_result) {
                  TagGroups_Error::log('[Tag Groups Pro] Error scheduling regular event %s', $identifier);
                  return false;
            }

            TagGroups_Error::verbose_log('[Tag Groups Pro] Successfully scheduled regular event %s with recurrence %s', $identifier, $recurrence);
            return true;
        }
    }


}
