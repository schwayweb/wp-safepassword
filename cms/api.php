<?php

/*
 * Create API
 */
class spcmsApi {
  
    function __construct(){
    }
  
    function start() {
        global $spclasses;
        
        // Network Api
        $api_type = $spclasses->protect->get('type');
      
        switch($api_type) {
          case "verifycode":
            $this->verifycode();
            break;
        }
    }
  
    function verifycode(){
        global $spDB;
        global $spcms;
        global $spsafepwd;
        global $spclasses;
        
        $spclasses->db->start();
        
        // Code
        $code = $spclasses->option->get('code');

        echo $code;
        exit;
    }
}