<?php

class spcmsRequests {
  
    function __construct(){
        $this->start();
    }
    
    /*
     *  Add requests
     */ 
    function start() {
        global $spcms;
        global $spsafepwd;
        global $spclasses;
      
      
        // Wordpress
        if($spcms['type'] == 'wordpress') {
          
            // Ajax requests
            foreach($spcms['requests'] as $request){
                add_action('wp_ajax_spsafepwd_'.$request['name'], array(&$spsafepwd->{$request['class']}, $request['function']));
                
                if($request['type'] == 'frontend'
                  || $request['type'] == 'both') {
                    add_action('wp_ajax_nopriv_spsafepwd_'.$request['name'], array(&$spsafepwd->{$request['class']}, $request['function']));
                }
            }
        }
    }
}