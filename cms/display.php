<?php

class spcmsDisplay {
  
    function __construct(){
    }
    
    /*
     *  View CMS
     */ 
    function view($view) {
        global $spcms;
        global $spclasses;
      
        if($view == '') {
          $view = 'main';
        }
      
        // Wordpress
        if($spcms['type'] == 'wordpress') {
            
            if(file_exists($spcms['plugin_path'].'views/'.$view.'.php')) {
                include_once $spcms['plugin_path'].'views/'.$view.'.php';
            }
        }
    }
}