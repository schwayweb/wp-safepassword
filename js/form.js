(function($){
  
var placeSearch, 
    autocomplete;
  
  /* 
   * Forms
   */
  var spsafepwdForm = {
      data: {},
      errors: 0,
      start: function(box, data){
          spsafepwdForm['data'] = data;
          box.html(spsafepwdForm.generate(data));
          spsafepwdForm.events();
      },
      generate: function(data){
          return spsafepwdForm.fields.generate(data);
      },
      events: function(){
          spsafepwdForm.fields.events();
      },
      fields: {
          get: function(){
              var myFields = spsafepwdForm['data']['fields'];
                  fieldsData = [];
              spsafepwdForm['erros'] = 0;
              $('.safepwd-errors').addClass('safepwd-invisible');
              $('div.safepwd-error').addClass('safepwd-invisible');
              $('.safepwd-input').removeClass('safepwd-error');
              $('.safepwd-textarea').removeClass('safepwd-error');
              $('.safepwd-select').removeClass('safepwd-error');
              
              for(var i = 0; i < myFields.length; i++) {
                  fieldsData[myFields[i]['name']] = spsafepwdForm.fields.field.get(spsafepwdForm['data']['name'], myFields[i]);
                  
                  if(fieldsData[myFields[i]['name']] === -999999999999999) {
                      return 'error';  
                  }
              }
              
              return fieldsData;
          },
          generate: function(data){
              var HTML = new Array();
              
              for(var i = 0; i < data['fields'].length; i++) {
                  HTML.push(spsafepwdForm.fields.field.generate(data['name'], data['fields'][i]));
              }
              
              return HTML.join('');
          },
          events: function(){
              spsafepwdForm.fields.field.events();
          },
          field: {
              generate: function(formName, data){
                  var HTML = new Array();
                  // safepwd-invisible
                  
                  if(data['type'] !== 'hidden') {
                      if(data['type'] === 'price_added'
                        && data['name'] === 'general_monthly_price') {
                          HTML.push('<div class="safepwd-more-price">'+sptext['more_prices']+'...</div>');
                      }
                      HTML.push('<div class="safepwd-field'+(data['type'] === 'price_added' && formName === 'availability_general' ? ' safepwd-price-box-add-content safepwd-invisible':'')+(data['field_class'] !== undefined ? ' '+data['field_class']:'')+'">');
                  }
                  
                  if(data['label_position'] === 'left') {
                    
                    // Label
                    if(data['type'] !== 'hidden'
                      && data['type'] !== 'button'
                      && data['type'] !== 'bullets'
                      && data['type'] !== 'description'){
                      
                      if(data['type'] === 'price' && formName === 'availability_general'
                        || data['type'] === 'price_added' && formName === 'availability_general'){
                          HTML.push('     <div class="safepwd-price-box-add">');
                        
                          if(data['type'] === 'price_added'){
                              HTML.push('     <div class="safepwd-price-box-add-plus '+(parseFloat(data['value']) > 0 ? ' safepwd-opened':'')+'" onclick="'+(data['enabling'] !== undefined ? data['enabling']:'javascript:void(0);')+'"><span class="safepwd-added">+</span><span class="safepwd-remove">-</span></div>');
                          }
                          HTML.push('     </div>');
                      }
                      
                      if(data['label'] !== undefined) {
                          HTML.push('   <label for="safepwd-form-'+formName+'-'+data['name']+'" class="safepwd-label '+(data['type'] === 'price' ? 'safepwd-price-label':'')+' '+(data['type'] === 'price_added' ? 'safepwd-price-label':'')+' '+data['label_class']+'">'+data['label']+' '+(data['required'] === 'true' ? '<span>*</span>':'')+'</label>');
                      }
                    }
                    
                    switch(data['type']) {
                        case "phone":
                          HTML.push(' <div class="wdh-box-full">');
                          HTML.push('   <div id="safepwd-form-'+formName+'-'+data['name']+'-select" class="safepwd-noselect-text wdh-box-1-3 safepwd-select"><span class="wdh-safepwd-flag wdh-safepwd-flag-'+data['country'].toLowerCase()+'"></span>');
                          HTML.push('     <div class="safepwd-select-icon"><span></span></div>');
                          HTML.push('     <ul id="safepwd-form-'+formName+'-'+data['name']+'-select-options" class="safepwd-select-options safepwd-phone">');
                          
                          for(var key in data['countries']) {
                              HTML.push('   <li data-value="'+key+'" class="'+(key === data['country'] ? 'safepwd-selected':'')+'"><span class="wdh-safepwd-flag wdh-safepwd-flag-'+key.toLowerCase()+'"></span> '+data['countries'][key]+'</li>');
                          }
                          HTML.push('     </ul>');
                          HTML.push('   </div>');
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'-country-code" type="hidden" class="safepwd-input '+data['input_class']+'" '+(data['modify'] !== undefined ? 'data-change="'+data['modify']+'"':'')+' value="'+data['country']+'" />');
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" '+(data['max_chars'] > 0 ? ' maxlength="'+data['max_chars']+'"':'')+' '+(data['modify'] !== undefined ? 'onchange="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onkeyup="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onblur="'+data['modify']+'"':'')+' type="text" class="safepwd-input wdh-box-2-3 '+data['input_class']+'" placeholder="'+data['placeholder']+'" value="'+data['value']+'" />');
                          HTML.push(' </div>');
                          break;
                        case "country_state":
                          HTML.push(' <div class="wdh-box-full">');
                          HTML.push('   <div id="safepwd-form-'+formName+'-'+data['name']+'-select" class="safepwd-noselect-text wdh-country wdh-box-1-3 safepwd-select"><span class="wdh-safepwd-flag wdh-safepwd-flag-'+data['country'].toLowerCase()+'"></span><span class="wdh-country-full">'+data['countries'][data['country']]+'</span>');
                          HTML.push('     <div class="safepwd-select-icon"><span></span></div>');
                          HTML.push('     <ul id="safepwd-form-'+formName+'-'+data['name']+'-select-options" class="safepwd-select-options safepwd-country">');
                          
                          for(var key in data['countries']) {
                              HTML.push('   <li data-value="'+key+'" class="'+(key === data['country'] ? 'safepwd-selected':'')+'"><span class="wdh-safepwd-flag wdh-safepwd-flag-'+key.toLowerCase()+'"></span> '+data['countries'][key]+'</li>');
                          }
                          HTML.push('     </ul>');
                          HTML.push('   </div>');
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'-country-code" type="hidden" class="safepwd-input '+data['input_class']+'" '+(data['modify'] !== undefined ? 'data-change="'+data['modify']+'"':'')+' value="'+data['country']+'" />');
                          HTML.push(' </div>');
                          HTML.push(' <div class="wdh-state-full-box safepwd-invisible">');
                              HTML.push(' <label for="safepwd-form-'+formName+'-'+data['name']+'-state-code-select" class="safepwd-label '+data['label_class']+'">'+sptext['state']+'<span>*</span></label>');
                              HTML.push(' <div class="wdh-box-full">');
                              HTML.push('   <div id="safepwd-form-'+formName+'-'+data['name']+'-state-code-select" class="safepwd-noselect-text wdh-state safepwd-select"><span class="safepwd-state-show">'+spsafepwdForm.fields.field.option.name(data['states'][0]['value'],data['states'])+'</span>');
                              HTML.push('     <div class="safepwd-select-icon"><span></span></div>');
                              HTML.push('     <ul id="safepwd-form-'+formName+'-'+data['name']+'-state-code-select-options" class="safepwd-select-options safepwd-state">');

                              for(var key in data['states']) {
                                  HTML.push('   <li data-value="'+data['states'][key]['value']+'" class="'+(data['states'][key]['value'] === data['state'] ? 'safepwd-selected':'')+'">'+data['states'][key]['name']+'</li>');
                              }
                              HTML.push('     </ul>');
                              HTML.push('   </div>');
                              HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'-state-code" type="hidden" class="safepwd-input '+data['input_class']+'" '+(data['modify'] !== undefined ? 'data-change="'+data['modify']+'"':'')+' value="'+data['state']+'" />');
                              HTML.push(' </div>');
                          HTML.push(' </div>');
                          HTML.push(' <div class="wdh-vat-full-box safepwd-invisible">');
                          HTML.push('   <div class="wdh-vat-full-box-left">'+sptext['pro_account']+'<br><span class="safepwd-vat-name"></span> (<span class="safepwd-vat-location"></span> - <span class="safepwd-vat-rate"></span>%)</div>');
                          HTML.push('   <div class="wdh-vat-full-box-right">'+spsafepwdRegister.data.account.pro.currency_symbol+spsafepwdRegister.data.account.pro.price+'<br><span class="safepwd-vat-amount"></div>');
                          HTML.push('   <div class="wdh-box-full-all">');
                          HTML.push('     <div class="wdh-vat-full-box-left-second">'+sptext['pro_account_total']+'</div>');
                          HTML.push('     <div class="wdh-vat-full-box-right-second"><span class="safepwd-vat-total"></div>');
                          HTML.push('   </div>');
                          HTML.push(' </div>');
                          break;
                        case "select":
                          HTML.push('   <div id="safepwd-form-'+formName+'-'+data['name']+'-select" class="safepwd-noselect-text safepwd-select"><span>'+spsafepwdForm.fields.field.option.name(data['value'],data['options'])+'</span>');
                          HTML.push('     <div class="safepwd-select-icon"><span></span></div>');
                          HTML.push('     <ul id="safepwd-form-'+formName+'-'+data['name']+'-select-options" class="safepwd-select-options">');
                          
                          for(var key in data['options']) {
                              HTML.push('   <li data-value="'+data['options'][key]['value']+'" class="'+(data['options'][key]['value'] === data['value'] ? 'safepwd-selected':'')+'">'+data['options'][key]['name']+'</li>');
                          }
                          HTML.push('     </ul>');
                          HTML.push('   </div>');
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" type="hidden" class="safepwd-input '+data['input_class']+'"" '+(data['modify'] !== undefined ? 'data-change="'+data['modify']+'"':'')+' value="'+data['value']+'" />');
                          break;
                        case "checkbox":
                          HTML.push('   <div id="safepwd-form-'+formName+'-'+data['name']+'-checkbox" class="safepwd-checkboxes '+((data['checkboxes_class'] !== undefined) ? data['checkboxes_class']:'')+'">');
                          
                          for(var key in data['options']) {
                            HTML.push('     <div id="safepwd-form-'+formName+'-'+data['name']+'-checkbox-'+data['options'][key]['value']+'" class="safepwd-checkbox-multiple'+(data['options'][key]['value'] === "true" ? ' safepwd-checked':'')+'" data-value="'+data['options'][key]['value']+'"></div>');
                            HTML.push('     <label id="safepwd-form-'+formName+'-'+data['name']+'-checkbox-'+data['options'][key]['value']+'-label" for="safepwd-form-'+formName+'-'+data['name']+'-checkbox-'+data['options'][key]['value']+'-checkbox" class="safepwd-checkbox-label safepwd-checkbox-multiple-label">'+data['options'][key]['name']+'</label>');
                          }
                          HTML.push('     <input id="safepwd-form-'+formName+'-'+data['name']+'-checkbox" type="hidden" class="safepwd-input '+data['input_class']+'" value="" />');
                          HTML.push('   </div>');
                          break;
                        case "terms":
                            HTML.push('     <div id="safepwd-form-'+formName+'-'+data['name']+'-checkbox" class="safepwd-checkbox '+data['input_class']+(data['value'] === "true" ? ' safepwd-checked':'')+'"></div>');
                            HTML.push('     <input id="safepwd-form-'+formName+'-'+data['name']+'" type="hidden" class="safepwd-input" value="'+data['value']+'" />');
                          break;
                        case "textarea":
                          HTML.push('   <textarea id="safepwd-form-'+formName+'-'+data['name']+'" '+(data['max_chars'] > 0 ? ' maxlength="'+data['max_chars']+'"':'')+' '+(data['modify'] !== undefined ? 'onchange="'+data['modify']+'"':'')+' '+(data['is_read_only'] !== undefined && data['is_read_only'] === 'true' ? 'readonly':'')+' class="safepwd-textarea '+data['input_class']+'">'+data['value']+'</textarea>');
                          
                          if(data['html'] !== undefined) {
                              HTML.push('   '+data['html']);
                          }
                          
                          break;
                        case "hidden":
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" type="hidden" '+(data['modify'] !== undefined ? 'onchange="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onkeyup="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onblur="'+data['modify']+'"':'')+' value="'+data['value']+'" />');
                          break;
                        case "image":
                          HTML.push('   <input id="safepwd-image-upload" '+(data['modify'] !== undefined ? 'data-modify="'+data['modify']+'"':'')+' type="file" style="width: 60%;overflow: hidden;" />');
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" type="hidden" class="safepwd-image-upload-data" '+(data['modify'] !== undefined ? 'onchange="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onkeyup="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onblur="'+data['modify']+'"':'')+' />');
                          HTML.push('   <div class="wdh-box-full-full safepwd-image-upload-preview-box">');
                          HTML.push('     <img style="max-height:80px;" '+(data['value'] !== undefined && data['value'] !== '' ? 'src="'+data['value']+'"':'')+' class="safepwd-image-upload-preview">');
                          HTML.push('   </div>');
                          break;
                        case "button":
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" class="safepwd-button" type="button" value="'+data['label']+'" onclick="javascript:'+data['action']+'" />');
                          break;
                        case "map":
                        
                          if(data['value'] !== undefined
                            && data['value'] !== '') {
                              data['value']['latitude'] = data['value']['lat'];
                              data['value']['longitude'] = data['value']['lng'];
                              data['value']['address'] = data['value']['name'];
                              var tempLocation = JSON.stringify(data['value']),
                                  tempLocationName = data['value']['name'];

                              window.wdhMapDataLocation = data['value'];
                          }
                          
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'-map" '+(data['max_chars'] > 0 ? ' maxlength="'+data['max_chars']+'"':'')+' '+(data['modify'] !== undefined ? 'onchange="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onkeyup="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onblur="'+data['modify']+'"':'')+' type="text" class="safepwd-input safepwd-map '+data['input_class']+'" placeholder="'+data['placeholder']+'" value="'+(tempLocationName !== undefined ? tempLocationName:'')+'" />');
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" type="hidden" value="'+(tempLocation !== undefined ? tempLocation:'')+'" />');
                          break;
                        case "switch":
                          HTML.push('   <label id="safepwd-form-'+formName+'-'+data['name']+'-switch" class="safepwd-switch">');
                          HTML.push('     <input id="safepwd-form-'+formName+'-'+data['name']+'" type="checkbox" class="safepwd-checkbox '+data['input_class']+'"" '+(data['modify'] !== undefined ? 'onclick="'+data['modify']+'"':'')+' '+(data['value'] === 'true' ? 'checked="checked"':'')+' />');
                          HTML.push('     <div class="safepwd-slider safepwd-round"></div>');
                          HTML.push('   </label>');
                          break;
                        case "password":
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" '+(data['max_chars'] > 0 ? ' maxlength="'+data['max_chars']+'"':'')+' '+(data['modify'] !== undefined ? 'onchange="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onkeyup="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onblur="'+data['modify']+'"':'')+' type="password" class="safepwd-input '+data['input_class']+'" placeholder="'+data['placeholder']+'" value="'+data['value']+'" />');
                          break;
                        case "date":
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'-date" type="text" class="safepwd-input safepwd-date '+data['input_class']+'" placeholder="'+data['placeholder']+'" value="'+data['value']+'" />');
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" type="hidden" class="safepwd-input '+data['input_class']+'" placeholder="'+data['placeholder']+'" value="'+data['value']+'" />');
                          break;
                        case "bullets":
                          HTML.push('   <div class="safepwd-bullets">');
                            
                          for(var bulletKey = 1; bulletKey <= data['bullets']; bulletKey++) {
                            HTML.push('   <div class="safepwd-bullet '+(data['selected_bullet'] === bulletKey ? 'safepwd-selected_bullet':'')+'"></div>');
                          }
                          HTML.push('   </div>');
                          break;
                        case "description":
                          HTML.push('   <div id="'+data['id']+'" class="safepwd-form-description '+data['class']+'">');
                          HTML.push(        data['value']);
                          HTML.push('   </div>');
                          break;
                        default:
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" '+(data['max_chars'] > 0 ? ' maxlength="'+data['max_chars']+'"':'')+' '+(data['is_read_only'] !== undefined && data['is_read_only'] === 'true' ? 'readonly':'')+' '+(data['modify'] !== undefined ? 'onchange="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onkeyup="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onblur="'+data['modify']+'"':'')+' type="text" class="safepwd-input '+data['input_class']+'" placeholder="'+data['placeholder']+'" value="'+data['value']+'" />');
                          break;
                    }
                    
                    // Info
                    if(data['hint'] !== undefined
                       && data['hint'] !== '') {
                        HTML.push('   <div class="safepwd-info '+(data['type'] === 'switch' ? 'safepwd-margin-left-10':'')+'">');
                        HTML.push('       ?');
                        HTML.push('       <div class="safepwd-info-box"><span class="safepwd-info-title">'+sptext['hint']+'</span><span class="safepwd-info-content">'+data['hint']+'</span></div>');
                        HTML.push('   </div>');
                    }
                    
                  } else {
                    
                    switch(data['type']) {
                        case "select":
                          HTML.push('   <div id="safepwd-form-'+formName+'-'+data['name']+'-select" class="safepwd-noselect-text safepwd-select"><span>'+spsafepwdForm.fields.field.option.name(data['value'],data['options'])+'</span>');
                          HTML.push('     <div class="safepwd-select-icon"><span></span></div>');
                          HTML.push('     <ul id="safepwd-form-'+formName+'-'+data['name']+'-select-options" class="safepwd-select-options">');
                          
                          for(var key in data['options']) {
                              HTML.push('   <li data-value="'+data['options'][key]['value']+'" class="'+(data['options'][key]['value'] === data['value'] ? 'safepwd-selected':'')+'">'+data['options'][key]['name']+'</li>');
                          }
                          HTML.push('     </ul>');
                          HTML.push('   </div>');
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" type="hidden" class="safepwd-input '+data['input_class']+'"" '+(data['modify'] !== undefined ? 'onchange="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onkeyup="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onblur="'+data['modify']+'"':'')+' value="'+data['value']+'" />');
                          break;
                        case "checkbox":
                          HTML.push('   <div class="safepwd-checkboxes '+((data['checkboxes_class'] !== undefined) ? data['checkboxes_class']:'')+'">');
                        
                          for(var key in data['options']) {
                            HTML.push('     <div id="safepwd-form-'+formName+'-'+data['name']+'-checkbox-'+data['options'][key]['name']+'-checkbox" class="safepwd-checkbox'+(data['options'][key]['value'] === "true" ? ' safepwd-checked':'')+'"></div>');
                            HTML.push('     <input id="safepwd-form-'+formName+'-'+data['name']+'-checkbox-'+data['options'][key]['name']+'" type="hidden" class="safepwd-input '+data['input_class']+'" value="'+data['options'][key]['value']+'" />');
                          }
                          HTML.push('   </div>');
                          break;
                        case "terms":
                            HTML.push('     <div id="safepwd-form-'+formName+'-'+data['name']+'-checkbox" class="safepwd-checkbox '+data['input_class']+(data['value']=== "true" ? ' safepwd-checked':'')+'"></div>');
                            HTML.push('     <input id="safepwd-form-'+formName+'-'+data['name']+'" type="hidden" class="safepwd-input" value="'+data['value']+'" />');
                          break;
                        case "textarea":
                          HTML.push('   <textarea id="safepwd-form-'+formName+'-'+data['name']+'" '+(data['max_chars'] > 0 ? ' maxlength="'+data['max_chars']+'"':'')+' '+(data['modify'] !== undefined ? 'onchange="'+data['modify']+'"':'')+' '+(data['is_read_only'] !== undefined && data['is_read_only'] === 'true' ? 'readonly':'')+' class="safepwd-textarea '+data['input_class']+'">'+data['value']+'</textarea>');
                          
                          if(data['html'] !== undefined) {
                              HTML.push('   '+data['html']);
                          }
                          
                          break;
                        case "hidden":
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" type="hidden" '+(data['modify'] !== undefined ? 'onchange="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onkeyup="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onblur="'+data['modify']+'"':'')+' value="'+data['value']+'" />');
                          break;
                        case "button":
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" class="safepwd-button" type="button" value="'+data['label']+'" onclick="javascript:'+data['action']+'" />');
                          break;
                        case "map":
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" '+(data['max_chars'] > 0 ? ' maxlength="'+data['max_chars']+'"':'')+' type="text" '+(data['modify'] !== undefined ? 'onchange="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onkeyup="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onblur="'+data['modify']+'"':'')+' class="safepwd-input safepwd-map '+data['input_class']+'" placeholder="'+data['placeholder']+'" value="'+data['value']+'" />');
                          break;
                        case "image":
                          HTML.push('   <input id="safepwd-image-upload" type="file" style="width: 60%;overflow: hidden;" />');
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" type="hidden" class="safepwd-image-upload-data" '+(data['modify'] !== undefined ? 'onchange="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onkeyup="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onblur="'+data['modify']+'"':'')+' />');
                          HTML.push('   <div class="wdh-box-full-full safepwd-image-upload-preview-box">');
                          HTML.push('     <img style="max-height:80px;" '+(data['value'] !== undefined && data['value'] !== '' ? 'src="'+data['value']+'"':'')+' class="safepwd-image-upload-preview">');
                          HTML.push('   </div>');
                          break;
                        case "switch":
                          HTML.push('   <label id="safepwd-form-'+formName+'-'+data['name']+'-switch" class="safepwd-switch">');
                          HTML.push('     <input id="safepwd-form-'+formName+'-'+data['name']+'" type="checkbox" class="safepwd-checkbox '+data['input_class']+'"" '+(data['modify'] !== undefined ? 'onclick="'+data['modify']+'"':'')+' '+(data['value'] === 'true' ? 'checked="checked"':'')+' />');
                          HTML.push('     <div class="safepwd-slider safepwd-round"></div>');
                          HTML.push('   </label>');
                          break;
                        case "pasword":
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" '+(data['max_chars'] > 0 ? ' maxlength="'+data['max_chars']+'"':'')+' type="password" '+(data['modify'] !== undefined ? 'onchange="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onkeyup="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onblur="'+data['modify']+'"':'')+' class="safepwd-input '+data['input_class']+'" placeholder="'+data['placeholder']+'" value="'+data['value']+'" />');
                          break;
                        case "date":
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'-date" type="text" class="safepwd-input safepwd-date '+data['input_class']+'" placeholder="'+data['placeholder']+'" value="'+data['value']+'" />');
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" type="hidden" class="safepwd-input '+data['input_class']+'" placeholder="'+data['placeholder']+'" value="'+data['value']+'" />');
                          break;
                        case "bullets":
                          HTML.push('   <div class="safepwd-bullets">');
                            
                          for(var bulletKey = 1; bulletKey <= data['bullets']; bulletKey++) {
                            HTML.push('   <div class="safepwd-bullet '+(bulletKey <= data['selected_bullet'] ? 'safepwd-selected_bullet':'')+'"></div>');
                          }
                          HTML.push('   </div>');
                          break;
                        case "description":
                          HTML.push('   <div id="'+data['id']+'" class="safepwd-form-description '+data['class']+'">');
                          HTML.push(        data['value']);
                          HTML.push('   </div>');
                          break;
                        default:
                          HTML.push('   <input id="safepwd-form-'+formName+'-'+data['name']+'" '+(data['max_chars'] > 0 ? ' maxlength="'+data['max_chars']+'"':'')+' '+(data['is_read_only'] !== undefined && data['is_read_only'] === 'true' ? 'readonly':'')+' '+(data['modify'] !== undefined ? 'onchange="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onkeyup="'+data['modify']+'"':'')+' '+(data['modify'] !== undefined ? 'onblur="'+data['modify']+'"':'')+' type="text" class="safepwd-input '+data['input_class']+'" placeholder="'+data['placeholder']+'" value="'+data['value']+'" />');
                          break;
                    }
                    
                    // Info
                    if(data['hint'] !== undefined
                       && data['hint'] !== '') {
                        HTML.push('   <div class="safepwd-info '+(data['type'] === 'switch' ? 'safepwd-margin-left-10':'')+'">');
                        HTML.push('       ?');
                        HTML.push('       <div class="safepwd-info-box"><span class="safepwd-info-title">'+sptext['hint']+'</span><span class="safepwd-info-content">'+data['hint']+'</span></div>');
                        HTML.push('   </div>');
                    }
                    
                    // Label
                    if(data['type'] !== 'hidden'){
                        HTML.push('   <label for="safepwd-form-'+formName+'-'+data['name']+'" class="safepwd-label '+data['label_class']+'">'+data['label']+' '+(data['required'] === 'true' ? '<span>*</span>':'')+'</label>');
                    }
                  }
                
                  HTML.push('   <div id="safepwd-form-'+formName+'-'+data['name']+'-errors" class="safepwd-errors safepwd-invisible">');
                  HTML.push('     <div class="safepwd-error safepwd-error-required safepwd-invisible">'+sptext['error_is_required']+'</div>');
                  HTML.push('     <div class="safepwd-error safepwd-error-email safepwd-invisible">'+sptext['error_is_email']+'</div>');
                  HTML.push('     <div class="safepwd-error safepwd-error-phone safepwd-invisible">'+sptext['error_is_phone']+'</div>');
                  HTML.push('     <div class="safepwd-error safepwd-error-iban safepwd-invisible">'+sptext['error_is_iban']+'</div>');
                  HTML.push('     <div class="safepwd-error safepwd-error-swift safepwd-invisible">'+sptext['error_is_swift']+'</div>');
                  
                  if(data['lower_than'] !== undefined) {
                      HTML.push('     <div class="safepwd-error safepwd-error-lower safepwd-invisible">'+sptext['error_is_lower_than']+'</div>');
                  }
                
                  if(data['higher_than'] !== undefined) {
                      HTML.push('     <div class="safepwd-error safepwd-error-higher safepwd-invisible">'+sptext['error_is_higher_than']+'</div>');
                  }
                
                  if(data['same_with'] !== undefined) {
                      HTML.push('     <div class="safepwd-error safepwd-error-same-with safepwd-invisible">'+sptext['the_fields']+' <b>'+data['label']+'</b> '+sptext['and']+' <b>'+data['same_with_label']+'</<b> '+sptext['must_be_identique']+'</div>');
                  }
                
                  if(data['exist'] !== undefined) {
                      HTML.push('     <div class="safepwd-error safepwd-error-exist safepwd-invisible">'+sptext['the_field']+' <b>'+data['label']+'</b> '+sptext['already_exist']+'</div>');
                  }
                
                  if(data['is_url'] !== undefined) {
                      HTML.push('     <div class="safepwd-error safepwd-error-url safepwd-invisible">'+sptext['error_invalid_url']+'</div>');
                  }
                
                  if(data['terms'] !== undefined) {
                      HTML.push('     <div class="safepwd-error safepwd-error-terms safepwd-invisible">'+sptext['must_agree']+'</div>');
                  }
                
                  HTML.push('     <div class="safepwd-error safepwd-error-min-chars safepwd-invisible">'+sptext['error_min_chars']+'</div>');
                  HTML.push('     <div class="safepwd-error safepwd-error-allowed-chars safepwd-invisible">'+sptext['error_allowed_characters']+'</div>');
                  HTML.push('   </div>');
                
                  if(data['type'] !== 'hidden') {
                      HTML.push('</div>');
                  }
                
                  return HTML.join('');
              },
              map: function(){
                  var mapinputID = $('.safepwd-map').attr('id');
                  
                  if(mapinputID !== undefined) {
                      autocomplete = new google.maps.places.Autocomplete(document.getElementById(mapinputID), {
                          types: []
                      });
//                           types: ['(cities)']
                    

                      google.maps.event.addListener(autocomplete, 'place_changed', function () {
                          var place = autocomplete.getPlace();
                          
                          if(place !== undefined) {
                              var address = place.formatted_address,
                                  latitude = place.geometry.location.lat(),
                                  longitude = place.geometry.location.lng(),
                                  tempData = {},
                                  mapinputSaveDataID = mapinputID.split('-map')[0];
                            
                              // Get Country & State
                              for(var key in place.address_components){
                                  
                                  if($.inArray('country', place.address_components[key]['types']) > -1){
                                      tempData['country'] = place.address_components[key]['short_name'];
                                      tempData['country_long'] = place.address_components[key]['long_name'];
                                  }
                                  
                                  if($.inArray('administrative_area_level_1', place.address_components[key]['types']) > -1){
                                      tempData['state'] = place.address_components[key]['short_name'];
                                      tempData['state_long'] = place.address_components[key]['long_name'];
                                  }
                                  
                                  if($.inArray('administrative_area_level_2', place.address_components[key]['types']) > -1){
                                      tempData['city'] = place.address_components[key]['short_name'];
                                      tempData['city_long'] = place.address_components[key]['short_name'];
                                  }
                                  
                                  if($.inArray('route', place.address_components[key]['types']) > -1){
                                      tempData['street'] = place.address_components[key]['short_name'];
                                  }
                                  
                                  if($.inArray('street_number', place.address_components[key]['types']) > -1){
                                      tempData['street_no'] = place.address_components[key]['long_name'];
                                  }
                                  
                                  if($.inArray('postal_code', place.address_components[key]['types']) > -1){
                                      tempData['postal_code'] = place.address_components[key]['long_name'];
                                  }
                              }
                            
                              tempData['address'] = address;
                              tempData['latitude'] = latitude;
                              tempData['longitude'] = longitude;
                            
                              window.wdhMapDataLocation = tempData;
                            
                              tempData = JSON.stringify(tempData);
                            
                              $.ajax({
                                 url:"https://maps.googleapis.com/maps/api/timezone/json?key="+spsafepwd["google_map_api_key"]+"&location="+latitude+","+longitude+"&timestamp="+(Math.round((new Date().getTime())/1000)).toString()+"&sensor=false",
                              }).done(function(response){
                                  
                                 if(response.timeZoneId != null){
                                    //do something
                                    var hour=(response.rawOffset)/60;
                                    window.wdhMapDataLocation['timezone'] = response.timeZoneId;
                            
                                    $('#'+mapinputSaveDataID).val(JSON.stringify(window.wdhMapDataLocation));
                                 }
                              });
                            
                              $('#'+mapinputSaveDataID).val(JSON.stringify(window.wdhMapDataLocation));
                          }
                      });
                  }
              },
              image: function(data) {
                var files = data.target.files;
  
                if (files && files[0]) {

                  var FR = new FileReader(),
                      image  = new Image();
                  window.safepwdImageFile = files[0];
                  
                  FR.addEventListener("load", function(e) {
                    var tempData64 = e.target.result;
                    $('.safepwd-image-upload-preview').attr('src', tempData64);
                    var tempImage = new Image(),
                        tempSize = Math.round(window.safepwdImageFile.size/(1024));
                        tempImage.src = tempData64;
                    
                    
                    if(tempSize <= 500) { // Maximum 500KB
                    
                        if ( (/\.(png|jpeg|jpg)$/i).test(window.safepwdImageFile.name) ) {

                            tempImage.onload = function() {

                                if(this.width >= 1024 &&
                                   this.height >= 685){
                                    $('.safepwd-image-upload-preview-box').removeClass('safepwd-invisible');
                                    $('.safepwd-image-upload-data').val(tempData64);
                                    
                                    var dataChange = $('#safepwd-image-upload').attr('data-modify');
                                    window.safepwdSelectedValue = tempData64; 
                                    eval(dataChange);
                                } else {
                                   alert(sptext['error_cover_size']);
                                }
                            };
                        } else {
                            alert(sptext['error_cover_extensions']);
                        }
                    } else {
                        alert(sptext['error_cover_file_size']);
                    }
                    
                    
                  }); 

                  FR.readAsDataURL( this.files[0] );
                }

              },
              option:{
                 name: function(value, options){
                   
                   for(var i = 0; i < options.length; i++){
                     
                      if(options[i] !== undefined) {
                          
                          if(options[i]['value'] === value) {
                              return options[i]['name'];
                          }

                          if(value === '') {
                              value = options[i]['name'];
                          }
                      }
                   }
                   
                   return value;
                     
                 }
              },
              events: function(){
                  
                  $('.safepwd-checkbox-multiple').unbind('click');
                  $('.safepwd-checkbox-multiple').bind('click', function(){
                    var tempCheckbox = $(this).attr('id'),
                        tempCheckboxValuesID = $(this).attr('id').split('-checkbox')[0],
                        tempCheckboxValues = $('#'+tempCheckboxValuesID+'-checkbox').val();
                    
                      if(tempCheckboxValues === '') {
                          tempCheckboxValues = [];
                      } else {
                          tempCheckboxValues = tempCheckboxValues.split('@').join('"');
                          tempCheckboxValues = JSON.parse(tempCheckboxValues);
                      }
                      
                      if($(this).hasClass('safepwd-checked')){
                          $(this).removeClass('safepwd-checked');
                          var newTempCheckboxValues = [];
                          
                          for(var key in tempCheckboxValues) {
                              
                              if($('#'+tempCheckbox).attr('data-value') !== tempCheckboxValues[key]) {
                                  newTempCheckboxValues.push(tempCheckboxValues[key]);
                              }
                          }
                          var checked = JSON.stringify(newTempCheckboxValues);
                              checked = checked.split('"').join('@');
                          $('#'+tempCheckboxValuesID+'-checkbox').val(checked);
                      } else {
                          $(this).addClass('safepwd-checked');
                          
                          if(tempCheckboxValues.indexOf($('#'+tempCheckbox).attr('data-value')) === -1) {
                              tempCheckboxValues.push($('#'+tempCheckbox).attr('data-value'));
                              var checked = JSON.stringify(tempCheckboxValues);
                                  checked = checked.split('"').join('@');
                              $('#'+tempCheckboxValuesID+'-checkbox').val(checked);
                          }
                      }
                  });
                  
                  $('.safepwd-checkbox-multiple-label').unbind('click');
                  $('.safepwd-checkbox-multiple-label').bind('click', function(){
                      var tempCheckbox = $(this).attr('id').split('-label')[0],
                          tempCheckboxValuesID = $(this).attr('id').split('-checkbox')[0],
                          tempCheckboxValues = $('#'+tempCheckboxValuesID+'-checkbox').val();
                    
                      if(tempCheckboxValues === '') {
                          tempCheckboxValues = [];
                      } else {
                          tempCheckboxValues = tempCheckboxValues.split('@').join('"');
                          tempCheckboxValues = JSON.parse(tempCheckboxValues);
                      }
                      
                      if($('#'+tempCheckbox).hasClass('safepwd-checked')){
                          $('#'+tempCheckbox).removeClass('safepwd-checked');
                          var newTempCheckboxValues = [];
                          
                          for(var key in tempCheckboxValues) {
                              
                              if($('#'+tempCheckbox).attr('data-value') !== tempCheckboxValues[key]) {
                                  newTempCheckboxValues.push(tempCheckboxValues[key]);
                              }
                          }
                          var checked = JSON.stringify(newTempCheckboxValues);
                              checked = checked.split('"').join('@');
                          $('#'+tempCheckboxValuesID+'-checkbox').val(checked);
                      } else {
                          $('#'+tempCheckbox).addClass('safepwd-checked');
                          
                          if(tempCheckboxValues.indexOf($('#'+tempCheckbox).attr('data-value')) === -1) {
                              tempCheckboxValues.push($('#'+tempCheckbox).attr('data-value'));
                              var checked = JSON.stringify(tempCheckboxValues);
                                  checked = checked.split('"').join('@');
                              $('#'+tempCheckboxValuesID+'-checkbox').val(checked);
                          }
                      }
                  });
                  
                  $('.safepwd-select').unbind('click');
                  $('.safepwd-select').bind('click', function(){
                      
                      if($(this).hasClass('safepwd-opened')){
                          $(this).removeClass('safepwd-opened');
                      } else {
                          $(this).addClass('safepwd-opened');
                      }
                  });
                
                  // More prices
                  $('.safepwd-more-price').unbind('click');
                  $('.safepwd-more-price').bind('click', function(){
                      $(this).remove();
                      $('.safepwd-price-box-add-content').removeClass('safepwd-invisible');
                  });
                  
                  // Checkbox
                  $('.safepwd-checkbox').unbind('click');
                  $('.safepwd-checkbox').bind('click', function(){
                      var fieldCheckboxID = $(this).attr('id'),
                          fieldID = fieldCheckboxID.split('-checkbox')[0];
                      
                      if($('#'+fieldCheckboxID).hasClass('safepwd-checked')) {
                          $('#'+fieldCheckboxID).removeClass('safepwd-checked');
                          $('#'+fieldID).val('false');
                      } else {
                          $('#'+fieldCheckboxID).addClass('safepwd-checked');
                          $('#'+fieldID).val('true');
                      }
                  });
                
                  // Select
                  $('.safepwd-select-options li').unbind('click');
                  $('.safepwd-select-options li').bind('click', function(){
                      var formID = $(this).parent().attr('id'),
                          idsData = formID.split('safepwd-form-')[1],
                          formName = idsData.split('-select-options')[0].split('-')[0],
                          fieldID = idsData.split('-select-options')[0].split('-')[1];
                    
                      $('.safepwd-select-options li').removeClass('safepwd-selected');
                      $('#safepwd-form-'+formName+'-'+fieldID+'-select span').eq(0).html($(this).html());
                      $('#safepwd-form-'+formName+'-'+fieldID).val($(this).attr('data-value'));
                    
                      var dataChange = $('#safepwd-form-'+formName+'-'+fieldID).attr('data-change');
                      window.safepwdSelectedValue = $(this).attr('data-value'); 
                      eval(dataChange);
                    
                      $(this).addClass('safepwd-selected');
                  });
                
                  // Select Phone country
                  $('.safepwd-select-options.safepwd-phone li').unbind('click');
                  $('.safepwd-select-options.safepwd-phone li').bind('click', function(){
                      var formID = $(this).parent().attr('id'),
                          idsData = formID.split('safepwd-form-')[1],
                          formName = idsData.split('-select-options')[0].split('-')[0],
                          fieldID = idsData.split('-select-options')[0].split('-')[1],
                          tempValue = $(this).attr('data-value');

                      $('.safepwd-select-options.safepwd-phone li').removeClass('safepwd-selected');

                      for(var ckey in spsafepwdRegister['data']['countries']) { 
                          $('#safepwd-form-'+formName+'-'+fieldID+'-select span').eq(0).removeClass('wdh-safepwd-flag-'+ckey.toLowerCase());
                      }

                      $('#safepwd-form-'+formName+'-'+fieldID+'-select span').eq(0).addClass('wdh-safepwd-flag-'+tempValue.toLowerCase());
                      $('#safepwd-form-'+formName+'-'+fieldID+'-country-code').val(tempValue);

                      $('#safepwd-form-'+formName+'-'+fieldID).addClass('safepwd-selected');
                  });
                
                  // Select Phone country
                  $('.safepwd-select-options.safepwd-country li').unbind('click');
                  $('.safepwd-select-options.safepwd-country li').bind('click', function(){
                      var formID = $(this).parent().attr('id'),
                          idsData = formID.split('safepwd-form-')[1],
                          formName = idsData.split('-select-options')[0].split('-')[0],
                          fieldID = idsData.split('-select-options')[0].split('-')[1],
                          tempValue = $(this).attr('data-value');

                      $('.safepwd-select-options.safepwd-country li').removeClass('safepwd-selected');

                      for(var ckey in spsafepwdRegister['data']['countries']) { 
                          $('#safepwd-form-'+formName+'-'+fieldID+'-select span').eq(0).removeClass('wdh-safepwd-flag-'+ckey.toLowerCase());
                      }
                      // wdh-country-full, wdh-safepwd-flag
                      $('#safepwd-form-'+formName+'-'+fieldID+'-select span.wdh-safepwd-flag').eq(0).addClass('wdh-safepwd-flag-'+tempValue.toLowerCase());
                      $('#safepwd-form-'+formName+'-'+fieldID+'-select span.wdh-country-full').eq(0).html(spsafepwdRegister['data']['countries'][tempValue]);
                      $('#safepwd-form-'+formName+'-'+fieldID+'-country-code').val(tempValue);
                    
                      if(tempValue === 'US'){
                          $('.wdh-state-full-box').removeClass('safepwd-invisible');
                      } else {
                          $('.wdh-state-full-box').addClass('safepwd-invisible');
                      }

                      $('#safepwd-form-'+formName+'-'+fieldID).addClass('safepwd-selected');
                    
                      var dataChange = $('#safepwd-form-'+formName+'-'+fieldID+'-country-code').attr('data-change');
                      window.safepwdSelectedValue = $(this).attr('data-value'); 
                      window.safepwdSelectedValueType = 'country'; 
                      eval(dataChange);
                  });
                
                  // Select
                  $('.safepwd-select-options.safepwd-state li').unbind('click');
                  $('.safepwd-select-options.safepwd-state li').bind('click', function(){
                      var formID = $(this).parent().attr('id'),
                          idsData = formID.split('safepwd-form-')[1],
                          formName = idsData.split('-select-options')[0].split('-')[0],
                          fieldID = idsData.split('-select-options')[0].split('-')[1];
                    
                      $('.safepwd-select-options.safepwd-state li').removeClass('safepwd-selected');
                      $('#safepwd-form-'+formName+'-'+fieldID+'-state-code-select span.safepwd-state-show').html($(this).html());
                      $('#safepwd-form-'+formName+'-'+fieldID+'-state-code').val($(this).attr('data-value'));
                    
                      var dataChange = $('#safepwd-form-'+formName+'-'+fieldID+'-state-code').attr('data-change');
                      window.safepwdSelectedValue = $(this).attr('data-value'); 
                      window.safepwdSelectedValueType = 'state'; 
                      eval(dataChange);
                    
                      $(this).addClass('safepwd-selected');
                  });
                  
                  // Map
                  spsafepwdForm.fields.field.map();
                  
                  // Image
                  $('#safepwd-image-upload').on("change", spsafepwdForm.fields.field.image);
                
                  // Datepicker
                  if($('input').hasClass('safepwd-date')) {
                      $('.safepwd-date').datepicker({
                        format: 'yy-mm-dd',
                        minDate: 0,
                        monthNames: [sptext['january'],sptext['february'],sptext['march'],sptext['april'],sptext['may'],sptext['june'],sptext['july'],sptext['august'],sptext['september'],sptext['october'],sptext['november'],sptext['december']],
                        monthNamesShort: [sptext['january'].slice(0,3),sptext['february'].slice(0,3),sptext['march'].slice(0,3),sptext['april'].slice(0,3),sptext['may'].slice(0,3),sptext['june'].slice(0,3),sptext['july'].slice(0,3),sptext['august'].slice(0,3),sptext['september'].slice(0,3),sptext['october'].slice(0,3),sptext['november'].slice(0,3),sptext['december'].slice(0,3)]
                      });

                      $('.safepwd-date').each(function() {
                          var altField = '#'+$(this).attr('id').split('-date')[0],
                              type = $(this).attr('id').indexOf('check_in') !== -1 ? 'check_in':'check_out';

                          $(this).datepicker('option', 'altField', altField);
                          $(this).datepicker('option', 'altFormat', 'yy-mm-dd');
                      });
                  }
              },
              get: function(formName, data){
                  var value = -999999999999999;
                
                  if(data['type'] === 'checkbox') {
                
                      if(spsafepwdForm.fields.field.validation($('#safepwd-form-'+formName+'-'+data['name']+'-checkbox').val(), formName, data)) {
                          value = $('#safepwd-form-'+formName+'-'+data['name']+'-checkbox').val();
                      }
                  } else if(data['type'] === 'phone') {
                      
                      if(spsafepwdForm.fields.field.validation($('#safepwd-form-'+formName+'-'+data['name']).val(), formName, data)) {
                          value = $('#safepwd-form-'+formName+'-'+data['name']+'-country-code').val()+'@'+$('#safepwd-form-'+formName+'-'+data['name']).val();
                      }
                  } else if(data['type'] === 'country_state') {
                      
                      if(spsafepwdForm.fields.field.validation($('#safepwd-form-'+formName+'-'+data['name']+'-country-code').val(), formName, data)) {
                          value = $('#safepwd-form-'+formName+'-'+data['name']+'-country-code').val()+'@'+$('#safepwd-form-'+formName+'-'+data['name']+'-state-code').val();
                      }
                  } else {
                     
                      if(spsafepwdForm.fields.field.validation($('#safepwd-form-'+formName+'-'+data['name']).val(), formName, data)) {
                          value = $('#safepwd-form-'+formName+'-'+data['name']).val();
                      }
                  }
                
                  return value;
              }, 
              validation: function(value, formName, data){
                  var valid = true;
                  
                  // Required
                  if(data['required'] === 'true'
                    && value.length < 1) {
                      spsafepwdForm.fields.field.error($('#safepwd-form-'+formName+'-'+data['name']+'-errors .safepwd-error-required'), sptext['error_is_required']);
                      $('#safepwd-form-'+formName+'-'+data['name']+'-errors').removeClass('safepwd-invisible');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-input').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-textarea').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'-select.safepwd-select').addClass('safepwd-error');
                      spsafepwdForm['erros']++;
                      valid = false;
                  }
                
                  // Email
                  if(data['is_email'] === 'true'
                    && !spsafepwdForm.fields.field.check.is_email(value)
                    && valid) {
                      spsafepwdForm.fields.field.error($('#safepwd-form-'+formName+'-'+data['name']+'-errors .safepwd-error-email'), sptext['error_is_email']);
                      $('#safepwd-form-'+formName+'-'+data['name']+'-errors').removeClass('safepwd-invisible');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-input').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-textarea').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'-select.safepwd-select').addClass('safepwd-error');
                      spsafepwdForm['erros']++;
                      valid = false;
                  }
                
                  // Phone
                  if(data['is_phone'] === 'true'
                    && !spsafepwdForm.fields.field.check.is_phone(value)
                    && valid) {
                      spsafepwdForm.fields.field.error($('#safepwd-form-'+formName+'-'+data['name']+'-errors .safepwd-error-phone'), sptext['error_is_phone']);
                      $('#safepwd-form-'+formName+'-'+data['name']+'-errors').removeClass('safepwd-invisible');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-input').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-textarea').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'-select.safepwd-select').addClass('safepwd-error');
                      spsafepwdForm['erros']++;
                      valid = false;
                  }
                
                  // Iban
                  if(data['is_iban'] === 'true'
                    && !spsafepwdForm.fields.field.check.is_iban(value)) {
                      spsafepwdForm.fields.field.error($('#safepwd-form-'+formName+'-'+data['name']+'-errors .safepwd-error-iban'), sptext['error_is_iban']);
                      $('#safepwd-form-'+formName+'-'+data['name']+'-errors').removeClass('safepwd-invisible');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-input').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-textarea').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'-select.safepwd-select').addClass('safepwd-error');
                      spsafepwdForm['erros']++;
                      valid = false;
                  }
                
                  // Swift
                  if(data['is_swift'] === 'true'
                    && !spsafepwdForm.fields.field.check.is_swift(value)) {
                      spsafepwdForm.fields.field.error($('#safepwd-form-'+formName+'-'+data['name']+'-errors .safepwd-error-swift'), sptext['error_is_swift']);
                      $('#safepwd-form-'+formName+'-'+data['name']+'-errors').removeClass('safepwd-invisible');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-input').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-textarea').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'-select.safepwd-select').addClass('safepwd-error');
                      spsafepwdForm['erros']++;
                      valid = false;
                  }
                
                  // Lower Than
                  if(data['lower_than'] !== undefined
                    && !spsafepwdForm.fields.field.check.is_lower(value, data['lower_than'])) {
                      spsafepwdForm.fields.field.error($('#safepwd-form-'+formName+'-'+data['name']+'-errors .safepwd-error-lower'), sptext['error_is_lower_than'], (data['lower_than'] !== undefined ? data['lower_than']:''));
                      $('#safepwd-form-'+formName+'-'+data['name']+'-errors').removeClass('safepwd-invisible');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-input').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-textarea').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'-select.safepwd-select').addClass('safepwd-error');
                      spsafepwdForm['erros']++;
                      valid = false;
                  }
                
                  // Higher Than
                  if(data['higher_than'] !== undefined
                    && !spsafepwdForm.fields.field.check.is_higher(value, data['higher_than'])) {
                      spsafepwdForm.fields.field.error($('#safepwd-form-'+formName+'-'+data['name']+'-errors .safepwd-error-higher'), sptext['error_is_higher_than'], (data['higher_than'] !== undefined ? data['higher_than']:''));
                      $('#safepwd-form-'+formName+'-'+data['name']+'-errors').removeClass('safepwd-invisible');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-input').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-textarea').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'-select.safepwd-select').addClass('safepwd-error');
                      spsafepwdForm['erros']++;
                      valid = false;
                  }
                
                  // Same with
                  if(data['same_with'] !== undefined
                    && value !== $('#safepwd-form-'+formName+'-'+data['same_with']).val()){
                      spsafepwdForm.fields.field.error($('#safepwd-form-'+formName+'-'+data['name']+'-errors .safepwd-error-same-with'));
                      $('#safepwd-form-'+formName+'-'+data['name']+'-errors').removeClass('safepwd-invisible');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-input').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-textarea').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'-select.safepwd-select').addClass('safepwd-error');
                      spsafepwdForm['erros']++;
                      valid = false;
                  }
                  
                  // Exist
                  if(data['exist'] !== undefined
                    && data['exist'] === 'true'
                    && value === data['exist_value']){
                      spsafepwdForm.fields.field.error($('#safepwd-form-'+formName+'-'+data['name']+'-errors .safepwd-error-exist'));
                      $('#safepwd-form-'+formName+'-'+data['name']+'-errors').removeClass('safepwd-invisible');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-input').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-textarea').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'-select.safepwd-select').addClass('safepwd-error');
                      spsafepwdForm['erros']++;
                      valid = false;
                  }
                
                  // Is URL
                  if(data['is_url'] !== undefined
                     && data['is_url'] === 'true'
                     && value !== ''
                     && !spsafepwdForm.fields.field.check.is_url(value)) {
                      spsafepwdForm.fields.field.error($('#safepwd-form-'+formName+'-'+data['name']+'-errors .safepwd-error-url'));
                      $('#safepwd-form-'+formName+'-'+data['name']+'-errors').removeClass('safepwd-invisible');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-input').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-textarea').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'-select.safepwd-select').addClass('safepwd-error');
                      spsafepwdForm['erros']++;
                      valid = false;
                  }
                
                  // Terms
                  if(data['terms'] !== undefined
                     && data['terms'] === 'true'
                     && value !== ''
                     && value === 'false') {
                      spsafepwdForm.fields.field.error($('#safepwd-form-'+formName+'-'+data['name']+'-errors .safepwd-error-terms'));
                      $('#safepwd-form-'+formName+'-'+data['name']+'-errors').removeClass('safepwd-invisible');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-input').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-textarea').addClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'-select.safepwd-select').addClass('safepwd-error');
                      spsafepwdForm['erros']++;
                      valid = false;
                  }
                
                  // Remove error if is ok
                  if(valid) {
//                       $('#safepwd-form-'+formName+'-'+data['name']+'-errors').addClass('safepwd-invisible');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-input').removeClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'.safepwd-textarea').removeClass('safepwd-error');
                      $('#safepwd-form-'+formName+'-'+data['name']+'-select.safepwd-select').removeClass('safepwd-error');
                  }
                
                  return valid;
              },
              check: {
                  is_email: function(email){ 
                      var re = /\S+@\S+\.\S+/;
                      return re.test(email);
                  },
                  is_phone: function(phone){
                      var reg = /^([+|\d])+([\s|\d])+([\d])$/;
                      return reg.test(phone);
                  },
                  /*
                   * Returns 1 if the IBAN is valid 
                   * Returns FALSE if the IBAN's length is not as should be (for CY the IBAN Should be 28 chars long starting with CY )
                   * Returns any other number (checksum) when the IBAN is invalid (check digits do not match)
                   */
                  is_iban: function(input) {
                      var CODE_LENGTHS = {
                          AD: 24, AE: 23, AT: 20, AZ: 28, BA: 20, BE: 16, BG: 22, BH: 22, BR: 29,
                          CH: 21, CR: 21, CY: 28, CZ: 24, DE: 22, DK: 18, DO: 28, EE: 20, ES: 24,
                          FI: 18, FO: 18, FR: 27, GB: 22, GI: 23, GL: 18, GR: 27, GT: 28, HR: 21,
                          HU: 28, IE: 22, IL: 23, IS: 26, IT: 27, JO: 30, KW: 30, KZ: 20, LB: 28,
                          LI: 21, LT: 20, LU: 20, LV: 21, MC: 27, MD: 24, ME: 22, MK: 19, MR: 27,
                          MT: 31, MU: 30, NL: 18, NO: 15, PK: 24, PL: 28, PS: 29, PT: 25, QA: 29,
                          RO: 24, RS: 22, SA: 24, SE: 24, SI: 19, SK: 24, SM: 27, TN: 24, TR: 26
                      };
                      var iban = String(input).toUpperCase().replace(/[^A-Z0-9]/g, ''), // keep only alphanumeric characters
                              code = iban.match(/^([A-Z]{2})(\d{2})([A-Z\d]+)$/), // match and capture (1) the country code, (2) the check digits, and (3) the rest
                              digits;
                      // check syntax and length
                      if (!code || iban.length !== CODE_LENGTHS[code[1]]) {
                          return false;
                      }
                      // rearrange country code and check digits, and convert chars to ints
                      digits = (code[3] + code[1] + code[2]).replace(/[A-Z]/g, function (letter) {
                          return letter.charCodeAt(0) - 55;
                      });
                      // final check
                      return spsafepwdForm.fields.field.check.mod97(digits);
                  },
                  mod97: function (string) {
                      var checksum = string.slice(0, 2), fragment;
                      for (var offset = 2; offset < string.length; offset += 7) {
                          fragment = String(checksum) + string.substring(offset, offset + 7);
                          checksum = parseInt(fragment, 10) % 97;
                      }
                      return checksum;
                  },
                  is_swift: function(value) {
                      return /^([A-Z]{6}[A-Z2-9][A-NP-Z1-2])(X{3}|[A-WY-Z0-9][A-Z0-9]{2})?$/.test(value.toUpperCase());
                  },
                  is_lower: function(value, thanValue) {
                      return value <= thanValue ? true:false;
                  },
                  is_higher: function(value, thanValue) {
                      return value >= thanValue ? true:false;
                  },
                  is_url: function(str) {
                      var pattern = new RegExp('((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
                      '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
                      '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
                      '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
                      '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
                      return pattern.test(str);
                  }
              },
              error: function(box, text, data){
                  
                  if(data !== undefined) {
                      text = text.split('%s').join(data);
                  }
                  
                  box.html(text).removeClass('safepwd-invisible');
              }
          }
      }
  };
  
  window.spsafepwdForm = spsafepwdForm;
  
})(jQuery);