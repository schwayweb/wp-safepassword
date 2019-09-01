<?php

/*
 * Create HTTP Requests
 */
class spcmsHTTP {
  
    function __construct(){
    }
    
    function get($url, $path, $fields = array(), $headers = array()){
        $options = !empty($fields) ? http_build_query($fields):'';
        $url = $url.$path.'/'.$options;
        
        $body = array();
        
        $args = array(
            'method' => 'GET'
        );
        
        $response = wp_remote_request( $url, $args );
        
        return $this->response($response);
    }
    
    function post($url, $path, $fields = array(), $headers = array()){
        $url = $url.$path;
        $args = array(
            'method' => 'POST',
            'body' => $fields,
            'headers' => $headers
        );
        
        $response = wp_remote_request( $url, $args );
        
        return $this->response($response);
    }
    
    function put($url, $path, $fields = array(), $headers = array()){
        $url = $url.$path;
        $args = array(
            'method' => 'PUT',
            'body' => $fields,
            'headers' => $headers
        );
        
        $response = wp_remote_request( $url, $args );
        
        return $this->response($response);
    }
    
    function delete($url, $path, $fields = array(), $headers = array()){
        $url = $url.$path;
        $args = array(
            'method' => 'DELETE',
            'body' => $fields,
            'headers' => $headers
        );
        
        $response = wp_remote_request( $url, $args );
        
        return $this->response($response);
    }
    
    function response($response){
        $new_response = new stdClass;
        
        $new_response->code = wp_remote_retrieve_response_code($response);
        $new_response->response = wp_remote_retrieve_body($response);
        
        return $new_response;
    }
}