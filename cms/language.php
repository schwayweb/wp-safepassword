<?php

class spcmsLanguage {
  
    function __construct(){
        global $splanguages;
        $splanguages['files'] = array('en' => 'en.php',
                                       'es' => 'es.php', 
                                       'fr' => 'fr.php', 
                                       'de' => 'de.php', 
                                       'it' => 'it.php', 
                                       'jp' => 'jp.php', 
                                       'ru' => 'ru.php', 
                                       'ro' => 'ro.php');
        $splanguages['languages'] = array('en', 'es', 'fr', 'de', 'it', 'jp', 'ru', 'ro');
        
        // Role detect
        add_action('init', array(&$this, 'load'));
        add_action('wp', array(&$this, 'load'));
    }
    
    /*
     * Autodetect & Load language
     */ 
    function load() {
        global $spcms;
        global $spclasses;
        global $splanguages;
        $language_path = defined('WP_CONTENT_DIR') ? WP_CONTENT_DIR.'/plugins/wp-safepassword/languages/':ABSPATH.'/wp-content/plugins/wp-safepassword/languages/';
        
        // Config
        $language = $spcms['language'];
        
        $user_id = $this->get_owner_user_id();
        
        // User language
        $user_language = $spclasses->option->get('language',
                                                  $user_id);
        
        if($user_language != "") {
            $language = $user_language;
        }
        
        // $_GET language
        $get_language = $spclasses->protect->get('lang');
        
        if(trim($get_language) != "") {
            
            // Update language
            if($user_id != 0
               && trim($get_language) != trim($user_language)) {
                $spclasses->option->add('language',
                                          $this->valid_language($get_language),
                                          $user_id,
                                          'user');
            }
            $language = $get_language;
        }
        
        // poly lang get current language
        if(function_exists('pll_current_language')) {
            $poly_language = pll_current_language('slug');
            
            // Update language
            if($user_id != 0
               && trim($poly_language) != trim($user_language)) {
                $spclasses->option->add('language',
                                          $this->valid_language($poly_language),
                                          $user_id,
                                          'user');
            }
            $language = $poly_language;
        }
        
        if(isset($spcms['shortcode_language'])) {
            $language = $spcms['shortcode_language'];
        }
        
        // $_GET language
        $bec_language = $spclasses->protect->get('bec_lang');
        
        if(trim($bec_language) != "") {
            $language = $bec_language;
        }
        
        //validate language
        $language = $this->valid_language($language);
        
        $spcms['language'] = $language;
        
        // include language file
        include($language_path.$splanguages['files'][$language]);
    }
    
    function get_owner_user_id(){
        global $spcms;
        global $spclasses;
        $user_id = 0;
        
        if(defined('WP_CONTENT_DIR')) {
            $spcms['type'] = 'wordpress';
            
            // Load Database
            $spclasses->db->start();

            global $current_user;
            $current_user = wp_get_current_user();
            
            if(!empty($current_user)
               && isset($current_user->ID)) {
                $user_id = $current_user->ID;
            } 
            else {
                if($spclasses->protect->get('ajax_ses') != ''){
                    $user_data = $spclasses->protect->show($spclasses->protect->get('ajax_ses'));
                    $user_data = explode('@@@', $user_data);
                    $user_id = $user_data[0];
                } else if($spclasses->protect->post('ajax_ses') != ''){
                    $user_data = $spclasses->protect->show($spclasses->protect->post('ajax_ses'));
                    $user_data = explode('@@@', $user_data);
                    $user_id = $user_data[0];
                }
            }
        }
        
        return $user_id;
    }
    
    function valid_language($language){
        global $splanguages;
        $valid_languages = $splanguages['languages'];
        
        if(trim($language) == '') {
            $language = $valid_languages[0];
        }
        
        if(!in_array($language, $valid_languages)) {
            $language = $valid_languages[0];
        }
        
        return $language;
    }
}