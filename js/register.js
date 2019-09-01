
(function($){
  
  var spsafepwdRegister = {
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
      var status = spsafepwd["status"] !== undefined ? spsafepwd["status"]:'';    
      
      if(status === 'cancel') {
          var warningMessage = new Array();

          warningMessage.push(sptext['error_cancel_subscription']);

          spsafepwdWarning.start(sptext['warning'],
                              warningMessage.join(''),
                              10);
      }
      
      if(status === 'success') {
          var warningMessage = new Array();

          warningMessage.push(sptext['account_success']);

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
            spsafepwdRegister.data.country = data.data;
          } else if(data.status === 'error') {
              var warningMessage = new Array();

              warningMessage.push(sptext['warning_error'].split('%s').join(data.data));
              warningMessage.push('<a href="'+spsafepwd['support_role']+'">'+sptext['warning_contact']+'</a>');

              spsafepwdWarning.start(sptext['warning'],
                                  warningMessage.join(''),
                                  10);
          }
      });
        
      spsafepwdRegister.events();
    },
    events: function() {
        // Enable / Disable
        $('.bmsp-register-btn').unbind('click');
        $('.bmsp-register-btn').bind('click', function(){
            var email = $(this).attr('data-user-email'),
                phone = $(this).attr('data-user-phone'),
                type  = $(this).prop('checked');
            
            if(type) {
                spsafepwdRegister.account.create(email, phone);
            } else {
                spsafepwdRegister.account.disable(email, phone);
            }
        });
    },
    account: {
        create: function(email,
                         phone){
            
            // Create Account
            var addAccountForm = {
                name: "create_account",
                fields: [{
                    label: sptext['email'],
                    name: "email",
                    value: email !== undefined ? email:"",    // default value
                    placeholder: "",  
                    required: "true",
                    allowed_characters: "",
                    min_chars: 0,     // 0 - disabled
                    max_chars: 0,     // 0 - disabled
                    is_email: "true",
                    is_phone: "false",
                    type: "text",                          // text, textarea, select, radio, checkbox, password
                    options: {},     // select options
                    label_class: "",
                    input_class: "",
                    hint: sptext['email_hint'],
                    label_position: "left"              // left, right, left_full, right_full
                  },{
                    label: sptext['phone'],
                    name: "phone",
                    value: phone !== undefined ? phone:"",    // default value
                    placeholder: "",  
                    required: "true",
                    allowed_characters: "",
                    min_chars: 0,     // 0 - disabled
                    max_chars: 0,     // 0 - disabled
                    is_email: "false",
                    is_phone: "true",
                    type: "phone",                          // text, textarea, select, radio, checkbox, password
                    options: {},     // select options
                    country: spsafepwdRegister.data.country,
                    countries: spsafepwd['countries'],
                    label_class: "",
                    input_class: "",
                    hint: sptext['phone_hint'],
                    modify: "spsafepwdRegister.account.phone(this)",
                    label_position: "left"              // left, right, left_full, right_full
                  },{
                    label: sptext['account_type'],
                    name: "type",
                    value: spsafepwd["account_type"] === undefined || spsafepwd["account_type"] === '' ? 'free':spsafepwd["account_type"],    // default value
                    placeholder: "",  
                    required: "true",
                    allowed_characters: "",
                    min_chars: 0,     // 0 - disabled
                    max_chars: 0,     // 0 - disabled
                    is_email: "false",
                    is_phone: "false",
                    type: spsafepwd["account_type"] === undefined || spsafepwd["account_type"] === '' ? "select":(spsafepwd["account_type"] === 'pro' ? 'hidden':'select'),                          // text, textarea, select, radio, checkbox, password
                    options: [{name:'FREE', value:'free'}, {name:'PRO - '+spsafepwdRegister.data.account.pro.currency_symbol+spsafepwdRegister.data.account.pro.price_monthly+'/'+sptext['monthly'], value:'pro'}, {name:'PRO - '+spsafepwdRegister.data.account.pro.currency_symbol+spsafepwdRegister.data.account.pro.price_year+'/'+sptext['yearly'], value:'pro_year'}],     // select options
                    label_class: "",
                    input_class: "",
                    hint: "",
                    modify: "spsafepwdRegister.account.change(window.safepwdSelectedValue)",
                    label_position: "left"              // left, right, left_full, right_full
                  },{
                    label: "",
                    name: "type_real",
                    value: 'free',    // default value
                    placeholder: "",  
                    required: "true",
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
                  },{
                    label: "",
                    name: "billed",
                    value: 'monthly',    // default value
                    placeholder: "",  
                    required: "true",
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
                  },{
                    label: "",
                    name: "paid",
                    value: (spsafepwd["account_type"] === undefined || spsafepwd["account_type"] === '' ? 'false':(spsafepwd["account_type"] === 'pro' ? 'true':'false')),    // default value
                    placeholder: "",  
                    required: "true",
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
                  },{
                    id: "safepwd-account-description",
                    name: "account_description",
                    value: spsafepwdRegister.account.description((spsafepwd["account_type"] === undefined || spsafepwd["account_type"] === '' ? 'free':(spsafepwd["account_type"] === 'pro' ? 'pro':'free'))),    // default value
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
                    class: (spsafepwd["account_type"] === undefined || spsafepwd["account_type"] === '' ? '':(spsafepwd["account_type"] === 'pro' ? 'safepwd-invisible':'')),
                    hint: "",
                    label_position: "left"              // left, right, left_full, right_full
                  },{
                    id: "safepwd-account-terms",
                    name: "account_terms",
                    value: spsafepwdRegister.account.terms(),    // default value
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
              window.spsafepwdInfo.start(sptext['registration'],
                                      addAccountForm,
                                      sptext['save'].toUpperCase(),
                                      sptext['cancel'],
                                      spsafepwdRegister.account.save,
                                      spsafepwdRegister.account.cancel,
                                      'safepwd-selected');

        },
        cancel: function(){
            $('.bmsp-register-btn').prop('checked', false);
            window.spsafepwdInfo.stop();
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
                email_sp = spsafepwdRegister.data.account.free.credits.email,
                phone_sp = spsafepwdRegister.data.account.free.credits.phone;
            
            if(type === 'pro') {
                price = spsafepwdRegister.data.account.pro.currency_symbol+spsafepwdRegister.data.account.pro.price_monthly+' - '+sptext['monthly'];
                email_sp = spsafepwdRegister.data.account.pro.credits.email;
                phone_sp = spsafepwdRegister.data.account.pro.credits.phone;
                $('#safepwd-form-create_account-billed').val('monthly');
            }
            
            if(type === 'pro_year') {
                price = spsafepwdRegister.data.account.pro.currency_symbol+spsafepwdRegister.data.account.pro.price_year+' - '+sptext['yearly'];
                email_sp = spsafepwdRegister.data.account.pro.credits.email;
                phone_sp = spsafepwdRegister.data.account.pro.credits.phone;
                type     = type.split('_year').join('');
                $('#safepwd-form-create_account-billed').val('yearly');
            }
            
            HTML.push('<div class="safepwd-row">');
            HTML.push(' <div class="safepwd-left-col">');
            HTML.push('     <h4>'+type+'</h4>');
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
        change: function(value){
            var tempValue = value;
            
            if(tempValue !== 'free') {
                tempValue = 'pro';
            }
            $('#safepwd-form-create_account-type_real').val(tempValue);
            $('#safepwd-account-description').html(spsafepwdRegister.account.description(value));
        },
        phone: function(element){
            element.value = element.value !== '' ? (isNaN(parseInt(element.value)) ? '':parseInt(element.value)):element.value;
        },
        disable: function(email,
                          phone){
            // Start Loader
            spsafepwdLoader.start(sptext['loading'],
                               sptext['wait']);

            $.post(spsafepwd_request_url, {action: spsafepwd_request['unregister'],
                                        is_ajax_request: true,
                                        ajax_ses: spsafepwd['ajax_ses']}, function(data){
                data = JSON.parse(data);

                if(data.status === 'success') {
                    // Stop Loader
                    spsafepwdLoader.stop(sptext['completed'],
                                       sptext['refresh'],
                                       true);
                    
                    if(window.bmsp_qrcode !== undefined) {
                       window.bmsp_qrcode.clear(); 
                    }
                    
                    $('#bmsp-qrcode').html('').addClass('bmsp-invisible');
                    $('#bmsp-qrcode-description').addClass('bmsp-invisible');
                } else {
                      var warningMessage = new Array();

                      warningMessage.push(sptext['warning_error'].split('%s').join(data.data));
                      warningMessage.push('<a href="'+spsafepwd['support_role']+'">'+sptext['warning_contact']+'</a>');

                      spsafepwdWarning.start(sptext['warning'],
                                          warningMessage.join(''),
                                          10);
                }

            });
        },
        save: function(){
            var fields = spsafepwdForm.fields.get(),
                sync_text = [];
        
          
            if(!$('#safepwd-terms-and-conditions').is(":checked")) {
                alert(sptext['must_agree']);
                return false;
            }
            
            if(fields !== "error"
              || fields === undefined){
                  
                // Start Loader
                spsafepwdLoader.start(sptext['loading'],
                                   sptext['wait']);
                
                $.post(spsafepwd_request_url, {action: spsafepwd_request['register'],
                                            type: fields['type_real'],
                                            billed: fields['billed'],
                                            paid: fields['paid'],
                                            email: fields['email'],
                                            phone: fields['phone'],
                                            link_back: window.location.href,
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
        },
        sync: function(){
//            sync_text.push(data.website);
//            sync_text.push(spsafepwd['api_url']);
//            sync_text.push(data.sync_key1);
//            sync_text.push(data.sync_key2);
//            sync_text.push(data.sync_key3);
//            sync_text.push(data.sync_key4);
//            sync_text.push(data.sync_key5);
//            sync_text.push(data.sync_key6);
//            sync_text.push(data.sync_key7);
//            sync_text.push(data.sync_key8);
//            sync_text.push(data.sync_key9);
//            sync_text.push(data.sync_key10);
//
//            var qrcode = new QRCode("bmsp-qrcode", {
//                text: sync_text.join('@'),
//                width: 180,
//                height: 180,
//                colorDark : "#000000",
//                colorLight : "#ffffff",
//                correctLevel : QRCode.CorrectLevel.H
//            });
//
//            window.bmsp_qrcode = qrcode;
//
//            $('#bmsp-qrcode').removeClass('bmsp-invisible')
//            $('#bmsp-qrcode-description').removeClass('bmsp-invisible');
        }
    }
  };
  
  window.spsafepwdRegister = spsafepwdRegister;
  
  $( document ).ready(function() {
      spsafepwdRegister.start();
  });
  
  
})(jQuery);