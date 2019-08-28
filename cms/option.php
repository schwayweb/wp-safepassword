<?php

class spcmsOption {
  
    function __construct(){
    }
    
    /*
     *  Get option
     */ 
    function get($name = '',
                 $user_id = 0,
                 $type = 'all') {
        global $spcms;
        global $spclasses;
        global $spDB;
        $value = '';
      
        // Wordpress
        if($spcms['type'] == 'wordpress') {
            $row = $spDB->get_row($spDB->prepare('SELECT * FROM '.$spDB->safepwd_table->options.' where option_name = %s AND user_id = %d', array($name, $user_id)));
            
            if($spDB->num_rows > 0) {
                $value = $row->option_value;
            }
            
            return $value;
        }
    }
    
    /*
     *  Get option by type
     */ 
    function get_by_type($type = 'main',
                         $show = true) {
        global $spcms;
        global $spclasses;
        global $spDB;
        $value = '';
      
        // Wordpress
        if($spcms['type'] == 'wordpress') {
            $rows = $spDB->get_results($spDB->prepare('SELECT * FROM '.$spDB->safepwd_table->options.' where option_type = %s', array($type)));
            $options = new stdClass;
            
            if($spDB->num_rows > 0) {
              
                foreach($rows as $row) {
                    
                  if($show) {
                    $options->{$row->{'option_name'}} = $row->{'option_value'};
                  } else {
                      
                      if($row->{'option_name'} != 'network_token'
                         && $row->{'option_name'} != 'use_stripe'
                         && $row->{'option_name'} != 'stripe_secret_key'
                         && $row->{'option_name'} != 'stripe_publishable_key'
                         && $row->{'option_name'} != 'use_test_stripe'
                         && $row->{'option_name'} != 'stripe_test_secret_key'
                         && $row->{'option_name'} != 'stripe_test_publishable_key') {
                        $options->{$row->{'option_name'}} = $row->{'option_value'};
                      }
                  }
                }
            }
            
            return $options;
        }
    }
    
    /*
     *  Add option
     */ 
    function add($name,
                 $value,
                 $user_id = 0,
                 $type = 'main') {
        global $spcms;
        global $spclasses;
        global $spDB;
        
        // Wordpress
        if($spcms['type'] == 'wordpress') {
            $row = $spDB->get_row($spDB->prepare('SELECT * FROM '.$spDB->safepwd_table->options.' where option_name = %s AND  option_type = %s AND user_id = %d', array($name, $type, $user_id)));
            
            if($spDB->num_rows > 0) {
                $spDB->update($spDB->safepwd_table->options, array(
                    'option_value' => $value,
                    'option_date' => date('Y-m-d H:i:s')
                ), array(
                    'option_name' => $name,
                    'user_id' => $user_id,
                    'option_type' => $type
                ));
            } else {
                $spDB->insert($spDB->safepwd_table->options, array(
                    'option_name' => $name,
                    'option_value' => $value,
                    'user_id' => $user_id,
                    'option_type' => $type,
                    'option_date' => date('Y-m-d H:i:s')
                ));
            }
            
            return $value;
        }
    }
    
    /*
     *  Append option
     */ 
    function append($name,
                    $value,
                    $user_id = 0,
                    $type = 'main') {
        global $spcms;
        global $spclasses;
        global $spDB;
        
        // Wordpress
        if($spcms['type'] == 'wordpress') {
            $row = $spDB->get_row($spDB->prepare('SELECT * FROM '.$spDB->safepwd_table->options.' where option_name = %s AND  option_type = %s AND user_id = %d', array($name, $type, $user_id)));
            
            if($spDB->num_rows > 0) {
                $old_value = $row->option_value;
                $new_value = intval($old_value)+intval($value);
                $spDB->update($spDB->safepwd_table->options, array(
                    'option_value' => $new_value,
                    'option_date' => date('Y-m-d H:i:s')
                ), array(
                    'option_name' => $name,
                    'user_id' => $user_id,
                    'option_type' => $type
                ));
            } else {
                $spDB->insert($spDB->safepwd_table->options, array(
                    'option_name' => $name,
                    'option_value' => $value,
                    'user_id' => $user_id,
                    'option_type' => $type,
                    'option_date' => date('Y-m-d H:i:s')
                ));
            }
            
            return $value;
        }
    }
    
    /*
     *  Delete option
     */ 
    function delete($name,
                    $user_id = 0,
                    $type = 'all') {
        global $spcms;
        global $spclasses;
        global $spDB;
      
        // Wordpress
        if($spcms['type'] == 'wordpress') {
            $spDB->delete($spDB->safepwd_table->options, array(
                'option_name' => $name,
                'user_id' => $user_id
            ));
        }
    }
    
    /*
     *  Delete all options
     */ 
    function delete_all() {
        global $spcms;
        global $spclasses;
        global $spDB;
      
        // Wordpress
        if($spcms['type'] == 'wordpress') {
          $delete = $spDB->query("TRUNCATE TABLE `".$spDB->safepwd_table->options."`");
        }
    }
}