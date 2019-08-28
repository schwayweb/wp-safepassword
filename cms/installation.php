<?php

class spcmsInstallation {
  
    function __construct(){
    }
    
    /*
     *  Start Installation / Update database CMS
     */ 
    function start() {
        global $spcms;
        global $spclasses;
      
        // Wordpress
        if($spcms['type'] == 'wordpress') {
            // Install / Update Database
            $spclasses->db->installation('safepwd_options');
        }
    }
}