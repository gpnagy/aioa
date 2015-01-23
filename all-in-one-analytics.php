<?php
/*
   Plugin Name: All In One Analytics
   Plugin URI: http://wordpress.org/extend/plugins/all-in-one-analytics/
   Version: 0.1
   Author: George Nagy
   Description: Manage all of your analytics tracking codes in one place. Supports: Google Analytics (universal & classic), Google AdWords, Google Tag Manager, Quantcast, Marketo, LeadLander. Also can be used to manage webmaster verification codes for Google Webmaster Tools and Bing Webmaster Tools.
   Text Domain: all-in-one-analytics
   License: GPLv3
  */

/*
    "WordPress Plugin Template" Copyright (C) 2014 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This following part of this file is part of WordPress Plugin Template for WordPress.

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

define ('AIOA_TEXT_DOMAIN', 'all-in-one-analytics');

$AIOAnalytics_minimalRequiredPhpVersion = '5.0';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function AIOAnalytics_noticePhpVersionWrong() {
    global $AIOAnalytics_minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
      __('Error: plugin "All In One Analytics" requires a newer version of PHP to be running.',  'all-in-one-analytics').
            '<br/>' . __('Minimal version of PHP required: ', 'all-in-one-analytics') . '<strong>' . $AIOAnalytics_minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'all-in-one-analytics') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function AIOAnalytics_PhpVersionCheck() {
    global $AIOAnalytics_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $AIOAnalytics_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'AIOAnalytics_noticePhpVersionWrong');
        return false;
    }
    return true;
}


/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function AIOAnalytics_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain(AIOA_TEXT_DOMAIN, false, $pluginDir . '/languages/');
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

// First initialize i18n
AIOAnalytics_i18n_init();


// Next, run the version check.
// If it is successful, continue with initialization for this plugin
if (AIOAnalytics_PhpVersionCheck()) {
    // Only load and run the init function if we know PHP version can parse it
    include_once('all-in-one-analytics_init.php');
    AIOAnalytics_init(__FILE__);
}
