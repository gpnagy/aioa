<?php
/*
    "WordPress Plugin Template" Copyright (C) 2014 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This file is part of WordPress Plugin Template for WordPress.

    WordPress Plugin Template is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    WordPress Plugin Template is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see http://www.gnu.org/licenses/gpl-3.0.html
*/

include_once('AIOAnalytics_InstallIndicator.php');

class AIOAnalytics_LifeCycle extends AIOAnalytics_InstallIndicator {

    public function install() {

        // Initialize Plugin Options
        $this->initOptions();

        // Initialize DB Tables used by the plugin
        $this->installDatabaseTables();

        // Other Plugin initialization - for the plugin writer to override as needed
        $this->otherInstall();

        // Record the installed version
        $this->saveInstalledVersion();

        // To avoid running install() more then once
        $this->markAsInstalled();
    }

    public function uninstall() {
        $this->otherUninstall();
        $this->unInstallDatabaseTables();
        $this->deleteSavedOptions();
        $this->markAsUnInstalled();
    }

    /**
     * Perform any version-upgrade activities prior to activation (e.g. database changes)
     * @return void
     */
    public function upgrade() {
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=105
     * @return void
     */
    public function activate() {
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=105
     * @return void
     */
    public function deactivate() {
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return void
     */
    protected function initOptions() {
    }

    public function addActionsAndFilters() {
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
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables() {
    }

    /**
     * Override to add any additional actions to be done at install time
     * See: http://plugin.michael-simpson.com/?page_id=33
     * @return void
     */
    protected function otherInstall() {
    }

    /**
     * Override to add any additional actions to be done at uninstall time
     * See: http://plugin.michael-simpson.com/?page_id=33
     * @return void
     */
    protected function otherUninstall() {
    }

    /**
     * Puts the configuration page in the Plugins menu by default.
     * Override to put it elsewhere or create a set of submenus
     * Override with an empty implementation if you don't want a configuration page
     * @return void
     */
    public function addSettingsSubMenuPage() {
        $this->addSettingsMenuPageToTopMenu();
        $this->addSubSettingsMenuPageToAIOAMenu();
    }


    protected function requireExtraPluginFiles() {
        require_once(ABSPATH . 'wp-includes/pluggable.php');
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }

    /**
     * @return string Slug name for the URL to the Setting page
     * (i.e. the page for setting options)
     */
    protected function getSettingsSlug() {
        return get_class($this) . 'Settings';
    }

    protected function getGoogleAnalyticsSlug() {
        return get_class($this) . 'GoogleAnalytics';
    }

    protected function addSettingsMenuPageToTopMenu() {
        $this->requireExtraPluginFiles();
        $displayName = $this->getPluginDisplayName();
        add_menu_page( $displayName, $displayName, 'manage_options', $this->getSettingsSlug(), array(&$this, 'settingsPage'), 'dashicons-chart-area');
    }

    protected function addSubSettingsMenuPageToAIOAMenu() {
        $this->requireExtraPluginFiles();
        $displayName = $this->getPluginDisplayName();
        add_submenu_page( $this->getSettingsSlug(), 'Settings', 'Settings', 'manage_options', $this->getSettingsSlug(), array(&$this, 'settingsPage'));
    }

    /**
     * @param  $name string name of a database table
     * @return string input prefixed with the WordPress DB table prefix
     * plus the prefix for this plugin (lower-cased) to avoid table name collisions.
     * The plugin prefix is lower-cases as a best practice that all DB table names are lower case to
     * avoid issues on some platforms
     */
    public function is_edit_page($new_edit = null){
        global $pagenow;
        //make sure we are on the backend
        if (!is_admin()) return false;

        if($new_edit == "edit")
            return in_array( $pagenow, array( 'post.php' ) );
        elseif($new_edit == "new") //check for new post page
            return in_array( $pagenow, array( 'post-new.php' ) );
        else //check for either new or edit
            return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
    }

    public function registerTrackingTagPostType() {
        $labels = array(
            'name'               => _x( 'Manage Tags', 'post type general name', AIOA_TEXT_DOMAIN ),
            'singular_name'      => _x( 'Tracking Tag', 'post type singular name', AIOA_TEXT_DOMAIN ),
            'menu_name'          => _x( 'Tracking Tags', 'admin menu', AIOA_TEXT_DOMAIN ),
            'name_admin_bar'     => _x( 'Tracking Tag', 'add new on admin bar', AIOA_TEXT_DOMAIN ),
            'add_new'            => _x( 'Add New', 'book', AIOA_TEXT_DOMAIN ),
            'add_new_item'       => __( 'Add New Tracking Tag', AIOA_TEXT_DOMAIN ),
            'new_item'           => __( 'New Tracking Tag', AIOA_TEXT_DOMAIN ),
            'edit_item'          => __( 'Edit Tracking Tag', AIOA_TEXT_DOMAIN ),
            'view_item'          => __( 'View Tracking Tag', AIOA_TEXT_DOMAIN ),
            'all_items'          => __( 'Manage Tags', AIOA_TEXT_DOMAIN ),
            'search_items'       => __( 'Search Tracking Tags', AIOA_TEXT_DOMAIN ),
            'parent_item_colon'  => __( 'Parent Tracking Tags:', AIOA_TEXT_DOMAIN ),
            'not_found'          => __( 'No tracking tags found.', AIOA_TEXT_DOMAIN ),
            'not_found_in_trash' => __( 'No tracking tags found in Trash.', AIOA_TEXT_DOMAIN )
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => $this->getSettingsSlug(),
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'trackingtag' ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title' )
        );

        register_post_type( 'trackingtag', $args );
    }
   
    public function create_trackingtag_taxonomies() {
        // Add new taxonomy, make it hierarchical (like categories)
        $labels = array(
            'name'              => _x( 'Tag Types', 'taxonomy general name' ),
            'singular_name'     => _x( 'Tag Type', 'taxonomy singular name' ),
            'search_items'      => __( 'Search Tag Types' ),
            'all_items'         => __( 'All Tag Types' ),
            'parent_item'       => __( 'Parent Tag Type' ),
            'parent_item_colon' => __( 'Parent Tag Type:' ),
            'edit_item'         => __( 'Edit Tag Type' ),
            'update_item'       => __( 'Update Tag Type' ),
            'add_new_item'      => __( 'Add New Tag Type' ),
            'new_item_name'     => __( 'New Tag Type Name' ),
            'menu_name'         => __( 'Tag Types' ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => false,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'tagtype' ),
        );
        register_taxonomy( 'tagtype', array( 'trackingtag' ), $args );
    }

    public function loadMetaBoxes(){
        if($this->is_edit_page('new')) {
            add_meta_box("tracking_tag_type", "Tracking Tag Type", array(&$this, 'tracking_tag_type'), "trackingtag", "normal", "default");
        }
        add_meta_box("tracking_tag_id", "Tracking Tag Details", array(&$this, 'field_container'), "trackingtag", "normal", "default");
        remove_meta_box( 'slugdiv', 'trackingtag', 'normal' ); 
    }

    public function field_container() {
        echo '<div id="tag_fields"></div>';
    }
    
    public function google_analytics_fields($post_id){
        $tag_id = null;
        $tag_type = null;
        if (!empty($post_id)) {
            $custom = get_post_custom($post_id);
            if (!empty($custom['tag_id'])) {
                $tag_id = $custom['tag_id'][0];
            } else {
                $tag_id = null;
            }
            if (!empty($custom['tag_id'])) {
                $tag_type = $custom['tag_type'][0];
            } else {
                $tag_type = null;
            }              
        }
        $output .= '<p>' . 'Tag Type: ' . 'Google Analytics' . '</p>';

        $output .= '<table class="form-table">';
        $output .= '<tbody>';
        $output .= '<tr>';
        $output .= '<td><label for="tag_id">' . __('Tracking ID', AIOA_TEXT_DOMAIN) . ': </label></td>';
        $output .= '<td><input name="tag_id" value="' . $tag_id . '" /></td>';
        $output .= '</tr>';
        $output .= '</table>';
        $output .= '<div class="instructions">';
        $output .= '<p>' . 'To find this ID, log in to Google Analytics and go to Admin -> Property Settings and find the Tracking ID.' . '</p>';
        $output .= '</div>';
        $output .= '<input type="hidden" name="tag_type" value="ga" />';
        // $output .= '<label for="tag_version">' . __('Tag Type', AIOA_TEXT_DOMAIN) . ': </label>';
        // $output .= '<select name="tag_version">';
        // $output .= $this->generate_select_option($tag_type, 'Classic');
        // $output .= $this->generate_select_option($tag_type, 'Universal');
        // $output .= '</select>';
        return $output;
    }

    public function google_webmaster_tools_fields($post_id){
        $tag_id = null;
        $tag_type = null;

        if (!empty($post_id)) {
            $custom = get_post_custom($post_id);
            if (!empty($custom['tag_id'])) {
                $tag_id = $custom['tag_id'][0];
            }
        }

        $output = '<label><strong>' . __('Webmaster Tools Verification Tag', AIOA_TEXT_DOMAIN) . '</strong>: </label>';
        $output .= '<input name="tag_id" value="' . $tag_id . '" />';
        $output .= '<input type="hidden" name="tag_type" value="gwt" />';
        $output .= '<p> ' . __('This tag is provided when adding a new site to', AIOA_TEXT_DOMAIN) . ' ' . '<a href="https://www.google.com/webmasters/tools/home">' . __('Google Webmaster Tools', AIOA_TEXT_DOMAIN) . '</a>.' . ' ' . __('If your site is already setup in Google Webmaster Tools you can find the tag here', AIOA_TEXT_DOMAIN) . ':';
        $output .= '<ol>';
        $output .= '<li>' . __('Log into Google Webmaster Tools and click on the site you will be verifying', AIOA_TEXT_DOMAIN) . '</li>';
        $output .= '<li>' . __('Click on the gear icon at the top right', AIOA_TEXT_DOMAIN) . '</li>';
        $output .= '<li>' . __('Click on Verification Details', AIOA_TEXT_DOMAIN) . '</li>';
        $output .= '<li>' . __('Click Verify using a different method', AIOA_TEXT_DOMAIN) . '</li>';
        $output .= '<li>' . __('Choose HTML Tag', AIOA_TEXT_DOMAIN) . '</li>';
        $output .= '<li>' . __('Copy the characters between the', AIOA_TEXT_DOMAIN) . ' ' . '<code>content="YOUR_CODE"</code>' . ' ' . __('quotes', AIOA_TEXT_DOMAIN) . '</li>';
        $output .= '</ol></p>';
        return $output;
    }

    public function marketo_fields($post_id){
        $tag_id = null;
        $tag_type = null;

        if (!empty($post_id)) {
            $custom = get_post_custom($post_id);
            if (!empty($custom['tag_id'])) {
                $tag_id = $custom['tag_id'][0];
            }
        }

        $output = '<label><strong>' . __('Marketo ID', AIOA_TEXT_DOMAIN) . '</strong>: </label>';
        $output .= '<input name="tag_id" value="' . $tag_id . '" />';
        $output .= '<input type="hidden" name="tag_type" value="mkto" />';

        return $output;
    }

    public function tracking_tag_type(){
        global $post;
        $custom = get_post_custom($post->ID);
        if (!empty($custom['tag_type'])) {
            $tag_type = $custom['tag_type'][0];
        } else {
            $tag_type = null;
        }
        ?>
        <p><?php _e('Start by choosing the type of tracking tag you would like to add', AIOA_TEXT_DOMAIN); ?>:</p>
        <label><?php _e('Tracking Tag Type', AIOA_TEXT_DOMAIN); ?>:</label>
        <select name="tag_type" id="tag_type">
            <option></option>
            <option value="ga" <?php if($tag_type == 'ga') echo 'selected'; ?>><?php _e('Google Analytics', AIOA_TEXT_DOMAIN); ?></option>
            <option value="gwt" <?php if($tag_type == 'gwt') echo 'selected'; ?>><?php _e('Google Webmaster Tools', AIOA_TEXT_DOMAIN); ?></option>
            <option value="mkto" <?php if($tag_type == 'mkto') echo 'selected'; ?>><?php _e('Marketo', AIOA_TEXT_DOMAIN); ?></option>
        </select>
        <?php 
    }

    public function save_fields(){
        global $post;
        if (!empty($_POST["tag_id"])) update_post_meta($post->ID, "tag_id", $_POST["tag_id"]);
        if (!empty($_POST["tag_type"])) update_post_meta($post->ID, "tag_type", $_POST["tag_type"]);
    }

    public function show_ga_analytics_tags(){
        global $post;
        $get_analytics_tags_args = array(
            'post_type'         => 'trackingtag',
            'posts_per_page'    => -1,
            'post_status'       => 'publish',
            'meta_key'          => 'tag_type',
            'meta_value'        => 'ga'
        );
        $get_analytics_tags = new WP_Query( $get_analytics_tags_args );

        if ( $get_analytics_tags->have_posts() ) {
            echo "\n\n" . '<!-- Google Analytics -->' . "\n";
            echo '<script>' . "\n";
            while ( $get_analytics_tags->have_posts() ) {
                $get_analytics_tags->the_post();
                if ($get_analytics_tags->current_post == 0) {
                    echo '(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){' . "\n";
                    echo '(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),' . "\n";
                    echo 'm=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)' . "\n";
                    echo '})(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');' . "\n";
                    echo 'ga(\'create\', \'' . get_post_meta($post->ID, 'tag_id', true) . '\', \'auto\');' . "\n";
                    echo 'ga(\'send\', \'pageview\');' . "\n";
                } else {
                    echo 'ga(\'create\', \'' . get_post_meta($post->ID, 'tag_id', true) . '\', \'auto\', {\'name\': \'' . 'tracker' . get_the_ID() . '\'});' . "\n";
                    echo 'ga(\'' . 'tracker' . get_the_ID() . '.send\', \'pageview\');' . "\n";
                }
            }
            echo '</script>' . "\n";
        } 
        echo "\n";
        /* Restore original Post Data */
        wp_reset_postdata();
    }

    public function my_action_javascript() {
        global $post;
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        <?php if($this->is_edit_page('new')) { ?>
            $('#tracking_tag_id').hide();
            $('#tag_type').change(function() {
                var data = {
                    'action': 'my_action',
                    'tag_type': $(this).val(),
                    'post_id': <?=$post->ID?>
                };

                $.post(ajaxurl, data, function(response) {
                    $('#tracking_tag_id').fadeOut('fast', function() {
                        $('#tag_fields').html(response);
                        $('#tracking_tag_id').fadeIn();
                    });
                });
            });
        <?php } else { ?>
            var data = {
                'action': 'my_action',
                'tag_type': '<?=get_post_meta($post->ID, "tag_type", true)?>',
                'post_id': <?=$post->ID?>
            };
            $.post(ajaxurl, data, function(response) {
                $('#tag_fields').html(response);
            });
        <?php } ?>
    });
    </script>
    <?php
    }

    public function my_action_callback() {
        global $wpdb; // this is how you get access to the database

        $tag_type = $_POST['tag_type'];

        switch ($tag_type) {
            case 'ga':
                echo $this->google_analytics_fields($_POST['post_id']);
                break;
            case 'gwt':
                echo $this->google_webmaster_tools_fields($_POST['post_id']);
                break;
            case 'mkto':
                echo $this->marketo_fields($_POST['post_id']);
                break;
            default:
                break;
        }
        
        die(); // this is required to return a proper result
    }

    public function generate_select_option($parameter = null, $value = null, $label = null) {
        $selected = null;
        if (empty($label)) $label = $value;
        if (!empty($parameter) && !empty($value) ) {
            if ($parameter == $value) $selected = 'selected';
        }
        return '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
    }


    /**
     * @param  $name string name of a database table
     * @return string input prefixed with the WordPress DB table prefix
     * plus the prefix for this plugin (lower-cased) to avoid table name collisions.
     * The plugin prefix is lower-cases as a best practice that all DB table names are lower case to
     * avoid issues on some platforms
     */
    protected function prefixTableName($name) {
        global $wpdb;
        return $wpdb->prefix . strtolower($this->prefix($name));
    }

    /**
     * Convenience function for creating AJAX URLs.
     *
     * @param $actionName string the name of the ajax action registered in a call like
     * add_action('wp_ajax_actionName', array(&$this, 'functionName'));
     *     and/or
     * add_action('wp_ajax_nopriv_actionName', array(&$this, 'functionName'));
     *
     * If have an additional parameters to add to the Ajax call, e.g. an "id" parameter,
     * you could call this function and append to the returned string like:
     *    $url = $this->getAjaxUrl('myaction&id=') . urlencode($id);
     * or more complex:
     *    $url = sprintf($this->getAjaxUrl('myaction&id=%s&var2=%s&var3=%s'), urlencode($id), urlencode($var2), urlencode($var3));
     *
     * @return string URL that can be used in a web page to make an Ajax call to $this->functionName
     */
    public function getAjaxUrl($actionName) {
        return admin_url('admin-ajax.php') . '?action=' . $actionName;
    }

}
