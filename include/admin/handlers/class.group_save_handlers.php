<?php

/**
 * @package     Tag Groups
 *
 * @author      Christoph Amthor
 * @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license     GPL-3.0+
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
defined('ABSPATH') || exit;

if (! class_exists('TagGroups_Group_Save_Handlers')) {
/**
   *
   */
    class TagGroups_Group_Save_Handlers
    {
      /**
       * Schedule to clear the transient cache tag_groups_group_terms
       *
       * Using cron so that we won't execute it for each tag in a series
       *
       * @param  void
       * @return void
       */
        public static function schedule_clear_tag_groups_group_terms()
        {

            TagGroups_Cron::schedule_in_secs(2, 'tag_groups_clear_tag_groups_group_terms');
        }
    }


}
