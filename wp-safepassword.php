<?php
/*
Plugin Name: WP SafePassword
Version: 1.0
Plugin URI: https://www.safepassword.net/installation
Description: Login with SafePassword.
Author: Schway
Author URI: https://www.safepassword.net
*/

global $spcms;
$spcms = array();
global $spsafepwd;
$spsafepwd = new stdClass;

// Redirect after plugin is activated
register_activation_hook(__FILE__, 'wpSafepasswordActivate');

$spcms['plugin_url'] = plugin_dir_url(__FILE__);

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