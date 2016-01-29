<?php


include_once('AIOAnalytics_LifeCycle.php');

class AIOAnalytics_Plugin extends AIOAnalytics_LifeCycle {

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData() {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array(
            //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
            'ATextInput' => array(__('Enter in some text', 'my-awesome-plugin')),
            'Donated' => array(__('I have donated to this plugin', 'my-awesome-plugin'), 'false', 'true'),
            'CanSeeSubmitData' => array(__('Can See Submission data', 'my-awesome-plugin'),
                                        'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber', 'Anyone')
        );
    }

//    protected function getOptionValueI18nString($optionValue) {
//        $i18nValue = parent::getOptionValueI18nString($optionValue);
//        return $i18nValue;
//    }

    protected function initOptions() {
        $options = $this->getOptionMetaData();
        if (!empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }

    public function getPluginDisplayName() {
        return 'All In One Analytics';
    }

    protected function getMainPluginFileName() {
        return 'all-in-one-analytics.php';
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
        //            `id` INTEGER NOT NULL");
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade() {
    }

    public function addActionsAndFilters() {

        // Add options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));
        add_action('init', array(&$this, 'registerTrackingTagPostType'));
        add_action('init', array(&$this, 'create_trackingtag_taxonomies'));
        add_action('add_meta_boxes', array(&$this, 'loadMetaBoxes'));
        add_action('save_post', array(&$this, 'save_fields'));
        add_action('wp_head', array(&$this, 'show_ga_analytics_tags'));
        add_action('admin_footer', array(&$this, 'my_action_javascript'));
        add_action('wp_ajax_select_tracking_type', array(&$this, 'select_tracking_type_callback'));
        add_action('wp_ajax_selectedpages_chooser', array(&$this, 'selectedpages_chooser_callback'));
        add_action('wp_ajax_allpages', array(&$this, 'allpages_callback'));

        function title_text_input ( $title ) {
            if ( get_post_type() == 'trackingtag' ) {
                $title = __( 'Provide a title for your tracking tag (i.e.: Google Analytics)' );
            }
            return $title;
        }
        add_filter( 'enter_title_here', 'title_text_input' );

        function add_trackingtag_columns($columns) {
            $columns = array(
                'cb' => '<input type="checkbox" />',
                'title' => __( 'Name' ),
                'tag_type' => __( 'Type' ),
                'tag_id' => __( 'ID' ),
                'date' => __( 'Date' )
            );
            return $columns;
        }
        add_filter('manage_edit-trackingtag_columns' , 'add_trackingtag_columns');

        function custom_trackingtag_column( $column, $post_id ) {
            switch ( $column ) {
                case 'tag_type' :
                    echo get_post_meta( $post_id , 'tag_type', true );
                    break;
                case 'tag_id' :
                    echo get_post_meta( $post_id , 'tag_id' , true ); 
                    break;
                default:
                    break;
            }
        }
        add_action( 'manage_trackingtag_posts_custom_column' , 'custom_trackingtag_column', 10, 2 );

        // Example adding a script & style just for the options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        //        if (strpos($_SERVER['REQUEST_URI'], $this->getSettingsSlug()) !== false) {
        //            wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));
        //            wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        }


        // Add Actions & Filters
        // http://plugin.michael-simpson.com/?page_id=37


        // Adding scripts & styles to all pages
        // Examples:
        //        wp_enqueue_script('jquery');
        //        wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));


        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39


        // Register AJAX hooks
        // http://plugin.michael-simpson.com/?page_id=41

    }


}
