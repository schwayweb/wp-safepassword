<?php

class spcmsResources {
  
    function __construct(){
    }
    
    /*
     *  Frontend CMS
     */ 
    function frontend() {
        global $spcms;
        global $spclasses;
        
        // Wordpress
        if($spcms['type'] == 'wordpress') {
            
            $is_login_page = $spclasses->main->is_wplogin();
          
            // CSS FILES
            foreach($spcms['resources']['css'] as $cssLink){
                $fileName = str_replace(' ', '-', $cssLink['name']);
                
                if($cssLink['type'] != 'backend') {
                    wp_register_style($fileName, ($cssLink['load'] == 'file' ? $spcms['plugin_url']:'').$cssLink['file']);
                    wp_enqueue_style($fileName);
                }
            }
          
            if (!wp_script_is('jquery', 'queue')){
                wp_enqueue_script('jquery');
            }
          
            if (!wp_script_is('jquery-ui-datepicker', 'queue')){
                wp_enqueue_script('jquery-ui-datepicker');
            }
          
            // JS FILES
            foreach($spcms['resources']['js'] as $jsLink){
                $fileName = str_replace(' ', '-', $jsLink['name']);
                
                if(($jsLink['page'] != '' && $spcms['page'] == $jsLink['page'])
                  || ($jsLink['page'] == '')
                  || $jsLink['type'] == 'both'
                  && ($jsLink['role'] == ''
                      || $jsLink['role'] == $spcms['role'])) {
                    
                    if($jsLink['type'] != 'backend') {
                        
                        if($jsLink['login_page']) {
                            
                            if($is_login_page) {
                                wp_register_script($fileName, ($jsLink['load'] == 'file' ? $spcms['plugin_url']:'').$jsLink['file'], 'jquery', false, true);
                                wp_enqueue_script($fileName, 9999);
                            }
                        } else {
                            
                            if(!$is_login_page) {
                                wp_register_script($fileName, ($jsLink['load'] == 'file' ? $spcms['plugin_url']:'').$jsLink['file'], 'jquery', false, true);
                                wp_enqueue_script($fileName, 9999);
                            }
                        }
                    }
                }
            }
        }
    }
    
    /*
     *  Backend CMS
     */ 
    function backend() {
        global $spcms;
        global $spclasses;
      
        // Wordpress
        if($spcms['type'] == 'wordpress') {
            $is_profile_page = $spclasses->main->is_wp_profile();
            $is_login_page   = $spclasses->main->is_wplogin();
          
            // CSS FILES
            foreach($spcms['resources']['css'] as $cssLink){
                $fileName = str_replace(' ', '-', $cssLink['name']);
                
                if($cssLink['type'] != 'frontend') {
                    wp_register_style($fileName, ($cssLink['load'] == 'file' ? $spcms['plugin_url']:'').$cssLink['file']);
                    wp_enqueue_style($fileName);
                }
            }
          
            if (!wp_script_is('jquery', 'queue')){
                wp_enqueue_script('jquery');
            }
          
            if (!wp_script_is('jquery-ui-datepicker', 'queue')){
                wp_enqueue_script('jquery-ui-datepicker');
            }
          
            if($spclasses->main->isBMpage()) {
                // JS FILES
                foreach($spcms['resources']['js'] as $jsLink){
                    $fileName = str_replace(' ', '-', $jsLink['name']);

                    if(($jsLink['page'] != '' && $spcms['page'] == $jsLink['page'])
                      || ($jsLink['page'] == '')
                      && ($jsLink['role'] == ''
                          || $jsLink['role'] == $spcms['role'])) {

                        if($jsLink['type'] != 'frontend') {
                            
                            if($jsLink['dashboard_page']){
                                wp_register_script($fileName, ($jsLink['load'] == 'file' ? $spcms['plugin_url']:'').$jsLink['file'], array('jquery'), false, true);
                                wp_enqueue_script($fileName, 9999);
                            } else if($jsLink['profile_page']) {
                            
                                if($is_profile_page) {
                                    wp_register_script($fileName, ($jsLink['load'] == 'file' ? $spcms['plugin_url']:'').$jsLink['file'], array('jquery'), false, true);
                                    wp_enqueue_script($fileName, 9999);
                                }
                            } else {

                                if(!$is_profile_page) {
                                    
                                    if($jsLink['login_page']) {
                            
                                        if($is_login_page) {
                                            wp_register_script($fileName, ($jsLink['load'] == 'file' ? $spcms['plugin_url']:'').$jsLink['file'], array('jquery'), false, true);
                                            wp_enqueue_script($fileName, 9999);
                                        }
                                    } else {
                                        
                                        if(!$is_login_page) {
                                            wp_register_script($fileName, ($jsLink['load'] == 'file' ? $spcms['plugin_url']:'').$jsLink['file'], array('jquery'), false, true);
                                            wp_enqueue_script($fileName, 9999);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                // JS FILES
                foreach($spcms['resources']['js'] as $jsLink){
                    $fileName = str_replace(' ', '-', $jsLink['name']);

                    if($jsLink['page'] == ''
                        && ($jsLink['role'] == ''
                            || $jsLink['role'] == $spcms['role'])) {

                        if($jsLink['type'] != 'frontend') {
                            
                            if($jsLink['profile_page']) {
                            
                                if($is_profile_page) {
                                    wp_register_script($fileName, ($jsLink['load'] == 'file' ? $spcms['plugin_url']:'').$jsLink['file'], array('jquery'), false, true);
                                    wp_enqueue_script($fileName, 9999);
                                }
                            } else {

                                if(!$is_profile_page) {
                                    
                                    if($jsLink['login_page']) {
                            
                                        if($is_login_page) {
                                            wp_register_script($fileName, ($jsLink['load'] == 'file' ? $spcms['plugin_url']:'').$jsLink['file'], array('jquery'), false, true);
                                            wp_enqueue_script($fileName, 9999);
                                        }
                                    } else {

                                        if(!$is_login_page) {
                                            wp_register_script($fileName, ($jsLink['load'] == 'file' ? $spcms['plugin_url']:'').$jsLink['file'], array('jquery'), false, true);
                                            wp_enqueue_script($fileName, 9999);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    /*
     *  // JS vars - Translation, Requests, ...
     */ 
    function js() {
        global $spcms;
        global $sptext;
        global $spcms;
        global $spclasses;
        
        $js_html = array();
        
        // Wordpress
        if($spcms['type'] == 'wordpress') {
      
            // Owner Data
            $server = $spclasses->option->get('server');
          
            if(isset($server) 
               && $server != '') {
                $spcms['api_url'] = str_replace('www', $server, $spcms['api_url']);
            }
          
            array_push($js_html, '<script type="text/javascript">');
            
            // Translation
            array_push($js_html,   'window.sptext = [];');
            array_push($js_html,   'var sptext = [];');
            foreach ($sptext as $key => $value) {
                array_push ($js_html, 'window.sptext["'.$key.'"] = "'.$value.'";');
            }
          
            // Ajax requests
            array_push($js_html,   'window.spsafepwd_request_url = "'.admin_url('admin-ajax.php').'";');
            array_push($js_html,   'var spsafepwd_request_url = "'.admin_url('admin-ajax.php').'";');
            array_push($js_html,   'window.spsafepwd_request = [];');
            array_push($js_html,   'var spsafepwd_request = [];');
            foreach($spcms['requests'] as $request){
                array_push ($js_html, 'window.spsafepwd_request["'.$request['name'].'"] = "spsafepwd_'.$request['name'].'";');
            }
      
            // Account type Data
            $account_type = $spclasses->option->get('type', $spcms['user_id']);
            
            // General Data
            array_push($js_html,   'window.spsafepwd = [];');
            array_push($js_html,   'var spsafepwd = [];');
            array_push($js_html,   '    spsafepwd["page"] = "'.$spcms['page'].'";');
            array_push($js_html,   '    spsafepwd["server"] = "'.$server.'";');
            array_push($js_html,   '    spsafepwd["api_url"] = "'.$spcms['api_url'].'";');
            array_push($js_html,   '    spsafepwd["type"] = "'.$spcms['type'].'";');
            array_push($js_html,   '    spsafepwd["role"] = "'.$spcms['role'].'";');
            array_push($js_html,   '    spsafepwd["support_role"] = "'.$spcms['support_url'].'";');
            array_push($js_html,   '    spsafepwd["credits_costs"] = "'.$spcms['credits_costs'].'";');
            array_push($js_html,   '    spsafepwd["upgrade_account"] = "'.$spcms['upgrade_url'].'";');
            array_push($js_html,   '    spsafepwd["language"] = "'.$spcms['language'].'";');
            array_push($js_html,   '    spsafepwd["user_id"] = '.$spcms['user_id'].';');
            array_push($js_html,   '    spsafepwd["account_type"] = "'.$account_type.'";');
            array_push($js_html,   '    spsafepwd["ajax_ses"] = "'.$spclasses->protect->hide($spcms['user_id'].'@@@'.$spcms['role']).'";');
            array_push($js_html,   "    spsafepwd['languages'] = '".$this->languages()."';");
            array_push($js_html,   "    spsafepwd['countries'] = ".json_encode($spclasses->main->countries()).";");
            array_push($js_html, '</script>');
          
        }
      
        echo implode('', $js_html);
    }
    
    function languages(){
        global $sptext;
        global $splanguages;
        $valid_languages = $splanguages['languages'];
        $language_codes = array(
                'en' => 'English' , 
                'aa' => 'Afar' , 
                'ab' => 'Abkhazian' , 
                'af' => 'Afrikaans' , 
                'am' => 'Amharic' , 
                'ar' => 'Arabic' , 
                'as' => 'Assamese' , 
                'ay' => 'Aymara' , 
                'az' => 'Azerbaijani' , 
                'ba' => 'Bashkir' , 
                'be' => 'Byelorussian' , 
                'bg' => 'Bulgarian' , 
                'bh' => 'Bihari' , 
                'bi' => 'Bislama' , 
                'bn' => 'Bengali/Bangla' , 
                'bo' => 'Tibetan' , 
                'br' => 'Breton' , 
                'ca' => 'Catalan' , 
                'co' => 'Corsican' , 
                'cs' => 'Czech' , 
                'cy' => 'Welsh' , 
                'da' => 'Danish' , 
                'de' => 'German' , 
                'dz' => 'Bhutani' , 
                'el' => 'Greek' , 
                'eo' => 'Esperanto' , 
                'es' => 'Spanish' , 
                'et' => 'Estonian' , 
                'eu' => 'Basque' , 
                'fa' => 'Persian' , 
                'fi' => 'Finnish' , 
                'fj' => 'Fiji' , 
                'fo' => 'Faeroese' , 
                'fr' => 'French' , 
                'fy' => 'Frisian' , 
                'ga' => 'Irish' , 
                'gd' => 'Scots/Gaelic' , 
                'gl' => 'Galician' , 
                'gn' => 'Guarani' , 
                'gu' => 'Gujarati' , 
                'ha' => 'Hausa' , 
                'hi' => 'Hindi' , 
                'hr' => 'Croatian' , 
                'hu' => 'Hungarian' , 
                'hy' => 'Armenian' , 
                'ia' => 'Interlingua' , 
                'ie' => 'Interlingue' , 
                'ik' => 'Inupiak' , 
                'in' => 'Indonesian' , 
                'is' => 'Icelandic' , 
                'it' => 'Italian' , 
                'iw' => 'Hebrew' , 
                'jp' => 'Japanese' , 
                'ji' => 'Yiddish' , 
                'jw' => 'Javanese' , 
                'ka' => 'Georgian' , 
                'kk' => 'Kazakh' , 
                'kl' => 'Greenlandic' , 
                'km' => 'Cambodian' , 
                'kn' => 'Kannada' , 
                'ko' => 'Korean' , 
                'ks' => 'Kashmiri' , 
                'ku' => 'Kurdish' , 
                'ky' => 'Kirghiz' , 
                'la' => 'Latin' , 
                'ln' => 'Lingala' , 
                'lo' => 'Laothian' , 
                'lt' => 'Lithuanian' , 
                'lv' => 'Latvian/Lettish' , 
                'mg' => 'Malagasy' , 
                'mi' => 'Maori' , 
                'mk' => 'Macedonian' , 
                'ml' => 'Malayalam' , 
                'mn' => 'Mongolian' , 
                'mo' => 'Moldavian' , 
                'mr' => 'Marathi' , 
                'ms' => 'Malay' , 
                'mt' => 'Maltese' , 
                'my' => 'Burmese' , 
                'na' => 'Nauru' , 
                'ne' => 'Nepali' , 
                'nl' => 'Dutch' , 
                'no' => 'Norwegian' , 
                'oc' => 'Occitan' , 
                'om' => '(Afan)/Oromoor/Oriya' , 
                'pa' => 'Punjabi' , 
                'pl' => 'Polish' , 
                'ps' => 'Pashto/Pushto' , 
                'pt' => 'Portuguese' , 
                'qu' => 'Quechua' , 
                'rm' => 'Rhaeto-Romance' , 
                'rn' => 'Kirundi' , 
                'ro' => 'Romanian' , 
                'ru' => 'Russian' , 
                'rw' => 'Kinyarwanda' , 
                'sa' => 'Sanskrit' , 
                'sd' => 'Sindhi' , 
                'sg' => 'Sangro' , 
                'sh' => 'Serbo-Croatian' , 
                'si' => 'Singhalese' , 
                'sk' => 'Slovak' , 
                'sl' => 'Slovenian' , 
                'sm' => 'Samoan' , 
                'sn' => 'Shona' , 
                'so' => 'Somali' , 
                'sq' => 'Albanian' , 
                'sr' => 'Serbian' , 
                'ss' => 'Siswati' , 
                'st' => 'Sesotho' , 
                'su' => 'Sundanese' , 
                'sv' => 'Swedish' , 
                'sw' => 'Swahili' , 
                'ta' => 'Tamil' , 
                'te' => 'Tegulu' , 
                'tg' => 'Tajik' , 
                'th' => 'Thai' , 
                'ti' => 'Tigrinya' , 
                'tk' => 'Turkmen' , 
                'tl' => 'Tagalog' , 
                'tn' => 'Setswana' , 
                'to' => 'Tonga' , 
                'tr' => 'Turkish' , 
                'ts' => 'Tsonga' , 
                'tt' => 'Tatar' , 
                'tw' => 'Twi' , 
                'uk' => 'Ukrainian' , 
                'ur' => 'Urdu' , 
                'uz' => 'Uzbek' , 
                'vi' => 'Vietnamese' , 
                'vo' => 'Volapuk' , 
                'wo' => 'Wolof' , 
                'xh' => 'Xhosa' , 
                'yo' => 'Yoruba' , 
                'zh' => 'Chinese' , 
                'zu' => 'Zulu' , 
                );
        $languages = array();
        
        $language = new stdClass;
        
        foreach($language_codes as $key => $value){
            
            if(in_array($key, $valid_languages)) {
                $language = new stdClass;
                $language->name = $language_codes[$key];
                $language->value = $key;
                array_push($languages, $language);
            }
        }

        return json_encode($languages);
	}
}