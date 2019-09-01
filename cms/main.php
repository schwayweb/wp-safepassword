<?php

class spcmsMain {
  
    function __construct(){
        $this->load();
    }
    
    /*
     * Autodetect & Load CMS
     */ 
    function load() {
        global $spcms;
        global $spclasses;
      
        $spcms['page_disabled'] = true;
        
        if($spcms['type'] == 'auto') {

            // Wordpress Detection
            if(defined('WP_CONTENT_DIR')) {
                $spcms['type'] = 'wordpress';
              
                // Role detect
                add_action('init', array(&$this, 'wpStart'));
                
                /*
                 * Login 
                 */
                add_action('authenticate', array(&$this, 'loginSafePassword'), 30, 3);

                /*
                 * Login Errors 
                 */
                add_filter('login_errors', array(&$this, 'loginErrorsSafePassword'));

                // Add Enable/Disable SafePassword in Profile
                add_action('show_user_profile', array(&$this, 'useSafePassword'));
                add_action('edit_user_profile', array(&$this, 'useSafePassword'));

                // Get SafePassword
                add_action('login_form', array(&$this, 'getSafePassword'));
                
                // Redirect after plugin is activated
                add_action('admin_init', array(&$this, 'wpSafePasswordToConnect'));
              
                $is_ajax_request = $spclasses->protect->post('is_ajax_request');
                
                if (is_admin() 
                    && $is_ajax_request == ''){
                  
                    // JS vars - Translation, Requests, ...
                    if (!has_action('admin_head', array (&$spclasses->resources, 'js') )) {
                        add_action('admin_head', array(&$spclasses->resources, 'js'),10);
                    }
                  
                    // Load Menu
                    add_action('admin_menu', array(&$spclasses->menu, 'load'));
                  
                    // Load CSS & JS FILES
                    add_action('admin_enqueue_scripts', array(&$spclasses->resources, 'backend'));
                } else {
                    
                    // JS vars - Translation, Requests, ...
                    if (!has_action('wp_head', array (&$spclasses->resources, 'js') )) {
                        add_action('wp_head', array(&$spclasses->resources, 'js'),10);
                    }
                  
                    // Load CSS & JS FILES
                    add_action('wp_enqueue_scripts', array(&$spclasses->resources, 'frontend'));
                    
                    // JS vars - Translation, Requests, ... in Login Page
                    if (!has_action('login_enqueue_scripts', array (&$spclasses->resources, 'js') )) {
                        add_action('login_enqueue_scripts', array(&$spclasses->resources, 'js'),10);
                    }
                  
                    // Load CSS & JS FILES in Login Page
                    add_action('login_enqueue_scripts', array(&$spclasses->resources, 'frontend'));
                }
            }
        }
        
        $spclasses->requests = class_exists('spcmsRequests') ? new spcmsRequests():'Class does not exist!';
      
        do_action('spcms_main_after_loaded');
    }
  
    function isBMpage(){
        global $spcms;
        global $spclasses;
        $isPage = false;
        $page = 'none';
      
        // Wordpress Detection
        if(defined('WP_CONTENT_DIR')) {
          
            if($spclasses->protect->get('page') != '') {              
                $page = $spclasses->protect->get('page');
            }
        }
        
        if(strpos($page, 'spsafepwd') !== false) {
            $isPage = true;
            $spcms['page'] = str_replace('spsafepwd-', '', $page);
        }
      
        return $isPage;
    }
  
    /*
     * Wordpress CMS
     */
  
    function wpStart(){
        global $spcms;
        global $spclasses;
        
        // SafePassword Api
        if($spclasses->protect->get('safepassword') == true
          || $spclasses->protect->get('safepassword') == 'true') {
            $spclasses->api->start();
        }
        
        // Get Role
        $this->role();
      
        if($spcms['role'] == 'admin') {
            // Check if is installed & install
            $spclasses->installation->start();
        }
      
        // Start Database
        $spclasses->db->start();
      
        // Check if is connected
        $token = $spclasses->option->get('token');
        
        // SafePassword User pro -> free ( Cancel Payment )
        if($spclasses->protect->get('safepassword-status') == 'cancel') {
            $spclasses->option->add('type', 'free', $spcms['user_id']);
        }
        
        if($token != '') {
          $spcms['page_disabled'] = false;
        }
    }
    
    /*
     * Redirect After Activation
     */
    function wpSafePasswordToConnect(){
        
        if (get_option('wpsafepassword_activation') == 'true') {
            update_option('wpsafepassword_activation', 'false');
            wp_redirect('admin.php?page=spsafepwd-connection');
        }
    }
    
    
    /*
     * Login Errors
     */ 
    function loginErrorsSafePassword($error){
        global $errors;
        global $spcms;
        global $spclasses;
        global $sptext;
        $err_codes = $errors->get_error_codes();
        
        // Token
        $token = $spclasses->option->get('token');
        
        // Check if is connected to SafePassword
        if($token != '') {
        
            if ( in_array( 'incorrect_password', $err_codes )) {

                if($spcms['safepassword']) {
                    $error = '<strong>'.$sptext['sp_error'].'</strong>: '.$sptext['sp_error_incorrect_expired'];
                } else {
                    $error = '<strong>'.$sptext['sp_error'].'</strong>: '.$sptext['p_error_incorrect_expired'];
                }
            }
        }

        return $error;
    }
    
    /*
     * Login
     */ 
    function loginSafePassword($user){
        global $spsafepwd;
        global $spcms;
        global $spclasses;
        global $sptext;
        $username   = $spclasses->protect->post('log');
        $password   = $spclasses->protect->post('pwd');
        $referrer   = $spclasses->protect->server('HTTP_REFERER');
    
        $error = false;
    
        if($username == '' || $password == '') {
            $error = true;
        }
    
        // check that were not on the default login page
        if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $error ) {
            
            // make sure we don't already have a failed login attempt
            if ( !strstr($referrer, '?login=failed') ) {
                // Redirect to the login page and append a querystring of login failed
                wp_redirect( $referrer . '?login=failed' );
            } else {
                wp_redirect( $referrer );
            }
    
            exit;
        } else {
            
            if($username == '') {
                return new WP_Error( 'empty_username', sprintf( __( '<strong>'.$sptext['sp_error'].'</strong>: '.$sptext['sp_error_empty_username'] ) ) );
            }
            
            if (strpos($username, '@') !== false) {
                $temp_user = get_user_by('email', $username);
            } else {
                $temp_user = get_user_by('login', $username);
            }
            
            if($password == '') {
                return new WP_Error( 'empty_password', sprintf( __( '<strong>'.$sptext['sp_error'].'</strong>: '.$sptext['sp_error_empty_password'] ) ) );
            }
        
            
            if(isset($temp_user)
              && !empty($temp_user)) {
                $token = $spclasses->option->get('token');

                // Server
                $server = $spclasses->option->get('server');
                
                // Email
                $email = $spclasses->option->get('email', $temp_user->ID);
                
                // Login with SafePassword
                if($email != ''
                   && $token != '') {
                    $spcms['safepassword'] = true;
                    $user = null;
                    
                    if(isset($server) 
                        && $server != '') {
                        $spcms['api_url'] = str_replace('www', $server, $spcms['api_url']);
                    }

                    // Get Token
                    $api = new $spsafepwd->http([
                      'base_url' => $spcms['api_url'], 
                      'format' => "json",
                      'headers' => ['token' => $token]
                    ]);

                    $result = $api->post("login",
                                        ['email' => $email,
                                         'safepassword' => $password]);

                    if($result->response_status_lines[0] == 'HTTP/1.1 201 Created' || $result->response_status_lines[0] == 'HTTP/1.1 200 OK'
                      || (isset($result->response_status_lines[1]) && ($result->response_status_lines[1] == 'HTTP/1.1 201 Created' || $result->response_status_lines[1] == 'HTTP/1.1 200 OK'))) {
                        $response = $spclasses->protect->data($result->response, 'json');
                        $data     = json_decode($response);

                        // Wrong SafePassword
                        if($data->status == 'error') {
                            return new WP_Error( 'incorrect_password', sprintf( __( '<strong>'.$sptext['sp_error'].'</strong>: '.$sptext['sp_error_username_password'] ), $username ) );
                        } else {
                            // Success Login
                            $user = $temp_user;
                        }
                    } else {
                        return new WP_Error( 'empty_password', sprintf( __( '<strong>'.$sptext['sp_error'].'</strong>: '.$sptext['sp_error_maintenance_mode'] ) ) );
                    }
                } else {
                    // Login with wordpress
                    $spcms['safepassword'] = false;
                }
            } else {
                return new WP_Error( 'invalid_username', sprintf( __( '<strong>'.$sptext['sp_error'].'</strong>: '.$sptext['sp_error_invalid_username'] ) ) );
            }
            
            return $user;
        }
    }
    
    /*
     * Enable / Disable SafePassword
     */
    function useSafePassword($user){ 
        global $spclasses;
        global $spcms;
        global $sptext;
        
        // Token
        $token = $spclasses->option->get('token');
        
        if($user->ID == $spcms['user_id']
          && $token != '') {
            $spclasses->display->view('shortcode');
            $enabledSafePassword = $spclasses->option->get('enable_sp', $spcms['user_id']);
            $account_type = $spclasses->option->get('type', $spcms['user_id']);
            $started_date = $spclasses->option->get('start_date', $spcms['user_id']);
            $billed = $spclasses->option->get('billed', $spcms['user_id']);
        ?>
            <h3><?php _e($sptext['login_with_safepassword'], "blank"); ?></h3>

            <table class="form-table bmsp-register">
                <tr>
                    <th><label for="enable-safepassword"><?php _e($sptext['title']); ?></label></th>
                    <td>
                        <label class="switch">
                          <input class="bmsp-register-btn" type="checkbox" <?php echo $enabledSafePassword == 'true' ? 'checked="checked"':''; ?> data-user-id="<?php _e($user->ID); ?>" data-user-email="<?php _e($user->data->user_email); ?>" data-user-phone="<?php _e(get_user_meta($user->ID,'phone_number',true)); ?>">
                          <span class="slider round"></span>
                        </label>
                        <br />
                        <span class="description safepwd-sp-v2"><?php _e($sptext['enable_safepassword']); ?></span>
                        <br class="safepwd-sp-v2" />
                        <br class="safepwd-sp-v2" />
                        <br class="safepwd-sp-v2" />
                        <div id="bmsp-qrcode" class="safepwd-sp-v2"></div>
                        <br class="safepwd-sp-v2" />
                        <span id="bmsp-qrcode-description" class="safepwd-sp-v2" class="description bmsp-invisible"><?php _e($sptext['sync_with_safepassword_app']); ?></span>
                    </td>
                </tr>
                <?php if($account_type != '') { ?>
                <tr>
                    <th><label for="account-type-safepassword"><?php _e($sptext['account_type_sp']); ?></label></th>
                    <td>
                        <span class="safepassword-account-type" style="text-transform: uppercase;font-weight: bold;"><?php echo $account_type; ?></span>
                        <?php if($account_type == 'free') { ?>
                        <br>
                        <span class="description"><?php _e($sptext['upgrade_to_pro']); ?></span>
                        <?php } ?>
                        
                    </td>
                </tr>
                <?php } ?>
                <?php if($billed != ''
                        && $started_date != ''
                        && $account_type == 'pro') { 
                    $next_date = $billed == 'monthly' ? date('Y-m-d', strtotime('+1 month', strtotime($started_date))):date('Y-m-d', strtotime('+1 year', strtotime($started_date)));
                ?>
                <tr>
                    <th><label for="account-next-date-safepassword"><?php _e($sptext['auto_renew']); ?></label></th>
                    <td>
                        <span class="safepassword-account-next-date"><b><?php echo $next_date; ?></b></span>
                        <br>
                        <span class="description"><?php _e($sptext['account_cancellation']." ".$spcms['support_email']); ?></span>
                    </td>
                </tr>
                <?php } ?>
            </table>
        <?php 
        }
    }
    
    function is_wplogin(){
        global $spclasses;
        $is_login_page = false;

        $ABSPATH_MY = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, ABSPATH);

        // Was wp-login.php or wp-register.php included during this execution?
        if (in_array($ABSPATH_MY . 'wp-login.php', get_included_files()) ||
            in_array($ABSPATH_MY . 'wp-register.php', get_included_files())) {
            $is_login_page = true;
        }

        if ($spclasses->protect->globals('pagenow') === 'wp-login.php') {
            $is_login_page = true;
        }

        if ($spclasses->protect->server('PHP_SELF') == '/wp-login.php') {
            $is_login_page = true;
        }

        return $is_login_page;
    }
    
    function is_wp_profile(){
        global $spclasses;
        $is_profile_page = false;

        $ABSPATH_MY = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, ABSPATH);

        // Was wp-login.php or wp-register.php included during this execution?
        if (in_array($ABSPATH_MY . 'profile.php', get_included_files())) {
            $is_profile_page = true;
        }

        if ($spclasses->protect->globals('pagenow') === 'profile.php') {
            $is_profile_page = true;
        }
        
        if ($spclasses->protect->server('PHP_SELF') == '/wp-admin/profile.php') {
            $is_profile_page = true;
        }

        return $is_profile_page;
    }
    
    /*
     * Get SafePassword button
     */
    function getSafePassword(){
        global $spclasses;
        global $sptext;
        
        // Token
        $token = $spclasses->option->get('token');
        
        // Check if is connected to SafePassword
        if($token != '') {
            $spclasses->display->view('shortcode');
            ?>
            <p style="float:left; width:100%; margin-top:20px; margin-bottom: 20px;"><?php _e($sptext['get_sp_description']); ?></p>
            <p align="center" id="bmsp-get-safepassword" style="float:left; width:100%; margin-bottom: 20px;" class="button button-primary button-large"><?php _e($sptext['get_safepassword']); ?></p><br><br>
        <?php
        }
    }
    
    /*
     *  Autodetect Role & Site url
     */ 
    function role() {
        global $spcms;
        global $spclasses;

        // Wordpress Roles
        if($spcms['type'] == 'wordpress') {
      
            $spcms['website']      = $spclasses->protect->data(get_site_url(), 'url');
            $spcms['website']      = str_replace('http://', '', $spcms['website']);  
            $spcms['website']      = str_replace('https://', '', $spcms['website']);  
            $spcms['website_full'] = $spclasses->protect->data(get_site_url(), 'url');
            
            global $current_user;
            $current_user = wp_get_current_user();
          
            if(!empty($current_user)) {
                $spcms['user_id'] = $current_user->ID;
                
                if($current_user->ID != 0) {
                    $spcms['username'] = $current_user->data->user_login;
                    $spcms['email'] = $current_user->data->user_email;
                }
            
                if( !empty($current_user->roles) ){
                    foreach ($current_user->roles as $key => $value) {

                        if($value == 'administrator'){
                            $spcms['role'] = 'admin';
                        } else if($value == 'author'
                                  || $value == 'editor'){
                            $spcms['role'] = 'owner';
                        } else if($value == 'subscriber'){
                            $spcms['role'] = 'customer';
                        } else {
                            $spcms['role'] = 'guest';
                        }
                    }
                }
            } else {
                if($spclasses->protect->get('ajax_ses') != ''){
                    $user_data = $spclasses->protect->show($spclasses->protect->get('ajax_ses'));
                    $user_data = explode('@@@', $user_data);
                    $spcms['user_id'] = $user_data[0];
                    $spcms['role'] = $user_data[1];
                    $user_info = get_userdata($spcms['user_id']);
                    $spcms['username'] = $user_info->user_login;
                    $spcms['email'] = $user_info->user_email;
                } else if($spclasses->protect->post('ajax_ses') != ''){
                    $user_data = $spclasses->protect->show($spclasses->protect->post('ajax_ses'));
                    $user_data = explode('@@@', $user_data);
                    $spcms['user_id'] = $user_data[0];
                    $spcms['role'] = $user_data[1];
                    $user_info = get_userdata($spcms['user_id']);
                    $spcms['username'] = $user_info->user_login;
                    $spcms['email'] = $user_info->user_email;
                }
            }
        }
    }
  
    function sign($code){
        $code = strtoupper($code);

        $currency_symbols = array(
            'AED' => '&#1583;.&#1573;', // ?
            'AFN' => '&#65;&#102;',
            'ALL' => '&#76;&#101;&#107;',
            'AMD' => '',
            'ANG' => '&#402;',
            'AOA' => '&#75;&#122;', // ?
            'ARS' => '&#36;',
            'AUD' => '&#36;',
            'AWG' => '&#402;',
            'AZN' => '&#1084;&#1072;&#1085;',
            'BAM' => '&#75;&#77;',
            'BBD' => '&#36;',
            'BDT' => '&#2547;', // ?
            'BGN' => '&#1083;&#1074;',
            'BHD' => '.&#1583;.&#1576;', // ?
            'BIF' => '&#70;&#66;&#117;', // ?
            'BMD' => '&#36;',
            'BND' => '&#36;',
            'BOB' => '&#36;&#98;',
            'BRL' => '&#82;&#36;',
            'BSD' => '&#36;',
            'BTN' => '&#78;&#117;&#46;', // ?
            'BWP' => '&#80;',
            'BYR' => '&#112;&#46;',
            'BZD' => '&#66;&#90;&#36;',
            'CAD' => '&#36;',
            'CDF' => '&#70;&#67;',
            'CHF' => '&#67;&#72;&#70;',
            'CLF' => '', // ?
            'CLP' => '&#36;',
            'CNY' => '&#165;',
            'COP' => '&#36;',
            'CRC' => '&#8353;',
            'CUP' => '&#8396;',
            'CVE' => '&#36;', // ?
            'CZK' => '&#75;&#269;',
            'DJF' => '&#70;&#100;&#106;', // ?
            'DKK' => '&#107;&#114;',
            'DOP' => '&#82;&#68;&#36;',
            'DZD' => '&#1583;&#1580;', // ?
            'EGP' => '&#163;',
            'ETB' => '&#66;&#114;',
            'EUR' => '&#8364;',
            'FJD' => '&#36;',
            'FKP' => '&#163;',
            'GBP' => '&#163;',
            'GEL' => '&#4314;', // ?
            'GHS' => '&#162;',
            'GIP' => '&#163;',
            'GMD' => '&#68;', // ?
            'GNF' => '&#70;&#71;', // ?
            'GTQ' => '&#81;',
            'GYD' => '&#36;',
            'HKD' => '&#36;',
            'HNL' => '&#76;',
            'HRK' => '&#107;&#110;',
            'HTG' => '&#71;', // ?
            'HUF' => '&#70;&#116;',
            'IDR' => '&#82;&#112;',
            'ILS' => '&#8362;',
            'INR' => '&#8377;',
            'IQD' => '&#1593;.&#1583;', // ?
            'IRR' => '&#65020;',
            'ISK' => '&#107;&#114;',
            'JEP' => '&#163;',
            'JMD' => '&#74;&#36;',
            'JOD' => '&#74;&#68;', // ?
            'JPY' => '&#165;',
            'KES' => '&#75;&#83;&#104;', // ?
            'KGS' => '&#1083;&#1074;',
            'KHR' => '&#6107;',
            'KMF' => '&#67;&#70;', // ?
            'KPW' => '&#8361;',
            'KRW' => '&#8361;',
            'KWD' => '&#1583;.&#1603;', // ?
            'KYD' => '&#36;',
            'KZT' => '&#1083;&#1074;',
            'LAK' => '&#8365;',
            'LBP' => '&#163;',
            'LKR' => '&#8360;',
            'LRD' => '&#36;',
            'LSL' => '&#76;', // ?
            'LTL' => '&#76;&#116;',
            'LVL' => '&#76;&#115;',
            'LYD' => '&#1604;.&#1583;', // ?
            'MAD' => '&#1583;.&#1605;.', //?
            'MDL' => '&#76;',
            'MGA' => '&#65;&#114;', // ?
            'MKD' => '&#1076;&#1077;&#1085;',
            'MMK' => '&#75;',
            'MNT' => '&#8366;',
            'MOP' => '&#77;&#79;&#80;&#36;', // ?
            'MRO' => '&#85;&#77;', // ?
            'MUR' => '&#8360;', // ?
            'MVR' => '.&#1923;', // ?
            'MWK' => '&#77;&#75;',
            'MXN' => '&#36;',
            'MYR' => '&#82;&#77;',
            'MZN' => '&#77;&#84;',
            'NAD' => '&#36;',
            'NGN' => '&#8358;',
            'NIO' => '&#67;&#36;',
            'NOK' => '&#107;&#114;',
            'NPR' => '&#8360;',
            'NZD' => '&#36;',
            'OMR' => '&#65020;',
            'PAB' => '&#66;&#47;&#46;',
            'PEN' => '&#83;&#47;&#46;',
            'PGK' => '&#75;', // ?
            'PHP' => '&#8369;',
            'PKR' => '&#8360;',
            'PLN' => '&#122;&#322;',
            'PYG' => '&#71;&#115;',
            'QAR' => '&#65020;',
            'RON' => '&#108;&#101;&#105;',
            'RSD' => '&#1044;&#1080;&#1085;&#46;',
            'RUB' => '&#1088;&#1091;&#1073;',
            'RWF' => '&#1585;.&#1587;',
            'SAR' => '&#65020;',
            'SBD' => '&#36;',
            'SCR' => '&#8360;',
            'SDG' => '&#163;', // ?
            'SEK' => '&#107;&#114;',
            'SGD' => '&#36;',
            'SHP' => '&#163;',
            'SLL' => '&#76;&#101;', // ?
            'SOS' => '&#83;',
            'SRD' => '&#36;',
            'STD' => '&#68;&#98;', // ?
            'SVC' => '&#36;',
            'SYP' => '&#163;',
            'SZL' => '&#76;', // ?
            'THB' => '&#3647;',
            'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
            'TMT' => '&#109;',
            'TND' => '&#1583;.&#1578;',
            'TOP' => '&#84;&#36;',
            'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
            'TTD' => '&#36;',
            'TWD' => '&#78;&#84;&#36;',
            'TZS' => '',
            'UAH' => '&#8372;',
            'UGX' => '&#85;&#83;&#104;',
            'USD' => '&#36;',
            'UYU' => '&#36;&#85;',
            'UZS' => '&#1083;&#1074;',
            'VEF' => '&#66;&#115;',
            'VND' => '&#8363;',
            'VUV' => '&#86;&#84;',
            'WST' => '&#87;&#83;&#36;',
            'XAF' => '&#70;&#67;&#70;&#65;',
            'XCD' => '&#36;',
            'XDR' => '',
            'XOF' => '',
            'XPF' => '&#70;',
            'YER' => '&#65020;',
            'ZAR' => '&#82;',
            'ZMK' => '&#90;&#75;', // ?
            'ZWL' => '&#90;&#36;',
          );

        return html_entity_decode($currency_symbols[$code]);

      }
  
    function currencies(){
      $currency_symbols = array(
          'AED' => html_entity_decode('&#1583;.&#1573;'), // ?
          'AFN' => html_entity_decode('&#65;&#102;'),
          'ALL' => html_entity_decode('&#76;&#101;&#107;'),
          'AMD' => html_entity_decode(''),
          'ANG' => html_entity_decode('&#402;'),
          'AOA' => html_entity_decode('&#75;&#122;'), // ?
          'ARS' => html_entity_decode('&#36;'),
          'AUD' => html_entity_decode('&#36;'),
          'AWG' => html_entity_decode('&#402;'),
          'AZN' => html_entity_decode('&#1084;&#1072;&#1085;'),
          'BAM' => html_entity_decode('&#75;&#77;'),
          'BBD' => html_entity_decode('&#36;'),
          'BDT' => html_entity_decode('&#2547;'), // ?
          'BGN' => html_entity_decode('&#1083;&#1074;'),
          'BHD' => html_entity_decode('.&#1583;.&#1576;'), // ?
          'BIF' => html_entity_decode('&#70;&#66;&#117;'), // ?
          'BMD' => html_entity_decode('&#36;'),
          'BND' => html_entity_decode('&#36;'),
          'BOB' => html_entity_decode('&#36;&#98;'),
          'BRL' => html_entity_decode('&#82;&#36;'),
          'BSD' => html_entity_decode('&#36;'),
          'BTN' => html_entity_decode('&#78;&#117;&#46;'), // ?
          'BWP' => html_entity_decode('&#80;'),
          'BYR' => html_entity_decode('&#112;&#46;'),
          'BZD' => html_entity_decode('&#66;&#90;&#36;'),
          'CAD' => html_entity_decode('&#36;'),
          'CDF' => html_entity_decode('&#70;&#67;'),
          'CHF' => html_entity_decode('&#67;&#72;&#70;'),
          'CLF' => html_entity_decode(''), // ?
          'CLP' => html_entity_decode('&#36;'),
          'CNY' => html_entity_decode('&#165;'),
          'COP' => html_entity_decode('&#36;'),
          'CRC' => html_entity_decode('&#8353;'),
          'CUP' => html_entity_decode('&#8396;'),
          'CVE' => html_entity_decode('&#36;'), // ?
          'CZK' => html_entity_decode('&#75;&#269;'),
          'DJF' => html_entity_decode('&#70;&#100;&#106;'), // ?
          'DKK' => html_entity_decode('&#107;&#114;'),
          'DOP' => html_entity_decode('&#82;&#68;&#36;'),
          'DZD' => html_entity_decode('&#1583;&#1580;'), // ?
          'EGP' => html_entity_decode('&#163;'),
          'ETB' => html_entity_decode('&#66;&#114;'),
          'EUR' => html_entity_decode('&#8364;'),
          'FJD' => html_entity_decode('&#36;'),
          'FKP' => html_entity_decode('&#163;'),
          'GBP' => html_entity_decode('&#163;'),
          'GEL' => html_entity_decode('&#4314;'), // ?
          'GHS' => html_entity_decode('&#162;'),
          'GIP' => html_entity_decode('&#163;'),
          'GMD' => html_entity_decode('&#68;'), // ?
          'GNF' => html_entity_decode('&#70;&#71;'), // ?
          'GTQ' => html_entity_decode('&#81;'),
          'GYD' => html_entity_decode('&#36;'),
          'HKD' => html_entity_decode('&#36;'),
          'HNL' => html_entity_decode('&#76;'),
          'HRK' => html_entity_decode('&#107;&#110;'),
          'HTG' => html_entity_decode('&#71;'), // ?
          'HUF' => html_entity_decode('&#70;&#116;'),
          'IDR' => html_entity_decode('&#82;&#112;'),
          'ILS' => html_entity_decode('&#8362;'),
          'INR' => html_entity_decode('&#8377;'),
          'IQD' => html_entity_decode('&#1593;.&#1583;'), // ?
          'IRR' => html_entity_decode('&#65020;'),
          'ISK' => html_entity_decode('&#107;&#114;'),
          'JEP' => html_entity_decode('&#163;'),
          'JMD' => html_entity_decode('&#74;&#36;'),
          'JOD' => html_entity_decode('&#74;&#68;'), // ?
          'JPY' => html_entity_decode('&#165;'),
          'KES' => html_entity_decode('&#75;&#83;&#104;'), // ?
          'KGS' => html_entity_decode('&#1083;&#1074;'),
          'KHR' => html_entity_decode('&#6107;'),
          'KMF' => html_entity_decode('&#67;&#70;'), // ?
          'KPW' => html_entity_decode('&#8361;'),
          'KRW' => html_entity_decode('&#8361;'),
          'KWD' => html_entity_decode('&#1583;.&#1603;'), // ?
          'KYD' => html_entity_decode('&#36;'),
          'KZT' => html_entity_decode('&#1083;&#1074;'),
          'LAK' => html_entity_decode('&#8365;'),
          'LBP' => html_entity_decode('&#163;'),
          'LKR' => html_entity_decode('&#8360;'),
          'LRD' => html_entity_decode('&#36;'),
          'LSL' => html_entity_decode('&#76;'), // ?
          'LTL' => html_entity_decode('&#76;&#116;'),
          'LVL' => html_entity_decode('&#76;&#115;'),
          'LYD' => html_entity_decode('&#1604;.&#1583;'), // ?
          'MAD' => html_entity_decode('&#1583;.&#1605;.'), //?
          'MDL' => html_entity_decode('&#76;'),
          'MGA' => html_entity_decode('&#65;&#114;'), // ?
          'MKD' => html_entity_decode('&#1076;&#1077;&#1085;'),
          'MMK' => html_entity_decode('&#75;'),
          'MNT' => html_entity_decode('&#8366;'),
          'MOP' => html_entity_decode('&#77;&#79;&#80;&#36;'), // ?
          'MRO' => html_entity_decode('&#85;&#77;'), // ?
          'MUR' => html_entity_decode('&#8360;'), // ?
          'MVR' => html_entity_decode('.&#1923;'), // ?
          'MWK' => html_entity_decode('&#77;&#75;'),
          'MXN' => html_entity_decode('&#36;'),
          'MYR' => html_entity_decode('&#82;&#77;'),
          'MZN' => html_entity_decode('&#77;&#84;'),
          'NAD' => html_entity_decode('&#36;'),
          'NGN' => html_entity_decode('&#8358;'),
          'NIO' => html_entity_decode('&#67;&#36;'),
          'NOK' => html_entity_decode('&#107;&#114;'),
          'NPR' => html_entity_decode('&#8360;'),
          'NZD' => html_entity_decode('&#36;'),
          'OMR' => html_entity_decode('&#65020;'),
          'PAB' => html_entity_decode('&#66;&#47;&#46;'),
          'PEN' => html_entity_decode('&#83;&#47;&#46;'),
          'PGK' => html_entity_decode('&#75;'), // ?
          'PHP' => html_entity_decode('&#8369;'),
          'PKR' => html_entity_decode('&#8360;'),
          'PLN' => html_entity_decode('&#122;&#322;'),
          'PYG' => html_entity_decode('&#71;&#115;'),
          'QAR' => html_entity_decode('&#65020;'),
          'RON' => html_entity_decode('&#108;&#101;&#105;'),
          'RSD' => html_entity_decode('&#1044;&#1080;&#1085;&#46;'),
          'RUB' => html_entity_decode('&#1088;&#1091;&#1073;'),
          'RWF' => html_entity_decode('&#1585;.&#1587;'),
          'SAR' => html_entity_decode('&#65020;'),
          'SBD' => html_entity_decode('&#36;'),
          'SCR' => html_entity_decode('&#8360;'),
          'SDG' => html_entity_decode('&#163;'), // ?
          'SEK' => html_entity_decode('&#107;&#114;'),
          'SGD' => html_entity_decode('&#36;'),
          'SHP' => html_entity_decode('&#163;'),
          'SLL' => html_entity_decode('&#76;&#101;'), // ?
          'SOS' => html_entity_decode('&#83;'),
          'SRD' => html_entity_decode('&#36;'),
          'STD' => html_entity_decode('&#68;&#98;'), // ?
          'SVC' => html_entity_decode('&#36;'),
          'SYP' => html_entity_decode('&#163;'),
          'SZL' => html_entity_decode('&#76;'), // ?
          'THB' => html_entity_decode('&#3647;'),
          'TJS' => html_entity_decode('&#84;&#74;&#83;'), // ? TJS (guess)
          'TMT' => html_entity_decode('&#109;'),
          'TND' => html_entity_decode('&#1583;.&#1578;'),
          'TOP' => html_entity_decode('&#84;&#36;'),
          'TRY' => html_entity_decode('&#8356;'), // New Turkey Lira (old symbol used)
          'TTD' => html_entity_decode('&#36;'),
          'TWD' => html_entity_decode('&#78;&#84;&#36;'),
          'TZS' => html_entity_decode(''),
          'UAH' => html_entity_decode('&#8372;'),
          'UGX' => html_entity_decode('&#85;&#83;&#104;'),
          'USD' => html_entity_decode('&#36;'),
          'UYU' => html_entity_decode('&#36;&#85;'),
          'UZS' => html_entity_decode('&#1083;&#1074;'),
          'VEF' => html_entity_decode('&#66;&#115;'),
          'VND' => html_entity_decode('&#8363;'),
          'VUV' => html_entity_decode('&#86;&#84;'),
          'WST' => html_entity_decode('&#87;&#83;&#36;'),
          'XAF' => html_entity_decode('&#70;&#67;&#70;&#65;'),
          'XCD' => html_entity_decode('&#36;'),
          'XDR' => html_entity_decode(''),
          'XOF' => html_entity_decode(''),
          'XPF' => html_entity_decode('&#70;'),
          'YER' => html_entity_decode('&#65020;'),
          'ZAR' => html_entity_decode('&#82;'),
          'ZMK' => html_entity_decode('&#90;&#75;'), // ?
          'ZWL' => html_entity_decode('&#90;&#36;'),
        );

      return $currency_symbols;

    }
  
    function vat_dat(){
        $vat_data = '{"AF":{"country":"Afghanistan","rate":0,"name":"VAT"},"AL":{"country":"Albania","rate":20,"name":"VAT"},"DZ":{"country":"Algeria","rate":19,"name":"VAT"},"AS":{"country":"American Samoa","rate":0,"name":"VAT"},"AD":{"country":"Andora","rate":4.5,"name":"VAT"},"AO":{"country":"Angora","rate":10,"name":"VAT"},"AI":{"country":"Anguilla","rate":0,"name":"VAT"},"AR":{"country":"Argentina","rate":21,"name":"VAT"},"AM":{"country":"Armenia","rate":20,"name":"VAT"},"AW":{"country":"Aruba","rate":1.5,"name":"VAT"},"AU":{"country":"Australia","rate":10,"name":"VAT"},"AT":{"country":"Austria","rate":20,"name":"VAT","eu":"true"},"AZ":{"country":"Azerbaijan","rate":18,"name":"VAT"},"BS":{"country":"Bahamas","rate":20,"name":"VAT"},"BD":{"country":"Bangladesh","rate":15,"name":"VAT"},"BB":{"country":"Barbados","rate":17.5,"name":"VAT"},"BH":{"country":"Bahrain","rate":5,"name":"VAT"},"BY":{"country":"Belarus","rate":20,"name":"VAT"},"BE":{"country":"Belgium","rate":21,"name":"VAT","eu":"true"},"BZ":{"country":"Belize","rate":12.5,"name":"VAT"},"BJ":{"country":"Benin","rate":18,"name":"VAT"},"BM":{"country":"Bermuda","rate":0,"name":"VAT"},"BT":{"country":"Bhutan","rate":0,"name":"VAT"},"BO":{"country":"Bolivia","rate":13,"name":"VAT"},"BA":{"country":"Bosnia and Herzegovina","rate":17,"name":"VAT"},"BW":{"country":"Botswana","rate":12,"name":"VAT"},"BR":{"country":"Brazil","rate":25,"name":"VAT"},"BN":{"country":"Brunei","rate":0,"name":"VAT"},"BG":{"country":"Bulgaria","rate":20,"name":"VAT","eu":"true"},"BF":{"country":"Burkina Faso","rate":18,"name":"VAT"},"BI":{"country":"Burundi","rate":18,"name":"VAT"},"KH":{"country":"Cambodia","rate":10,"name":"VAT"},"CM":{"country":"Cameroon","rate":19.25,"name":"VAT"},"CA":{"country":"Canada","rate":15,"name":"VAT"},"CV":{"country":"Cape Verde","rate":15,"name":"VAT"},"KY":{"country":"Cayman Islands","rate":0,"name":"VAT"},"CF":{"country":"Central African Republic","rate":19,"name":"VAT"},"TD":{"country":"Chad","rate":0,"name":"VAT"},"CL":{"country":"Chile","rate":19,"name":"VAT"},"CN":{"country":"China","rate":17,"name":"VAT"},"CO":{"country":"Colombia","rate":19,"name":"VAT"},"KM":{"country":"Comoros","rate":0,"name":"VAT"},"CK":{"country":"Cook Islands","rate":15,"name":"VAT"},"DRC":{"country":"Democratic Republic of the Congo","rate":0,"name":"VAT"},"CG":{"country":"Congo","rate":0,"name":"VAT"},"CR":{"country":"Costa Rica","rate":13,"name":"VAT"},"HR":{"country":"Croatia","rate":25,"name":"VAT","eu":"true"},"CU":{"country":"Cuba","rate":20,"name":"VAT"},"CW":{"country":"Cura\u00e7ao","rate":0,"name":"VAT"},"CY":{"country":"Cyprus","rate":19,"name":"VAT","eu":"true"},"CZ":{"country":"Czech Republic","rate":21,"name":"VAT","eu":"true"},"DK":{"country":"Denmark","rate":25,"name":"VAT","eu":"true"},"DJ":{"country":"Djibouti","rate":0,"name":"VAT"},"DM":{"country":"Dominica","rate":0,"name":"VAT"},"DO":{"country":"Dominican Republic","rate":18,"name":"VAT"},"TP":{"country":"Timor-Leste","rate":0,"name":"VAT"},"EC":{"country":"Ecuador","rate":12,"name":"VAT"},"EG":{"country":"Egypt","rate":14,"name":"VAT"},"SV":{"country":"El Salvador","rate":13,"name":"VAT"},"GQ":{"country":"Equatorial Guinea","rate":0,"name":"VAT"},"ER":{"country":"Eritrea","rate":0,"name":"VAT"},"EE":{"country":"Estonia","rate":20,"name":"VAT","eu":"true"},"ET":{"country":"Ethiopia","rate":0,"name":"VAT"},"FK":{"country":"Falkland Islands","rate":0,"name":"VAT"},"fsm":{"country":"F.S. Micronesia","rate":0,"name":"VAT"},"FJ":{"country":"Fiji","rate":9,"name":"VAT"},"FI":{"country":"Finland","rate":24,"name":"VAT","eu":"true"},"FR":{"country":"France","rate":20,"name":"VAT","eu":"true"},"GA":{"country":"Gabon","rate":18,"name":"VAT"},"GM":{"country":"Gambia","rate":0,"name":"VAT"},"DE":{"country":"Germany","rate":19,"name":"VAT","eu":"true"},"GE":{"country":"Georgia","rate":18,"name":"VAT"},"GH":{"country":"Ghana","rate":0,"name":"VAT"},"GI":{"country":"Gibraltar","rate":0,"name":"VAT"},"GR":{"country":"Greece","rate":24,"name":"VAT","eu":"true"},"GD":{"country":"Grenada","rate":0,"name":"VAT"},"GT":{"country":"Guatemala","rate":12,"name":"VAT"},"GN":{"country":"Guinea","rate":0,"name":"VAT"},"GW":{"country":"Guinea-Bissau","rate":0,"name":"VAT"},"GY":{"country":"Guyana","rate":16,"name":"VAT"},"GG":{"country":"Guernsey","rate":0,"name":"VAT"},"HT":{"country":"Haiti","rate":0,"name":"VAT"},"HN":{"country":"Honduras","rate":0,"name":"VAT"},"HKO":{"country":"Hong Kong","rate":0,"name":"VAT"},"HU":{"country":"Hungary","rate":27,"name":"VAT","eu":"true"},"IS":{"country":"Iceland","rate":24,"name":"VAT"},"IN":{"country":"India","rate":28,"name":"VAT"},"ID":{"country":"Indonesia","rate":10,"name":"VAT"},"IR":{"country":"Iran","rate":9,"name":"VAT"},"IQ":{"country":"Iraq","rate":0,"name":"VAT"},"IE":{"country":"Ireland","rate":13.5,"name":"VAT","eu":"true"},"IM":{"country":"Isle of Man","rate":20,"name":"VAT"},"IL":{"country":"Israel","rate":17,"name":"VAT"},"IT":{"country":"Italy","rate":22,"name":"VAT","eu":"true"},"CI":{"country":"Ivory Coast","rate":0,"name":"VAT"},"JM":{"country":"Jamaica","rate":0,"name":"VAT"},"JP":{"country":"Japan","rate":8,"name":"VAT"},"JE":{"country":"Jersey","rate":5,"name":"VAT"},"JO":{"country":"Jordan","rate":16,"name":"GST"},"KZ":{"country":"Kazakhstan","rate":12,"name":"VAT"},"KE":{"country":"Kenya","rate":16,"name":"VAT"},"KI":{"country":"Kiribati","rate":0,"name":"VAT"},"KW":{"country":"Kuwait","rate":0,"name":"VAT"},"KR":{"country":"South Korea","rate":10,"name":"VAT"},"KP":{"country":"North Korea","rate":4,"name":"VAT"},"KG":{"country":"Kyrgyzstan","rate":0,"name":"VAT"},"LA":{"country":"Laos","rate":0,"name":"VAT"},"LV":{"country":"Latvia","rate":21,"name":"VAT","eu":"true"},"LB":{"country":"Lebanon","rate":10,"name":"VAT"},"LS":{"country":"Lesotho","rate":0,"name":"VAT"},"LR":{"country":"Liberia","rate":0,"name":"VAT"},"LY":{"country":"Libya","rate":0,"name":"VAT"},"LI":{"country":"Liechtenstein","rate":8,"name":"VAT"},"LT":{"country":" Lithuania","rate":21,"name":"VAT","eu":"true"},"LU":{"country":"Luxembourg","rate":17,"name":"VAT","eu":"true"},"MO":{"country":"Macau","rate":0,"name":"VAT"},"MK":{"country":"Macedonia","rate":18,"name":"VAT"},"MG":{"country":"Madagascar","rate":0,"name":"VAT"},"MW":{"country":"Malawi","rate":0,"name":"VAT"},"MY":{"country":"Malaysia","rate":0,"name":"GST"},"MV":{"country":"Maldives","rate":6,"name":"VAT"},"ML":{"country":"Mali","rate":0,"name":"VAT"},"MT":{"country":"Malta","rate":18,"name":"VAT","eu":"true"},"MH":{"country":"Marshall Islands","rate":4,"name":"VAT"},"MR":{"country":"Mauritania","rate":0,"name":"VAT"},"MU":{"country":"Mauritius","rate":15,"name":"VAT"},"MX":{"country":"Mexico","rate":16,"name":"VAT"},"MD":{"country":"Moldova ","rate":20,"name":"VAT"},"MC":{"country":"Monaco","rate":19.6,"name":"VAT"},"MN":{"country":"Mongolia","rate":10,"name":"VAT"},"ME":{"country":"Montenegro","rate":19,"name":"VAT"},"MS":{"country":"Montserrat","rate":0,"name":"VAT"},"MA":{"country":"Morocco","rate":0,"name":"VAT"},"MZ":{"country":"Mozambique","rate":0,"name":"VAT"},"MM":{"country":"Myanmar","rate":0,"name":"VAT"},"NA":{"country":"Namibia","rate":0,"name":"VAT"},"NR":{"country":"Nauru","rate":0,"name":"VAT"},"NP":{"country":"Nepal","rate":13,"name":"VAT"},"NL":{"country":"Netherlands","rate":21,"name":"VAT","eu":"true"},"NZ":{"country":"New Zealand","rate":0,"name":"GST"},"NC":{"country":"New Caledonia","rate":0,"name":"VAT"},"NI":{"country":"Nicaragua","rate":0,"name":"VAT"},"NE":{"country":"Niger","rate":0,"name":"VAT"},"NG":{"country":"Nigeria","rate":5,"name":"VAT"},"NU":{"country":"Niue","rate":12.5,"name":"VAT"},"NF":{"country":"Norfolk Island","rate":0,"name":"VAT"},"NO":{"country":"Norway","rate":10,"name":"VAT"},"OM":{"country":"Oman","rate":5,"name":"VAT"},"PK":{"country":"Pakistan","rate":0,"name":"VAT"},"PW":{"country":"Palau","rate":0,"name":"VAT"},"PS":{"country":"Palestine","rate":14.5,"name":"VAT"},"PA":{"country":"Panama","rate":0,"name":"VAT"},"PG":{"country":"Papua New Guinea","rate":0,"name":"VAT"},"PY":{"country":"Paraguay","rate":10,"name":"VAT"},"PE":{"country":"Peru","rate":18,"name":"VAT"},"PH":{"country":"Philippines","rate":12,"name":"VAT"},"PN":{"country":"Pitcairn Islands","rate":0,"name":"VAT"},"PL":{"country":"Poland","rate":23,"name":"VAT","eu":"true"},"PT":{"country":"Portugal","rate":23,"name":"VAT","eu":"true"},"PR":{"country":"Puerto Rico","rate":11.5,"name":"VAT"},"QA":{"country":"Qatar","rate":0,"name":"VAT"},"RO":{"country":"Romania","rate":19,"name":"VAT","eu":"true"},"Ru":{"country":"Russia","rate":18,"name":"VAT"},"RW":{"country":"Rwanda","rate":0,"name":"VAT"},"KN":{"country":"Saint Kitts and Nevis","rate":0,"name":"VAT"},"LC":{"country":"Saint Lucia","rate":0,"name":"VAT"},"PM":{"country":"Saint Pierre and Miquelon","rate":0,"name":"VAT"},"VC":{"country":"Saint Vincent and the Grenadines","rate":0,"name":"VAT"},"WS":{"country":"Samoa","rate":0,"name":"VAT"},"SM":{"country":"San Marino","rate":0,"name":"VAT"},"ST":{"country":"S\u00e3o Tom\u00e9 and Pr\u00edncipe","rate":0,"name":"VAT"},"SAARK":{"country":"Sark","rate":0,"name":"VAT"},"SA":{"country":"Saudi Arabia","rate":5,"name":"VAT"},"SN":{"country":"Senegal","rate":20,"name":"VAT"},"RS":{"country":"Serbia","rate":20,"name":"VAT"},"SC":{"country":"Seychelles","rate":0,"name":"VAT"},"SL":{"country":"Sierra Leone","rate":0,"name":"VAT"},"SG":{"country":"Singapore","rate":7,"name":"GST"},"SX":{"country":"Sint Maarten","rate":0,"name":"VAT"},"SK":{"country":"Slovakia","rate":20,"name":"VAT","eu":"true"},"SI":{"country":"Slovenia","rate":22,"name":"VAT","eu":"true"},"SB":{"country":"Solomon Islands","rate":0,"name":"VAT"},"SO":{"country":"Somalia","rate":0,"name":"VAT"},"ZA":{"country":"South Africa","rate":15,"name":"VAT"},"SD":{"country":"South Sudan","rate":0,"name":"VAT"},"ES":{"country":"Spain","rate":21,"name":"VAT","eu":"true"},"LK":{"country":"Sri Lanka","rate":12,"name":"VAT"},"SU":{"country":"Sudan","rate":0,"name":"VAT"},"SR":{"country":"Suriname","rate":0,"name":"VAT"},"SZ":{"country":"Swaziland","rate":14,"name":"VAT"},"SE":{"country":"Sweden","rate":25,"name":"VAT","eu":"true"},"CH":{"country":"Switzerland","rate":7.7,"name":"VAT"},"SY":{"country":"Syria","rate":0,"name":"VAT"},"TW":{"country":"Taiwan","rate":5,"name":"VAT"},"TJ":{"country":"Tajikistan","rate":0,"name":"VAT"},"TZ":{"country":"Tanzania","rate":18,"name":"VAT"},"TH":{"country":"Thailand","rate":7,"name":"VAT"},"TG":{"country":"Togo","rate":0,"name":"VAT"},"TK":{"country":"Tokelau","rate":0,"name":"VAT"},"TO":{"country":"Tonga","rate":0,"name":"VAT"},"TT":{"country":"Trinidad and Tobago","rate":12.5,"name":"VAT"},"TN":{"country":"Tunisia","rate":18,"name":"VAT"},"TR":{"country":"Turkey","rate":18,"name":"VAT"},"TM":{"country":"Turkmenistan","rate":0,"name":"VAT"},"TC":{"country":"Turks and Caicos Islands","rate":0,"name":"VAT"},"TV":{"country":"Tuvalu","rate":0,"name":"VAT"},"UG":{"country":"Uganda","rate":0,"name":"VAT"},"UA":{"country":"Ukraine","rate":0,"name":"VAT"},"AE":{"country":"United Arab Emirates","rate":0,"name":"VAT"},"UK":{"country":"United Kingdom","rate":20,"name":"VAT","eu":"true"},"US":{"country":"United States of America","state":{"AL":{"state":"Alabama","rate":4,"name":"GST"},"Ak":{"state":"Alaska","rate":0,"name":"GST"},"Az":{"state":"Arizona","rate":4.54,"name":"GST"},"AR":{"state":"Arkansas","rate":6.5,"name":"GST"},"CA":{"state":"California","rate":7.25,"name":"GST"},"CO":{"state":"Colorado","rate":2.9,"name":"GST"},"CT":{"state":"Connecticut","rate":6.35,"name":"GST"},"DE":{"state":"Delaware","rate":0,"name":"GST"},"DC":{"state":"District Of Columbia","rate":5.75,"name":"GST"},"FL":{"state":"Florida","rate":6,"name":"GST"},"GA":{"state":"Georgia","rate":4,"name":"GST"},"HI":{"state":"Hawaii","rate":4,"name":"GST"},"ID":{"state":"Idaho","rate":6,"name":"GST"},"IL":{"state":"Ilinois","rate":6.25,"name":"GST"},"IN":{"state":"Indiana","rate":7,"name":"GST"},"IA":{"state":"Iowa","rate":6,"name":"GST"},"KS":{"state":"Kansas","rate":6.5,"name":"GST"},"KY":{"state":"Kentucky","rate":6,"name":"GST"},"LA":{"state":"Louisiana","rate":5,"name":"GST"},"ME":{"state":"Maine","rate":5.5,"name":"GST"},"MD":{"state":"Maryland","rate":6,"name":"GST"},"MA":{"state":"Massachusetts","rate":6.25,"name":"GST"},"MI":{"state":"Michigan","rate":6,"name":"GST"},"MN":{"state":"Minnesota","rate":6.875,"name":"GST"},"MS":{"state":"Mississippi","rate":7,"name":"GST"},"MO":{"state":"Missouri","rate":4.225,"name":"GST"},"MT":{"state":"Montana","rate":0,"name":"GST"},"NE":{"state":"Nebraska","rate":5.5,"name":"GST"},"NV":{"state":"Nevada","rate":6.85,"name":"GST"},"NH":{"state":"New Hampshire","rate":0,"name":"GST"},"NJ":{"state":"New Jersey","rate":6.63,"name":"GST"},"NM":{"state":"New Mexico","rate":5.125,"name":"GST"},"NY":{"state":"New York","rate":4,"name":"GST"},"NC":{"state":"North Carolina","rate":4.75,"name":"GST"},"ND":{"state":"North Dakota","rate":5,"name":"GST"},"OH":{"state":"Ohio","rate":5.75,"name":"GST"},"OK":{"state":"Oklahoma","rate":4.5,"name":"GST"},"OR":{"state":"Oregon","rate":0,"name":"GST"},"PA":{"state":"Pennsylvania","rate":6,"name":"GST"},"RI":{"state":"Rhode Island","rate":7,"name":"GST"},"SC":{"state":"South Carolina","rate":6,"name":"GST"},"SD":{"state":"South Dakota","rate":4.5,"name":"GST"},"TN":{"state":"Tennessee","rate":7,"name":"GST"},"TX":{"state":"Texas","rate":6.25,"name":"GST"},"UT":{"state":"Utah","rate":5.95,"name":"GST"},"VT":{"state":"Vermont","rate":6,"name":"GST"},"VA":{"state":"Virginia","rate":5.3,"name":"GST"},"WA":{"state":"Washington","rate":6.5,"name":"GST"},"WV":{"state":"West Virginia","rate":6,"name":"GST"},"WI":{"state":"Wisconsin","rate":5,"name":"GST"},"WY":{"state":"Wyoming","rate":4,"name":"GST"}},"rate":23,"name":"GST"},"UY":{"country":"Uruguay","rate":22,"name":"VAT"},"UZ":{"country":"Uzbekistan","rate":20,"name":"VAT"},"VU":{"country":"Vanuatu","rate":0,"name":"VAT"},"VE":{"country":"Venezuela","rate":9,"name":"VAT"},"VN":{"country":"Vietnam","rate":10,"name":"VAT"},"VG":{"country":"British Virgin Islands","rate":0,"name":"VAT"},"VI":{"country":"U.S. Virgin Islands","rate":0,"name":"VAT"},"YE":{"country":"Yemen","rate":2,"name":"VAT"},"ZM":{"country":"Zambia","rate":16,"name":"VAT"},"ZW":{"country":"Zimbabwe","rate":0,"name":"VAT"}}';
        $vat_data = json_decode($vat_data);
        $vat_data = (array)$vat_data;
        $vat_data['US'] = (array)$vat_data['US'];  
      
        return $vat_data;
    }
	
    function countries(){
        return array('AF'=>'AFGHANISTAN',
                     'AL'=>'ALBANIA',
                    'DZ'=>'ALGERIA',
                    'AS'=>'AMERICAN SAMOA',
                    'AD'=>'ANDORRA',
                    'AO'=>'ANGOLA',
                    'AI'=>'ANGUILLA',
                    'AQ'=>'ANTARCTICA',
                    'AG'=>'ANTIGUA AND BARBUDA',
                    'AR'=>'ARGENTINA',
                    'AM'=>'ARMENIA',
                    'AW'=>'ARUBA',
                    'AC'=>'ASCENSION ISLAND',
                    'AU'=>'AUSTRALIA',
                    'AT'=>'AUSTRIA',
                    'AZ'=>'AZERBAIJAN',
                    'BS'=>'BAHAMAS',
                    'BH'=>'BAHRAIN',
                    'BD'=>'BANGLADESH',
                    'BB'=>'BARBADOS',
                    'BY'=>'BELARUS',
                    'BE'=>'BELGIUM',
                    'BZ'=>'BELIZE',
                    'BJ'=>'BENIN',
                    'BM'=>'BERMUDA',
                    'BT'=>'BHUTAN',
                    'BO'=>'BOLIVIA',
                    'BA'=>'BOSNIA AND HERZEGOWINA',
                    'BW'=>'BOTSWANA',
                    'BV'=>'BOUVET ISLAND',
                    'BR'=>'BRAZIL',
                    'IO'=>'BRITISH INDIAN OCEAN TERRITORY',
                    'BN'=>'BRUNEI DARUSSALAM',
                    'BG'=>'BULGARIA',
                    'BF'=>'BURKINA FASO',
                    'BI'=>'BURUNDI',
                    'KH'=>'CAMBODIA',
                    'CM'=>'CAMEROON',
                    'CA'=>'CANADA',
                    'CV'=>'CAPE VERDE',
                    'KY'=>'CAYMAN ISLANDS',
                    'CF'=>'CENTRAL AFRICAN REPUBLIC',
                    'TD'=>'CHAD',
                    'CL'=>'CHILE',
                    'CN'=>'CHINA',
                    'CX'=>'CHRISTMAS ISLAND',
                    'CC'=>'COCOS (KEELING) ISLANDS',
                    'CO'=>'COLOMBIA',
                    'KM'=>'COMOROS',
                    'CD'=>'CONGO THE DEMOCRATIC REPUBLIC OF THE',
                    'CG'=>'CONGO',
                    'CK'=>'COOK ISLANDS',
                    'CR'=>'COSTA RICA',
                    'CI'=>'COTE D\'IVOIRE',
                    'HR'=>'CROATIA',
                    'CU'=>'CUBA',
                    'CY'=>'CYPRUS',
                    'CZ'=>'CZECH REPUBLIC',
                    'DK'=>'DENMARK',
                    'DJ'=>'DJIBOUTI',
                    'DM'=>'DOMINICA',
                    'DO'=>'DOMINICAN REPUBLIC',
                    'TP'=>'EAST TIMOR',
                    'EC'=>'ECUADOR',
                    'EG'=>'EGYPT',
                    'SV'=>'EL SALVADOR',
                    'GQ'=>'EQUATORIAL GUINEA',
                    'ER'=>'ERITREA',
                    'EE'=>'ESTONIA',
                    'ET'=>'ETHIOPIA',
                    'EU'=>'EUROPEAN UNION',
                    'FK'=>'FALKLAND ISLANDS (MALVINAS)',
                    'FO'=>'FAROE ISLANDS',
                    'FJ'=>'FIJI',
                    'FI'=>'FINLAND',
                    'FX'=>'FRANCE METRO',
                    'FR'=>'FRANCE',
                    'GF'=>'FRENCH GUIANA',
                    'PF'=>'FRENCH POLYNESIA',
                    'TF'=>'FRENCH SOUTHERN TERRITORIES',
                    'GA'=>'GABON',
                    'GM'=>'GAMBIA',
                    'GE'=>'GEORGIA',
                    'DE'=>'GERMANY',
                    'GH'=>'GHANA',
                    'GI'=>'GIBRALTAR',
                    'GR'=>'GREECE',
                    'GL'=>'GREENLAND',
                    'GD'=>'GRENADA',
                    'GP'=>'GUADELOUPE',
                    'GU'=>'GUAM',
                    'GT'=>'GUATEMALA',
                    'GG'=>'GUERNSEY',
                    'GN'=>'GUINEA',
                    'GW'=>'GUINEA-BISSAU',
                    'GY'=>'GUYANA',
                    'HT'=>'HAITI',
                    'HM'=>'HEARD AND MC DONALD ISLANDS',
                    'VA'=>'HOLY SEE (VATICAN CITY STATE)',
                    'HN'=>'HONDURAS',
                    'HK'=>'HONG KONG',
                    'HU'=>'HUNGARY',
                    'IS'=>'ICELAND',
                    'IN'=>'INDIA',
                    'ID'=>'INDONESIA',
                    'IR'=>'IRAN (ISLAMIC REPUBLIC OF)',
                    'IQ'=>'IRAQ',
                    'IE'=>'IRELAND',
                    'IM'=>'ISLE OF MAN',
                    'IL'=>'ISRAEL',
                    'IT'=>'ITALY',
                    'JM'=>'JAMAICA',
                    'JP'=>'JAPAN',
                    'JE'=>'JERSEY',
                    'JO'=>'JORDAN',
                    'KZ'=>'KAZAKHSTAN',
                    'KE'=>'KENYA',
                    'KI'=>'KIRIBATI',
                    'KP'=>'KOREA DEMOCRATIC PEOPLE\'S REPUBLIC OF',
                    'KR'=>'KOREA REPUBLIC OF',
                    'KW'=>'KUWAIT',
                    'KG'=>'KYRGYZSTAN',
                    'LA'=>'LAO PEOPLE\'S DEMOCRATIC REPUBLIC',
                    'LV'=>'LATVIA',
                    'LB'=>'LEBANON',
                    'LS'=>'LESOTHO',
                    'LR'=>'LIBERIA',
                    'LY'=>'LIBYAN ARAB JAMAHIRIYA',
                    'LI'=>'LIECHTENSTEIN',
                    'LT'=>'LITHUANIA',
                    'LU'=>'LUXEMBOURG',
                    'MO'=>'MACAU',
                    'MK'=>'MACEDONIA',
                    'MG'=>'MADAGASCAR',
                    'MW'=>'MALAWI',
                    'MY'=>'MALAYSIA',
                    'MV'=>'MALDIVES',
                    'ML'=>'MALI',
                    'MT'=>'MALTA',
                    'MH'=>'MARSHALL ISLANDS',
                    'MQ'=>'MARTINIQUE',
                    'MR'=>'MAURITANIA',
                    'MU'=>'MAURITIUS',
                    'YT'=>'MAYOTTE',
                    'MX'=>'MEXICO',
                    'FM'=>'MICRONESIA FEDERATED STATES OF',
                    'MD'=>'MOLDOVA REPUBLIC OF',
                    'MC'=>'MONACO',
                    'MN'=>'MONGOLIA',
                    'MS'=>'MONTSERRAT',
                    'MA'=>'MOROCCO',
                    'MZ'=>'MOZAMBIQUE',
                    'MM'=>'MYANMAR',
                    'ME'=>'Montenegro',
                    'NA'=>'NAMIBIA',
                    'NR'=>'NAURU',
                    'NP'=>'NEPAL',
                    'AN'=>'NETHERLANDS ANTILLES',
                    'NL'=>'NETHERLANDS',
                    'NC'=>'NEW CALEDONIA',
                    'NZ'=>'NEW ZEALAND',
                    'NI'=>'NICARAGUA',
                    'NE'=>'NIGER',
                    'NG'=>'NIGERIA',
                    'NU'=>'NIUE',
                    'AP'=>'NON-SPEC ASIA PAS LOCATION',
                    'NF'=>'NORFOLK ISLAND',
                    'MP'=>'NORTHERN MARIANA ISLANDS',
                    'NO'=>'NORWAY',
                    'OM'=>'OMAN',
                    'PK'=>'PAKISTAN',
                    'PW'=>'PALAU',
                    'PS'=>'PALESTINIAN TERRITORY OCCUPIED',
                    'PA'=>'PANAMA',
                    'PG'=>'PAPUA NEW GUINEA',
                    'PY'=>'PARAGUAY',
                    'PE'=>'PERU',
                    'PH'=>'PHILIPPINES',
                    'PN'=>'PITCAIRN',
                    'PL'=>'POLAND',
                    'PT'=>'PORTUGAL',
                    'PR'=>'PUERTO RICO',
                    'QA'=>'QATAR',
                    'ZZ'=>'RESERVED',
                    'RE'=>'REUNION',
                    'RO'=>'ROMANIA',
                    'RU'=>'RUSSIAN FEDERATION',
                    'RW'=>'RWANDA',
                    'KN'=>'SAINT KITTS AND NEVIS',
                    'LC'=>'SAINT LUCIA',
                    'VC'=>'SAINT VINCENT AND THE GRENADINES',
                    'WS'=>'SAMOA',
                    'SM'=>'SAN MARINO',
                    'ST'=>'SAO TOME AND PRINCIPE',
                    'SA'=>'SAUDI ARABIA',
                    'SN'=>'SENEGAL',
                    'SC'=>'SEYCHELLES',
                    'SL'=>'SIERRA LEONE',
                    'SG'=>'SINGAPORE',
                    'SK'=>'SLOVAKIA (SLOVAK REPUBLIC)',
                    'SI'=>'SLOVENIA',
                    'SB'=>'SOLOMON ISLANDS',
                    'SO'=>'SOMALIA',
                    'ZA'=>'SOUTH AFRICA',
                    'GS'=>'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
                    'ES'=>'SPAIN',
                    'LK'=>'SRI LANKA',
                    'SH'=>'ST. HELENA',
                    'PM'=>'ST. PIERRE AND MIQUELON',
                    'SD'=>'SUDAN',
                    'SR'=>'SURINAME',
                    'SJ'=>'SVALBARD AND JAN MAYEN ISLANDS',
                    'SZ'=>'SWAZILAND',
                    'SE'=>'SWEDEN',
                    'CH'=>'SWITZERLAND',
                    'SY'=>'SYRIAN ARAB REPUBLIC',
                    'CS'=>'SERBIA AND MONTENEGRO',
                    'YU'=>'SERBIA AND MONTENEGRO',
                    'RS'=>'Serbia',
                    'TW'=>'TAIWAN; REPUBLIC OF CHINA (ROC)',
                    'TJ'=>'TAJIKISTAN',
                    'TZ'=>'TANZANIA UNITED REPUBLIC OF',
                    'TH'=>'THAILAND',
                    'TL'=>'TIMOR-LESTE',
                    'TG'=>'TOGO',
                    'TK'=>'TOKELAU',
                    'TO'=>'TONGA',
                    'TT'=>'TRINIDAD AND TOBAGO',
                    'TN'=>'TUNISIA',
                    'TR'=>'TURKEY',
                    'TM'=>'TURKMENISTAN',
                    'TC'=>'TURKS AND CAICOS ISLANDS',
                    'TV'=>'TUVALU',
                    'UG'=>'UGANDA',
                    'UA'=>'UKRAINE',
                    'AE'=>'UNITED ARAB EMIRATES',
                    'GB'=>'UNITED KINGDOM',
                    'UK'=>'UNITED KINGDOM',
                    'UM'=>'UNITED STATES MINOR OUTLYING ISLANDS',
                    'US'=>'UNITED STATES',
                    'UY'=>'URUGUAY',
                    'UZ'=>'UZBEKISTAN',
                    'VU'=>'VANUATU',
                    'VE'=>'VENEZUELA',
                    'VN'=>'VIET NAM',
                    'VG'=>'VIRGIN ISLANDS (BRITISH)',
                    'VI'=>'VIRGIN ISLANDS (U.S.)',
                    'WF'=>'WALLIS AND FUTUNA ISLANDS',
                    'EH'=>'WESTERN SAHARA',
                    'YE'=>'YEMEN',
                    'ZM'=>'ZAMBIA',
                    'ZW'=>'ZIMBABWE',
                    'AX'=>'ALAND ISLANDS',
                    'MF'=>'SAINT MARTIN'
                    );
    }	
    
    function phonecode($phone){
        $phonecodes = '{"BD": "880", "BE": "32", "BF": "226", "BG": "359", "BA": "387", "BB": "+1-246", "WF": "681", "BL": "590", "BM": "+1-441", "BN": "673", "BO": "591", "BH": "973", "BI": "257", "BJ": "229", "BT": "975", "JM": "+1-876", "BV": "", "BW": "267", "WS": "685", "BQ": "599", "BR": "55", "BS": "+1-242", "JE": "+44-1534", "BY": "375", "BZ": "501", "RU": "7", "RW": "250", "RS": "381", "TL": "670", "RE": "262", "TM": "993", "TJ": "992", "RO": "40", "TK": "690", "GW": "245", "GU": "+1-671", "GT": "502", "GS": "", "GR": "30", "GQ": "240", "GP": "590", "JP": "81", "GY": "592", "GG": "+44-1481", "GF": "594", "GE": "995", "GD": "+1-473", "GB": "44", "GA": "241", "SV": "503", "GN": "224", "GM": "220", "GL": "299", "GI": "350", "GH": "233", "OM": "968", "TN": "216", "JO": "962", "HR": "385", "HT": "509", "HU": "36", "HK": "852", "HN": "504", "HM": " ", "VE": "58", "PR": "+1-787 and 1-939", "PS": "970", "PW": "680", "PT": "351", "SJ": "47", "PY": "595", "IQ": "964", "PA": "507", "PF": "689", "PG": "675", "PE": "51", "PK": "92", "PH": "63", "PN": "870", "PL": "48", "PM": "508", "ZM": "260", "EH": "212", "EE": "372", "EG": "20", "ZA": "27", "EC": "593", "IT": "39", "VN": "84", "SB": "677", "ET": "251", "SO": "252", "ZW": "263", "SA": "966", "ES": "34", "ER": "291", "ME": "382", "MD": "373", "MG": "261", "MF": "590", "MA": "212", "MC": "377", "UZ": "998", "MM": "95", "ML": "223", "MO": "853", "MN": "976", "MH": "692", "MK": "389", "MU": "230", "MT": "356", "MW": "265", "MV": "960", "MQ": "596", "MP": "+1-670", "MS": "+1-664", "MR": "222", "IM": "+44-1624", "UG": "256", "TZ": "255", "MY": "60", "MX": "52", "IL": "972", "FR": "33", "IO": "246", "SH": "290", "FI": "358", "FJ": "679", "FK": "500", "FM": "691", "FO": "298", "NI": "505", "NL": "31", "NO": "47", "NA": "264", "VU": "678", "NC": "687", "NE": "227", "NF": "672", "NG": "234", "NZ": "64", "NP": "977", "NR": "674", "NU": "683", "CK": "682", "XK": "", "CI": "225", "CH": "41", "CO": "57", "CN": "86", "CM": "237", "CL": "56", "CC": "61", "CA": "1", "CG": "242", "CF": "236", "CD": "243", "CZ": "420", "CY": "357", "CX": "61", "CR": "506", "CW": "599", "CV": "238", "CU": "53", "SZ": "268", "SY": "963", "SX": "599", "KG": "996", "KE": "254", "SS": "211", "SR": "597", "KI": "686", "KH": "855", "KN": "+1-869", "KM": "269", "ST": "239", "SK": "421", "KR": "82", "SI": "386", "KP": "850", "KW": "965", "SN": "221", "SM": "378", "SL": "232", "SC": "248", "KZ": "7", "KY": "+1-345", "SG": "65", "SE": "46", "SD": "249", "DO": "+1-809 and 1-829", "DM": "+1-767", "DJ": "253", "DK": "45", "VG": "+1-284", "DE": "49", "YE": "967", "DZ": "213", "US": "1", "UY": "598", "YT": "262", "UM": "1", "LB": "961", "LC": "+1-758", "LA": "856", "TV": "688", "TW": "886", "TT": "+1-868", "TR": "90", "LK": "94", "LI": "423", "LV": "371", "TO": "676", "LT": "370", "LU": "352", "LR": "231", "LS": "266", "TH": "66", "TF": "", "TG": "228", "TD": "235", "TC": "+1-649", "LY": "218", "VA": "379", "VC": "+1-784", "AE": "971", "AD": "376", "AG": "+1-268", "AF": "93", "AI": "+1-264", "VI": "+1-340", "IS": "354", "IR": "98", "AM": "374", "AL": "355", "AO": "244", "AQ": "", "AS": "+1-684", "AR": "54", "AU": "61", "AT": "43", "AW": "297", "IN": "91", "AX": "+358-18", "AZ": "994", "IE": "353", "ID": "62", "UA": "380", "QA": "974", "MZ": "258"}';
        $phonecodes = json_decode($phonecodes);
        $phonecodes = (array)$phonecodes;

        $phone_data = explode('@',$phone);
        $phone = str_replace($phone_data[0].'@', $phone_codes[$phone_data[0]], $phone);
        
        return $phone;
    }
	
	function get_ip_address() {
        global $spclasses;
        // check for shared internet/ISP IP
        if (!empty($spclasses->protect->server('HTTP_CLIENT_IP')) && $this->validate_ip($spclasses->protect->server('HTTP_CLIENT_IP'))) {
            return $spclasses->protect->server('HTTP_CLIENT_IP');
        }

        // check for IPs passing through proxies
        if (!empty($spclasses->protect->server('HTTP_X_FORWARDED_FOR'))) {
            // check if multiple ips exist in var
            if (strpos($spclasses->protect->server('HTTP_X_FORWARDED_FOR'), ',') !== false) {
                $iplist = explode(',', $spclasses->protect->server('HTTP_X_FORWARDED_FOR'));
                foreach ($iplist as $ip) {
                    if ($this->validate_ip($ip))
                        return $ip;
                }
            } else {
                if ($this->validate_ip($spclasses->protect->server('HTTP_X_FORWARDED_FOR')))
                    return $spclasses->protect->server('HTTP_X_FORWARDED_FOR');
            }
        }
        if (!empty($spclasses->protect->server('HTTP_X_FORWARDED')) && $this->validate_ip($spclasses->protect->server('HTTP_X_FORWARDED')))
            return $spclasses->protect->server('HTTP_X_FORWARDED');
        if (!empty($spclasses->protect->server('HTTP_X_CLUSTER_CLIENT_IP')) && $this->validate_ip($spclasses->protect->server('HTTP_X_CLUSTER_CLIENT_IP')))
            return $spclasses->protect->server('HTTP_X_CLUSTER_CLIENT_IP');
        if (!empty($spclasses->protect->server('HTTP_FORWARDED_FOR')) && $this->validate_ip($spclasses->protect->server('HTTP_FORWARDED_FOR')))
            return $spclasses->protect->server('HTTP_FORWARDED_FOR');
        if (!empty($spclasses->protect->server('HTTP_FORWARDED')) && $this->validate_ip($spclasses->protect->server('HTTP_FORWARDED')))
            return $spclasses->protect->server('HTTP_FORWARDED');

        // return unreliable ip since all else failed
        return $spclasses->protect->server('REMOTE_ADDR');
	}
    
	function validate_ip($ip) {
        if (strtolower($ip) === 'unknown')
            return false;

        // generate ipv4 network address
        $ip = ip2long($ip);

        // if the ip is set and not equivalent to 255.255.255.255
        if ($ip !== false && $ip !== -1) {
            // make sure to get unsigned long representation of ip
            // due to discrepancies between 32 and 64 bit OSes and
            // signed numbers (ints default to signed in PHP)
            $ip = sprintf('%u', $ip);
            // do private network range checking
            if ($ip >= 0 && $ip <= 50331647) return false;
            if ($ip >= 167772160 && $ip <= 184549375) return false;
            if ($ip >= 2130706432 && $ip <= 2147483647) return false;
            if ($ip >= 2851995648 && $ip <= 2852061183) return false;
            if ($ip >= 2886729728 && $ip <= 2887778303) return false;
            if ($ip >= 3221225984 && $ip <= 3221226239) return false;
            if ($ip >= 3232235520 && $ip <= 3232301055) return false;
            if ($ip >= 4294967040) return false;
        }
        return true;
	}
}