<?php

global $spcms;

$spcms['resources'] = array();

/*
 * JAVASCRIPT
 */
$spcms['resources']['js'] = array();

/*
 * JAVASCRIPT LINKS
 */

/*
 * JAVASCRIPT FILES
 */

$spcms['resources']['js'][1] = array('name' => 'SafePassword - General Library',
                                      'file' => 'libs/js/general.js',
                                      'load' => 'file', // link, file
                                      'type' => 'both', // frontend, backend, both 
                                      'page' => '', // set custom page or leave blank 
                                      'login_page' => true, // display on login form page
                                      'profile_page' => true, // display on profile page
                                      'dashboard_page' => true, // display on dashboard page
                                      'role' => ''); // set admin, owner, guest, customer or leave blank for all roles
$spcms['resources']['js'][2] = array('name' => 'SafePassword - Form',
                                      'file' => 'js/form.js',
                                      'load' => 'file', // link, file
                                      'type' => 'both', // frontend, backend, both 
                                      'page' => '', // set custom page or leave blank  
                                      'login_page' => true, // display on login form page
                                      'profile_page' => true, // display on profile page
                                      'dashboard_page' => true, // display on dashboard page
                                      'role' => ''); // set admin, owner, guest, customer or leave blank for all roles
$spcms['resources']['js'][3] = array('name' => 'SafePassword - Popup',
                                      'file' => 'js/popup.js',
                                      'load' => 'file', // link, file
                                      'type' => 'both', // frontend, backend, both 
                                      'page' => '', // set custom page or leave blank  
                                      'login_page' => true, // display on login form page
                                      'profile_page' => true, // display on profile page
                                      'dashboard_page' => true, // display on dashboard page
                                      'role' => ''); // set admin, owner, guest, customer or leave blank for all roles
$spcms['resources']['js'][4] = array('name' => 'SafePassword - Connection',
                                      'file' => 'js/connection.js',
                                      'load' => 'file', // link, file
                                      'type' => 'both', // frontend, backend, both 
                                      'page' => 'connection', // set custom page or leave blank  
                                      'login_page' => false, // display on login form page
                                      'profile_page' => false, // display on profile page
                                      'dashboard_page' => false, // display on dashboard page
                                      'role' => 'admin'); // set admin, owner, guest, customer or leave blank for all roles
$spcms['resources']['js'][5] = array('name' => 'SafePassword - Enable safepassword for user',
                                      'file' => 'js/register.js',
                                      'load' => 'file', // link, file
                                      'type' => 'backend', // frontend, backend, both 
                                      'page' => '', // set custom page or leave blank  
                                      'login_page' => false, // display on login form page
                                      'profile_page' => true, // display on profile page
                                      'dashboard_page' => false, // display on dashboard page
                                      'role' => 'admin'); // set admin, owner, guest, customer or leave blank for all roles
$spcms['resources']['js'][6] = array('name' => 'SafePassword - QR Code Library',
                                      'file' => 'libs/js/qrcode.min.js',
                                      'load' => 'file', // link, file
                                      'type' => 'backend', // frontend, backend, both 
                                      'page' => '', // set custom page or leave blank  
                                      'login_page' => false, // display on login form page
                                      'profile_page' => true, // display on profile page
                                      'dashboard_page' => false, // display on dashboard page
                                      'role' => 'admin'); // set admin, owner, guest, customer or leave blank for all roles
$spcms['resources']['js'][7] = array('name' => 'SafePassword - Login & Get SafePassword',
                                      'file' => 'js/login.js',
                                      'load' => 'file', // link, file
                                      'type' => 'both', // frontend, backend, both, login 
                                      'page' => '',  // set custom page or leave blank  
                                      'login_page' => true, // display on login form page
                                      'profile_page' => false, // display on profile page
                                      'dashboard_page' => false, // display on dashboard page
                                      'role' => ''); // set admin, owner, guest, customer or leave blank for all roles


/*
 * CSS
 */
$spcms['resources']['css'] = array();

/*
 * CSS LINKS
 */

/*
 * CSS FILES
 */
$spcms['resources']['css'][0] = array('name' => 'SafePassword - Dashboard',
                                       'file' => 'designs/'.$spcms['template'].'/'.$spcms['template'].'.design.css',
                                       'type' => 'both', // frontend, backend, both 
                                       'load' => 'file', // link, file
                                       'page' => ''); // set custom page or leave blank

