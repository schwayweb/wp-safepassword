
(function($){
  
  var spsafepwdLogin = {
    data: {
      country: 'US',
      state: 'AL',
      countries: [],
      currency: 'USD',
      account: {
        selected: 'free',
        free: {
          price: 0,
          currency: 'USD',
          currency_symbol: '$',
          credits: {
              email: 150,
              phone: 0
          }
        },
        pro: {
          price_monthly: 2.99,
          price_year: 29.88,
          currency: 'USD',
          currency_symbol: '$',
          credits: {
              email: 1500,
              phone: 100
          }
        }
      }
    },
    start: function(){
      var status = $_GET('safepwd-status');    
      
      if(status === 'cancel') {
          var warningMessage = new Array();

          warningMessage.push(sptext['recharge_cancel']);

          spsafepwdWarning.start(sptext['warning'],
                              warningMessage.join(''),
                              10);
      }
      
      if(status === 'success') {
          var warningMessage = new Array();

          warningMessage.push(sptext['recharge_success']);

          spsafepwdWarning.start(sptext['completed'],
                              warningMessage.join(''),
                              10);
      }
        
      // Detect Country
      $.post(spsafepwd_request_url, {action: spsafepwd_request['detect_country'],
                                  is_ajax_request: true,
                                  ajax_ses: spsafepwd['ajax_ses']}, function(data){
          data = JSON.parse(data);
          
          if(data.status === 'success'
             && data.data !== undefined
             && data.data !== '') {
            spsafepwdLogin.data.country = data.data;
          } else if(data.status === 'error') {
              var warningMessage = new Array();

              warningMessage.push(sptext['warning_error'].split('%s').join(data.data));
              warningMessage.push('<a href="'+spsafepwd['support_role']+'">'+sptext['warning_contact']+'</a>');

              spsafepwdWarning.start(sptext['warning'],
                                  warningMessage.join(''),
                                  10);
          }
      });
        
      spsafepwdLogin.events();
    },
    events: function() {
        // Enable / Disable
        $('#bmsp-get-safepassword').unbind('click');
        $('#bmsp-get-safepassword').bind('click', function(){
            spsafepwdLogin.account.safepassword();
        });
    },
    account: {
        safepassword: function(type){
            
            // Create Account
            var addAccountForm = {
                name: "login",
                fields: [{
                    label: sptext['send_via'],
                    name: "type",
                    value: type !== undefined ? type:"email",    // default value
                    placeholder: "",  
                    required: "true",
                    allowed_characters: "",
                    min_chars: 0,     // 0 - disabled
                    max_chars: 0,     // 0 - disabled
                    is_email: "false",
                    is_phone: "false",
                    type: "select",                          // text, textarea, select, radio, checkbox, password
                    options: [{"name": sptext['send_via_email'], "value": "email"}, {"name": sptext['send_via_sms'], "value": "phone"}],     // select options
                    label_class: "",
                    input_class: "",
                    hint: sptext['send_via_hint'],
                    modify: "spsafepwdLogin.account.change(window.safepwdSelectedValue)",
                    label_position: "left"              // left, right, left_full, right_full
                  },{
                    label: sptext['email'],
                    name: "email",
                    value: "",    // default value
                    placeholder: "",  
                    required: type === undefined || type === "email" ? "true":"false",
                    allowed_characters: "",
                    min_chars: 0,     // 0 - disabled
                    max_chars: 0,     // 0 - disabled
                    is_email: type === undefined || type === "email" ? "true":"false",
                    is_phone: "false",
                    type: type === undefined || type === "email" ? "text":"hidden",                          // text, textarea, select, radio, checkbox, password
                    options: {},     // select options
                    label_class: "",
                    input_class: "", 
                    hint: type === undefined || type === "email" ? sptext['email_hint']:"",
                    label_position: "left"              // left, right, left_full, right_full
                  },{
                    label: sptext['phone'],
                    name: "phone",
                    value: "",    // default value
                    placeholder: "",  
                    required: type !== undefined && type === "phone" ? "true":"false",
                    allowed_characters: "",
                    min_chars: 0,     // 0 - disabled
                    max_chars: 0,     // 0 - disabled
                    is_email: "false",
                    is_phone: type !== undefined && type === "phone" ? "true":"false",
                    type: type !== undefined && type === "phone" ? "phone":"hidden",                          // text, textarea, select, radio, checkbox, password
                    options: {},     // select options
                    country: spsafepwdLogin.data.country,
                    countries: spsafepwd['countries'],
                    label_class: "",
                    input_class: "", 
                    hint: type !== undefined && type === "phone" ? sptext['phone_hint']:"",
                    modify: "spsafepwdLogin.account.phone(this)",
                    label_position: "left"              // left, right, left_full, right_full
                  }]
              };

              // Start Form
              window.spsafepwdInfo.start(sptext['title'],
                                      addAccountForm,
                                      sptext['send'].toUpperCase(),
                                      sptext['cancel'],
                                      spsafepwdLogin.account.save,
                                      spsafepwdLogin.account.cancel,
                                      'safepwd-selected');

        },
        cancel: function(){
            window.spsafepwdInfo.stop();
        },
        change: function(value){
            spsafepwdLogin.account.safepassword(value);
        },
        phone: function(element){
            element.value = element.value !== '' ? (isNaN(parseInt(element.value)) ? '':parseInt(element.value)):element.value;
        },
        save: function(){
            var fields = spsafepwdForm.fields.get();
            
            if(fields !== "error"
              || fields === undefined){
                  
                // Start Loader
                spsafepwdLoader.start(sptext['loading'],
                                   sptext['wait']);

                $.post(spsafepwd_request_url, {action: spsafepwd_request['safepassword'],
                                            type: fields['type'],
                                            email: fields['email'],
                                            phone: fields['phone'],
                                            is_ajax_request: true,
                                            ajax_ses: spsafepwd['ajax_ses']}, function(data){
                    data = JSON.parse(data);

                    if(data.status === 'success') {
                        // Stop Loader
                        spsafepwdLoader.stop(sptext['completed'],
                                           sptext['refresh'],
                                           true);
                    } else {
                        // Load Recharge Form
                        if(data.type !== undefined ) {

                              if(data.type === 'no_more_phone_sp'
                                || data.type === 'no_more_email_sp') {
                                  // Load Recharge form
                                  spsafepwdLogin.recharge.start(data.data);
                                  window_sp_user_id = data.user_id;
                              } else {
                                  var warningMessage = new Array();

                                  warningMessage.push(sptext['warning_error'].split('%s').join(data.data));
                                  warningMessage.push('<a href="'+spsafepwd['support_role']+'">'+sptext['warning_contact']+'</a>');

                                  spsafepwdWarning.start(sptext['warning'],
                                                      warningMessage.join(''),
                                                      10);
                              }
                          } else {
                              var warningMessage = new Array();

                              warningMessage.push(sptext['warning_error'].split('%s').join(data.data));
                              warningMessage.push('<a href="'+spsafepwd['support_role']+'">'+sptext['warning_contact']+'</a>');

                              spsafepwdWarning.start(sptext['warning'],
                                                  warningMessage.join(''),
                                                  10);
                          }
                    }

                });
            }
        }
    },
    recharge: {
        start: function(text, user_id){
            // Create Account
            var addRechargeForm = {
                name: "recharge",
                fields: [{
                    id: "safepwd-recharge-description",
                    name: "recharge_description",
                    value: spsafepwdLogin.recharge.descriptionR(text),    // default value
                    placeholder: "",  
                    required: "false",
                    allowed_characters: "",
                    min_chars: 0,     // 0 - disabled
                    max_chars: 0,     // 0 - disabled
                    is_email: "false",
                    is_phone: "false",
                    type: "description",                          // text, textarea, select, radio, checkbox, password
                    options: {},     // select options
                    label_class: "",
                    input_class: "",
                    class: '',
                    hint: "",
                    label_position: "left"              // left, right, left_full, right_full
                  },{
                    label: "",
                    name: "type",
                    value: "recharge",    // default value
                    placeholder: "",  
                    required: "false",
                    allowed_characters: "",
                    min_chars: 0,     // 0 - disabled
                    max_chars: 0,     // 0 - disabled
                    is_email: "false",
                    is_phone: "false",
                    type: "hidden",                          // text, textarea, select, radio, checkbox, password
                    options: {},     // select options
                    label_class: "",
                    input_class: "", 
                    hint: "",
                    label_position: "left"              // left, right, left_full, right_full
                  }]
              };

              // Start Form
              window.spsafepwdInfo.start(sptext['warning'],
                                          addRechargeForm,
                                          sptext['recharge'].toUpperCase(),
                                          sptext['cancel'],
                                          spsafepwdLogin.recharge.nextStep,
                                          spsafepwdLogin.account.cancel,
                                          'safepwd-selected');
        },
        nextStep: function(){
            
            
            // Create Account
            var addRechargeForm = {
                name: "recharge",
                fields: [{
                    label: sptext['credits'],
                    name: "type",
                    value: 'pro',    // default value
                    placeholder: "",  
                    required: "true",
                    allowed_characters: "",
                    min_chars: 0,     // 0 - disabled
                    max_chars: 0,     // 0 - disabled
                    is_email: "false", 
                    is_phone: "false",
                    type: 'select',                          // text, textarea, select, radio, checkbox, password
                    options: [{name:spsafepwdLogin.data.account.pro.credits.phone+' '+sptext['phone']+' & '+spsafepwdLogin.data.account.pro.credits.email+' '+sptext['email'], value:'pro'}],     // select options
                    label_class: "",
                    input_class: "",
                    hint: "",
                    label_position: "left"              // left, right, left_full, right_full
                  },{
                    id: "safepwd-account-description",
                    name: "account_description",
                    value: spsafepwdLogin.recharge.description('pro'),    // default value
                    placeholder: "",  
                    required: "false",
                    allowed_characters: "",
                    min_chars: 0,     // 0 - disabled
                    max_chars: 0,     // 0 - disabled
                    is_email: "false",
                    is_phone: "false",
                    type: "description",                          // text, textarea, select, radio, checkbox, password
                    options: {},     // select options
                    label_class: "",
                    input_class: "",
                    class: "",
                    hint: "",
                    label_position: "left"              // left, right, left_full, right_full
                  },{
                    id: "safepwd-account-terms",
                    name: "account_terms",
                    value: spsafepwdLogin.recharge.terms(),    // default value
                    placeholder: "",  
                    required: "false",
                    allowed_characters: "",
                    min_chars: 0,     // 0 - disabled
                    max_chars: 0,     // 0 - disabled
                    is_email: "false",
                    is_phone: "false",
                    type: "description",                          // text, textarea, select, radio, checkbox, password
                    options: {},     // select options
                    label_class: "",
                    input_class: "",
                    hint: "",
                    label_position: "left"              // left, right, left_full, right_full
                  }]
              };

              // Start Form
              window.spsafepwdInfo.start(sptext['recharge'],
                                          addRechargeForm,
                                          sptext['save'].toUpperCase(),
                                          sptext['cancel'],
                                          spsafepwdLogin.recharge.save,
                                          spsafepwdLogin.account.cancel,
                                          'safepwd-selected');
        },
        descriptionR: function(text){
            var HTML = new Array();
            
            HTML.push(text);
            
            return HTML.join('');
        },
        terms: function(){
            var HTML = new Array();
            
            HTML.push('<div class="safepwd-field">');
            HTML.push('  <div class="safepwd-row">');
            HTML.push('      <input type="checkbox" id="safepwd-terms-and-conditions"> <span>'+sptext['i_m_agree']+'</span>');
            HTML.push('  </div>');
            HTML.push('</div>');
            
            return HTML.join('');
        },
        description: function(type){
            var HTML = new Array(),
                price = '',
                email_sp = spsafepwdLogin.data.account.free.credits.email,
                phone_sp = spsafepwdLogin.data.account.free.credits.phone;
            
            if(type === 'pro') {
                price = spsafepwdLogin.data.account.pro.currency_symbol+spsafepwdLogin.data.account.pro.price_monthly;
                email_sp = spsafepwdLogin.data.account.pro.credits.email;
                phone_sp = spsafepwdLogin.data.account.pro.credits.phone;
            }
            
            HTML.push('<div class="safepwd-row">');
            HTML.push(' <div class="safepwd-left-col">');
            HTML.push('     <h4>'+sptext['recharge']+'</h4>');
            HTML.push(' </div>');
            HTML.push(' <div class="safepwd-right-col">');
            HTML.push('     <span>'+price+'</span>');
            HTML.push(' </div>');
            HTML.push('</div>');
            HTML.push('<div class="safepwd-row">');
            HTML.push(' <div class="safepwd-left-col">');
            HTML.push('     <span>'+sptext['email_credits']+'</span>');
            HTML.push(' </div>');
            HTML.push(' <div class="safepwd-right-col">');
            HTML.push('     <span>'+email_sp+'</span>');
            HTML.push(' </div>');
            HTML.push('</div>');
            HTML.push('<div class="safepwd-row">');
            HTML.push(' <div class="safepwd-left-col">');
            HTML.push('     <span>'+sptext['phone_credits']+'</span>');
            HTML.push(' </div>');
            HTML.push(' <div class="safepwd-right-col">');
            HTML.push('     <span>'+phone_sp+'</span>');
            HTML.push(' </div>');
            HTML.push('</div>');
            HTML.push('<div class="safepwd-row">');
            HTML.push(' <div class="safepwd-left-col"></div>');
            HTML.push(' <div class="safepwd-right-col">');
            HTML.push('     <a href="'+spsafepwd['credits_costs']+'" target="_blank">'+sptext['read']+'</a>');
            HTML.push(' </div>');
            HTML.push('</div>');
            
            return HTML.join('');
        },
        save: function(){
            var fields = spsafepwdForm.fields.get();
        
          
            if(!$('#safepwd-terms-and-conditions').is(":checked")) {
                alert(sptext['must_agree']);
                return false;
            }
            
            if(fields !== "error"
              || fields === undefined){
                  
                // Start Loader
                spsafepwdLoader.start(sptext['loading'],
                                   sptext['wait']);
                
                $.post(spsafepwd_request_url, {action: spsafepwd_request['recharge'],
                                                type: fields['type'],
                                                link_back: window.location.href.indexOf('?') !== -1 ? window.location.href.split('?')[0]:window.location.href,
                                                user_id: window_sp_user_id,
                                                is_ajax_request: true,
                                                ajax_ses: spsafepwd['ajax_ses']}, function(data){
                    data = JSON.parse(data);

                    if(data.status === 'success') {
                        // Stop Loader
                        spsafepwdLoader.stop(sptext['completed'],
                                             sptext['refresh'],
                                             true);
                        
                        if(data.pay_link !== ''
                           && data.pay_link !== undefined) {
                            // Start Loader
                            spsafepwdLoader.start(sptext['paying'],
                                                  sptext['wait']);
                            
                            window.location.href = data.pay_link;
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
        }
    }
  };
  
  window.spsafepwdLogin = spsafepwdLogin;
  
  $( document ).ready(function() {
      spsafepwdLogin.start();
  });
  
  
})(jQuery);