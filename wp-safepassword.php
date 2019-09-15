<?php
/*
Plugin Name: WP SafePassword
Version: 1.1
Plugin URI: https://www.safepassword.net/installation
Description: Login Protection - Email login and phone login with SafePassword Login Security protect your users login and admin login by hackers against bruteforce attacks.
Author: SafePassword Login Security
Author URI: https://www.safepassword.net
*/

global $spcms;
$spcms = array();
global $spsafepwd;
$spsafepwd = new stdClass;

// Redirect after plugin is activated
register_activation_hook(__FILE__, 'wpSafepasswordActivate');

/*
 * Config
 */

// Load Config General
include_once 'config/general.php';

// Load Config Database
include_once 'config/database.php';

// Load Config Requests
include_once 'config/requests.php';

// Load Config Resources
include_once 'config/resources.php';
 
// Plugin Url
$spcms['plugin_url'] = plugin_dir_url(__FILE__);

// Plugin path
$spcms['plugin_path'] = plugin_dir_path(__FILE__);


/*
 * Models
 */

// Load Dashboard - Main
include_once 'models/main.php';

// Start Main
$spsafepwd->main = class_exists('safepwdMain') ? new safepwdMain():'Class does not exist!';


/*
 * CMS
 */

// Load CMS
include_once 'cms/load.php';
  
/*
 * Plugin Activated
 */
function wpSafepasswordActivate(){
    add_option('wpsafepassword_activation', 'true');
}