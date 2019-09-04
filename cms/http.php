<?php

/*
 * Create HTTP Requests
 */
class spcmsHTTP {
  
    function __construct(){
    }
    
    function get($url, $path, $fields = array(), $headers = array()){
        $url = $url.$path;
        $url = add_query_arg($fields, $url);
        
        $body = array();
        
        $args = array(
            'method' => 'GET'
        );
        
        if(!empty($headers)
          && isset($headers['token'])) {
            $reqHeaders = array(
                'Authorization' => 'Bearer ' . $headers['token'],
                'Content-type' => 'application/x-www-form-urlencoded',
                'Host'          => site_url()
            );
            
            $args['headers'] = $reqHeaders;
        }
        
        $response = wp_remote_request( $url, $args );
        
        return $this->response($response);
    }
    
    function post($url, $path, $fields = array(), $headers = array()){
        $url = $url.$path;
        $args = array(
            'method' => 'POST',
            'body' => $fields
        );
        
        if(!empty($headers)
          && isset($headers['token'])) {
            $reqHeaders = array(
                'Authorization' => 'Bearer ' . $headers['token'],
                'Content-type' => 'application/x-www-form-urlencoded',
                'Host'          => site_url()
            );
            
            $args['headers'] = $reqHeaders;
        }
        
        $response = wp_remote_request( $url, $args );
        
        return $this->response($response);
    }
    
    function put($url, $path, $fields = array(), $headers = array()){
        $url = $url.$path;
        $args = array(
            'method' => 'PUT',
            'body' => $fields
        );
        
        if(!empty($headers)
          && isset($headers['token'])) {
            $reqHeaders = array(
                'Authorization' => 'Bearer ' . $headers['token'],
                'Content-type' => 'application/x-www-form-urlencoded',
                'Host'          => site_url()
            );
            
            $args['headers'] = $reqHeaders;
        }
        
        $response = wp_remote_request( $url, $args );
        
        return $this->response($response);
    }
    
    function delete($url, $path, $fields = array(), $headers = array()){
        $url = $url.$path;
        $args = array(
            'method' => 'DELETE',
            'body' => json_encode($fields)
        );
        
        if(!empty($headers)
          && isset($headers['token'])) {
            $reqHeaders = array(
                'Authorization' => 'Bearer ' . $headers['token'],
                'Content-type' => 'application/json',
                'Host'          => site_url()
            );
            
            $args['headers'] = $reqHeaders;
        }
        
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