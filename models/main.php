<?php

class safepwdMain {
  
    function __construct(){
    }
    
    /*
     * Display
     */ 
    function display() {
        global $spcms;
        global $spclasses;

        $spclasses->display->view('main');
    }
    
    /*
     * Ajax requests
     */
    function server(){
        global $spsafepwd;
        global $spcms;
        global $spclasses;
        $type           = $spclasses->protect->post('type'); // simple / dedicated
        $retData        = array('status' => 'success',
                                'data' => '');
        
        if(strlen($type) < 4) {
            $retData['status'] = 'error';
            $retData['data'] = 'Error: Wrong type.';
            exit;
        }

        // Get Token
        $api = new $spsafepwd->http([
          'base_url' => $spcms['api_url'], 
          'format' => "json"
        ]);

        $result = $api->post("server",
                             ['type' => $type]);
        
        if($result->response_status_lines[0] == 'HTTP/1.1 201 Created' || $result->response_status_lines[0] == 'HTTP/1.1 200 OK'
          || (isset($result->response_status_lines[1]) && ($result->response_status_lines[1] == 'HTTP/1.1 201 Created' || $result->response_status_lines[1] == 'HTTP/1.1 200 OK'))) {
            $response = $spclasses->protect->data($result->response, 'json');
            $data = json_decode($response);
            
            $status = $data->status;
            $server = $data->data;
            $server = isset($server) && $server != '' ? $server:'www';
            
            if($status != 'success') {
                echo $response;
                exit;
            }
            
            // Server
            $spclasses->option->add('server',
                                     $server);
            
            $retData['data'] = $server;
            
            echo json_encode($retData);
        } else {
            $retData['status'] = 'error';
            $retData['data'] = 'Sorry. SafePassword is maintenance mode.';
            echo json_encode($retData);
        }
        
        exit;
    }
    
    /*
     * Ajax requests
     */
    function connect(){
        global $spsafepwd;
        global $spcms;
        global $spclasses;
        $email          = $spclasses->protect->post('email', 'email');
        $website        = $spclasses->protect->post('website');
        $step           = $spclasses->protect->post('step');
        $referral_id    = $spclasses->protect->post('referral_id');
        $token          = '';
        $retData        = array('status' => 'success',
                                'data' => '');
        
        if(strlen($email) < 4) {
            $retData['status'] = 'error';
            $retData['data'] = 'Wrong email. Please complete the email field.';
            exit;
        }

        // Get Token
        $api = new $spsafepwd->http([
          'base_url' => $spcms['api_url'], 
          'format' => "json"
        ]);

        $result = $api->post("connect",
                             ['email' => $email,
                              'website' => $website,
                              'referral_id' => $referral_id,
                              'step' => $step]);
        
        if($result->response_status_lines[0] == 'HTTP/1.1 201 Created' || $result->response_status_lines[0] == 'HTTP/1.1 200 OK'
          || (isset($result->response_status_lines[1]) && ($result->response_status_lines[1] == 'HTTP/1.1 201 Created' || $result->response_status_lines[1] == 'HTTP/1.1 200 OK'))) {
            $response = $spclasses->protect->data($result->response, 'json');
            $data = json_decode($response);
            
            $status = $data->status;
            $data = $data->data;
            
            if($status != 'success') {
                echo $response;
                exit;
            }

            if($step == '1') {
                $code = isset($data->code) ? $data->code:'';

                if($code != '') {
                    // Code
                    $spclasses->option->add('code',
                                             $code);
                    echo json_encode($retData);
                } else {
                    echo $response;
                }
            } else if($step == '2') {
                $token = isset($data->token) ? $data->token:'';
                $server = isset($data->server) ? $data->server:'';
                $public_key = isset($data->public_key) ? $data->public_key:'';
                $private_key = isset($data->private_key) ? $data->private_key:'';

                if($token != ''
                  && $server != ''
                  && $public_key != ''
                  && $private_key != '') {
                    // Email
                    $spclasses->option->add('email',
                                             $email);

                    // Token
                    $spclasses->option->add('token',
                                             $token);

                    // Server
                    $spclasses->option->add('server',
                                             $server);

                    // Public Key
                    $spclasses->option->add('public_key',
                                             $public_key);

                    // Private Key
                    $spclasses->option->add('private_key',
                                             $private_key);

                    echo json_encode($retData);
                } else {
                    echo $response;
                }
            }
        } else {
            $retData['status'] = 'error';
            $retData['data'] = 'Sorry. SafePassword is maintenance mode.';
            echo json_encode($retData);
        }
        
        exit;
    }
    
    function disconnect(){
        global $spsafepwd;
        global $spcms;
        global $spclasses;
        $role = $spcms['role'];
        
        if($role == 'admin') {
            // Delete connection options
            // Email
            $spclasses->option->delete('email');

            // Token
            $spclasses->option->delete('token');

            // Public Key
            $spclasses->option->delete('public_key');

            // Private Key
            $spclasses->option->delete('private_key');
            echo 'success';
        } else {
            echo 'not_allowed';
        }
        
        exit;
    }
    
    function detect_country(){
        global $spsafepwd;
        global $spcms;
        global $spclasses;
      
        $token = $spclasses->option->get('token');
        $user_id = $spcms['user_id'];
			
        // Server
        $server = $spclasses->option->get('server');

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

        $result = $api->get("ip",
                            ['ip' => $spclasses->main->get_ip_address()]);
        
        if($result->response_status_lines[0] == 'HTTP/1.1 201 Created' || $result->response_status_lines[0] == 'HTTP/1.1 200 OK'
          || (isset($result->response_status_lines[1]) && ($result->response_status_lines[1] == 'HTTP/1.1 201 Created' || $result->response_status_lines[1] == 'HTTP/1.1 200 OK'))) {
            $response = $spclasses->protect->data($result->response, 'json');
            $data = $response;
        } else {
            $data = array('status' => 'error');
            $data = json_encode($data);
        }
        
        echo $data;
        exit;
    }
  
    function register(){
        global $spsafepwd;
        global $spcms;
        global $spclasses;
        $email      = $spclasses->protect->post('email', 'email');
        $phone      = $spclasses->protect->post('phone');
        $type       = $spclasses->protect->post('type');
        $billed     = $spclasses->protect->post('billed');
        $paid       = $spclasses->protect->post('paid');
        $link_back  = $spclasses->protect->post('link_back');
      
        if(!strlen($email)
           || !strlen($phone)
           || !strlen($type)) {
            $data = array('status' => 'error');
            echo json_encode($data);
            exit;
        }
      
        $token = $spclasses->option->get('token');
        $user_id = $spcms['user_id'];
			
        // Server
        $server = $spclasses->option->get('server');

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
        
        $result = $api->post("register",
                            ['email' => $email, 
                             'phone' => $phone, 
                             'user_id' => $user_id, 
                             'type' => $type, 
                             'paid' => $paid, 
                             'link_back' => $link_back, 
                             'billed' => $billed]);
        
        if($result->response_status_lines[0] == 'HTTP/1.1 201 Created' || $result->response_status_lines[0] == 'HTTP/1.1 200 OK'
          || (isset($result->response_status_lines[1]) && ($result->response_status_lines[1] == 'HTTP/1.1 201 Created' || $result->response_status_lines[1] == 'HTTP/1.1 200 OK'))) {
            $response = $spclasses->protect->data($result->response, 'json');
            $data = json_decode($response);
            
            if($data->status == 'success') {
                
                // Enable SafePassword
                $spclasses->option->add('enable_sp',
                                         'true',
                                         $user_id);
                // Email
                $spclasses->option->add('email',
                                         $email,
                                         $user_id);
                // Phone
                $spclasses->option->add('phone',
                                         $phone,
                                         $user_id);
                // Type
                $spclasses->option->add('type',
                                         $type,
                                         $user_id);
                // Billed
                $spclasses->option->add('billed',
                                         $billed,
                                         $user_id);
                // Account Start Date
                $spclasses->option->add('start_date',
                                         date('Y-m-d'),
                                         $user_id);
            }
          
            echo $response;
        }
        exit;
    }
  
    function unregister(){
        global $spsafepwd;
        global $spcms;
        global $spclasses;
        $token      = $spclasses->option->get('token');
        $user_id    = $spcms['user_id'];
        $email      = $spclasses->option->get('email', $user_id);
        $phone      = $spclasses->option->get('phone', $user_id);
        $type       = $spclasses->option->get('type', $user_id);
      
        if(!strlen($email)
           || !strlen($phone)) {
            $data = array('status' => 'error');
            echo json_encode($data);
            exit;
        }
			
        // Server
        $server = $spclasses->option->get('server');

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
        
        $result = $api->delete("register",
                               json_encode(['email' => $email, 
                                            'phone' => $phone, 
                                            'user_id' => $user_id, 
                                            'type' => $type]));
        
        if($result->response_status_lines[0] == 'HTTP/1.1 201 Created' || $result->response_status_lines[0] == 'HTTP/1.1 200 OK'
          || (isset($result->response_status_lines[1]) && ($result->response_status_lines[1] == 'HTTP/1.1 201 Created' || $result->response_status_lines[1] == 'HTTP/1.1 200 OK'))) {
            $response = $spclasses->protect->data($result->response, 'json');
            $data = json_decode($response);
            
            if($data->status == 'success') {
                
                // Disable SafePassword
                $spclasses->option->delete('enable_sp', $user_id);
                
                // Email
                $spclasses->option->delete('email', $user_id);
                
                // Phone
                $spclasses->option->delete('phone', $user_id);
                
                // Type
//                $spclasses->option->delete('type', $user_id);
//                
//                // Billed
//                $spclasses->option->delete('billed', $user_id);
//                
//                // Start Date
//                $spclasses->option->delete('start_date', $user_id);
            }
          
            echo $response;
        }
        exit;
    }
  
    function safepassword(){
        global $spsafepwd;
        global $spcms;
        global $spclasses;
        $email      = $spclasses->protect->post('email', 'email');
        $phone      = $spclasses->protect->post('phone');
        $type       = $spclasses->protect->post('type');
            
        if(!strlen($email)
            && $type == 'email') {
            $data = array('status' => 'error',
                          'data' => 'Wrong email address.');
            echo json_encode($data);
            exit;
        }
      
        if(!strlen($phone)
            && $type == 'phone') {
            $data = array('status' => 'error',
                          'data' => 'Wrong phone number.');
            echo json_encode($data);
            exit;
        }
      
        $token = $spclasses->option->get('token');
			
        // Server
        $server = $spclasses->option->get('server');

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
        
        $result = $api->post("safepassword",
                            ['email_or_phone' => $type == 'email' ? $email:$phone]);
        
        if($result->response_status_lines[0] == 'HTTP/1.1 201 Created' || $result->response_status_lines[0] == 'HTTP/1.1 200 OK'
          || (isset($result->response_status_lines[1]) && ($result->response_status_lines[1] == 'HTTP/1.1 201 Created' || $result->response_status_lines[1] == 'HTTP/1.1 200 OK'))) {
            $response = $spclasses->protect->data($result->response, 'json');
            echo $response;
        }
        exit;
    }
  
    function recharge(){
        global $spsafepwd;
        global $spcms;
        global $spclasses;
        $type       = $spclasses->protect->post('type');
        $link_back  = $spclasses->protect->post('link_back');
        $user_id  = $spclasses->protect->post('user_id', 'id');
      
        if(!strlen($type)
           && !strlen($type)) {
            $data = array('status' => 'error');
            echo json_encode($data);
            exit;
        }
      
        $token = $spclasses->option->get('token');
			
        // Server
        $server = $spclasses->option->get('server');

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
        
        $result = $api->post("recharge",
                            ['user_id' => $user_id, 
                             'type' => $type, 
                             'link_back' => $link_back]);
        
        if($result->response_status_lines[0] == 'HTTP/1.1 201 Created' || $result->response_status_lines[0] == 'HTTP/1.1 200 OK'
          || (isset($result->response_status_lines[1]) && ($result->response_status_lines[1] == 'HTTP/1.1 201 Created' || $result->response_status_lines[1] == 'HTTP/1.1 200 OK'))) {
            $response = $spclasses->protect->data($result->response, 'json');
            $data = json_decode($response);
            echo $response;
        }
        exit;
    }
}