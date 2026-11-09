<?php

/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2019 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
* @since
*/

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, Squiz.Scope.MethodScope.Missing, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
defined('ABSPATH') || exit;

if (! class_exists('TagGroups_View')) {
/**
  * General handling of views
  *
  * @since
  */
    class TagGroups_View
    {
      /**
      * identifier of this view
      *
      * @var string
      * @since
      */
        private $view_slug;
/**
      * full path of the file providing the view
      *
      * @var string
      * @since
      */
        private $view;
/**
      * array of variables to be made available to the view (key is the variable name)
      *
      * @var array
      * @since
      */
        private $vars;
/**
       * name of the view slug that can be used in a filter identificator
       *
       * @var string
       */
        private $view_slug_filter;

      /**
      * Constructor: checks if view exists
      *
      * @param string $view identifier of the view
      * @return object $this
      * @since
      */
        public function __construct($view_slug)
        {

            $this->view_slug = $view_slug;
            $path = TAG_GROUPS_PLUGIN_ABSOLUTE_PATH . "/views/" . $this->view_slug . ".view.php";
            if (file_exists($path)) {
                    $this->view = $path;
            } else {
                  TagGroups_Error::log('[Tag Groups] View ' . $path . ' not found');
            }

            $this->view_slug_filter = str_replace('/', '-', $this->view_slug);
            $this->vars = array();
            return $this;
        }


      /**
      * renders the view
      *
      * @param void
      * @return void
      */
        public function render()
        {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Views contain trusted HTML/JS from plugin files
            echo $this->return_html();
        }


      /**
        * returns the view
        *
        * @param void
        * @return string $html
        */
        public function return_html()
        {

            if (empty($this->view)) {
                return '';
            }
      
            $this->do_filter_atts();
            extract($this->vars, EXTR_SKIP);
            ob_start();
            include $this->view;
            $html = ob_get_clean();
            return $this->do_filter_html($html);
        }


      /**
       * Option to modify the attributes used in the templates
       *
       * @return void
       */
        private function do_filter_atts()
        {

          /**
           * Filters the attributes before rendering
           * 
           * @param array $this->vars
           * @param string $this->view_slug_filter
           * @return array must contain same fields and data types as $this->vars
           */
            $this->vars = apply_filters('tag_groups_view_atts', $this->vars, $this->view_slug_filter);
        }


      /**
       * Option to customize the output
       *
       * @param string $html
       * @return string
       */
        private function do_filter_html($html)
        {

          /**
           * Filters the final output of the view
           * 
           * @param string $html
           * @return string
           */
            return apply_filters('tag_groups_view-' . $this->view_slug_filter, $html);
        }


      /**
      * General setter for $this->vars, accepting an array of key and values or one pair of key and value
      *
      * @param array|string $variable_name_or_array
      * @param mixed@null $data
      * @return object $this
      */
        public function set($variable_name_or_array, $data = null)
        {

            if (is_string($variable_name_or_array)) {
                $this->set_view_var($variable_name_or_array, $data);
            } elseif (is_array($variable_name_or_array)) {
                foreach ($variable_name_or_array as $key => $value) {
                    $this->set_view_var($key, $value);
                }
            }

            return $this;
        }


      /**
      * Setter for $this->vars
      *
      * @param string $key
      * @param mixed $value
      * @return void
      */
        private function set_view_var($key, $value)
        {

            $this->vars[ $key ] = $value;
        }
    }


}
