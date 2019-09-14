
(function($){
  
  var spsafepwdConnection = {
    start: function(){
        
        if(spsafepwd["server"] === ''
          || spsafepwd["server"] === undefined){
            spsafepwdConnection.server();
        }
    },
    server: function(){
        // Start Loader
        spsafepwdLoader.start(sptext['loading'],
                           sptext['wait']);

        $.post(spsafepwd_request_url, {action: spsafepwd_request['server'],
                                    is_ajax_request: true,
                                    type: 'simple',
                                    user_id: spsafepwd['user_id'],
                                    role: spsafepwd['role']}, function(data){
          data = JSON.parse(data);
          
          if(data.status === 'success') {
                spsafepwd["server"] = data.data;

                // Stop Loader
                spsafepwdLoader.stop(sptext['completed'],
                                  sptext['refresh'],
                                  true);
            } else if(data.status === 'error') {
              var warningMessage = new Array();

              warningMessage.push(sptext['warning_error'].split('%s').join(data.data));
              warningMessage.push('<a href="'+spsafepwd['support_role']+'">'+sptext['warning_contact']+'</a>');

              spsafepwdWarning.start(sptext['warning'],
                                  warningMessage.join(''),
                                  10);
          }
        });
        
    },
    connect: function(step){
        var email = $('#safepwd-connection-email').val(),
            website = $('#safepwd-connection-website').val(),
            referral_id = $('#safepwd-connection-referral-id').val();
        
        if(email.length > 6
          && website.length > 4
          && step.length > 0) {
        
          
            if(!$('#safepwd-terms-and-conditions').is(":checked")) {
                alert(sptext['must_agree']);
                return false;
            }
        
          
            // Localhost not allowed
            if(website.indexOf("localhost") !== -1
               || website.indexOf("127.0.0.1") !== -1) {
                alert(sptext['sp_error_localhost']);
                return false;
            }
          
            if(step === '1'){
                // Start Loader
                spsafepwdLoader.start(sptext['loading'],
                                    sptext['wait']);
            }
                                
            $.post(spsafepwd_request_url, {action: spsafepwd_request['connect'],
                                        is_ajax_request: true,
                                        user_id: spsafepwd['user_id'],
                                        role: spsafepwd['role'],
                                        email: email,
                                        website: website,
                                        referral_id: referral_id,
                                        step: step}, function(data){
                
                data = JSON.parse(data);
              
                if(data.status === 'success') {
                    
                    if(step === '2'){
                        // Stop Loader
                        spsafepwdLoader.stop(sptext['completed'],
                                          sptext['refresh'],
                                          true);

                        // Redirect to your profile page
                        window.location.href = spsafepwd["profile_url"];
                    } else {
                        // Load Step 2
                        spsafepwdConnection.connect('2');
                    }
                } else {
                    var warningMessage = new Array();
                    
                    warningMessage.push(sptext['warning_error'].split('%s').join(data.data));
                    warningMessage.push('<a href="'+spsafepwd['support_role']+'">'+sptext['warning_contact']+'</a>');
                  
                    spsafepwdWarning.start(sptext['warning'],
                                        warningMessage.join(''),
                                        10);
                }
            });
        }
    },
    disconnect: function(){
        
        // Start Loader
        spsafepwdInfo.start(sptext['disconnect'],
                         sptext['no_dashboard'],
                         sptext['button_yes'],
                         sptext['button_no'],
                         spsafepwdConnection.disconnect_ok);
    },
    disconnect_ok: function(){
        
        // Start Loader
        spsafepwdLoader.start(sptext['loading'],
                           sptext['wait']);

        $.post(spsafepwd_request_url, {action: spsafepwd_request['disconnect'],
                                    is_ajax_request: true,
                                    user_id: spsafepwd['user_id'],
                                    role: spsafepwd['role']}, function(data){
            
            // Stop Loader
            spsafepwdLoader.stop(sptext['completed'],
                              sptext['refresh'],
                              true);

            window.location.href = window.location.href;
        });
    }
  };
  
  window.spsafepwdConnection = spsafepwdConnection;
  
  $( document ).ready(function() {
      spsafepwdConnection.start();
  });
  
})(jQuery);