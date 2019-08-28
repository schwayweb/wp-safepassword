
(function($){
  
  /* type watch */
  var spsafepwdDelay = function(){
      var timer = 0;
      return function(callback, ms){
          clearTimeout (timer);
          timer = setTimeout(callback, ms);
      }  
  }();
  
  window.spsafepwdDelay = spsafepwdDelay;
  
  /*
   * Loader
   */
  var spsafepwdLoaderBullets = {
      start: function(){
          var currentCircle = 0;
          
          setInterval(function(){
              
              if($('.safepwd-load-circle').eq(currentCircle).hasClass("safepwd-on1")) {
                  $('.safepwd-load-circle').eq(currentCircle).removeClass("safepwd-on1");
                  $('.safepwd-load-circle').eq(currentCircle).addClass("safepwd-on2");
                  currentCircle++; 
              } else {
                  $('.safepwd-load-circle').eq(currentCircle).addClass("safepwd-on1");
                  
                  if(currentCircle == 7) {
                      currentCircle = 0;
                  }
              }
          }, 30);
      },
      stop: function(){
          $('.safepwd-load-circle').removeClass('safepwd-on1');
          $('.safepwd-load-circle').removeClass('safepwd-on2');
      } 
  };
  
  window.spsafepwdLoaderBullets = spsafepwdLoaderBullets;
  
  var spsafepwdPopupLoader = {
      start: function(){
          $('#safepwd-popup').addClass('safepwd-loader-custom');
          $('#safepwd-popup').addClass('safepwd-open');
          $('#safepwd-popup').css('left', 0);
        
          $('.safepwd-box').addClass('safepwd-invisible');
          $('.safepwd-loader').removeClass('safepwd-invisible');
          
          spsafepwdDelay(function(){
              $('#safepwd-popup .safepwd-popup-box').addClass('safepwd-open');
              $('#safepwd-popup').css('left', 0);
              
              spsafepwdDelay(function(){
                  spsafepwdLoaderBullets.start();
              }, 300);
          }, 300);
      },
      
      stop: function(){
          
          spsafepwdDelay(function(){
              $('#safepwd-popup .safepwd-popup-box').removeClass('safepwd-open');
          
              spsafepwdDelay(function(){
                  $('#safepwd-popup').removeClass('safepwd-loader-custom');
                  $('#safepwd-popup').removeClass('safepwd-open');
                  spsafepwdLoaderBullets.stop();
              }, 300);
          }, 700);
      } 
  };
  
  window.spsafepwdPopupLoader = spsafepwdPopupLoader;
  
  var spsafepwdLoader = {
      start: function(message1,
                      message2){
          
          $('.safepwd-load').removeClass('safepwd-hidden');
          $('.safepwd-load-line').addClass('safepwd-hidden');
          $('#safepwd-message2').removeClass('safepwd-load-message-left');
          $('#safepwd-message3').removeClass('safepwd-load-message-right');
          
          $('#safepwd-message1').html(message1);
          $('#safepwd-message2').html(message2);
          $('#safepwd-message3').html('');
          spsafepwdPopupLoader.start();
      },
      stop: function(message1,
                     message2,
                     close){
          $('#safepwd-message1').html(message1);
          $('#safepwd-message2').html(message2);
        
          if(close !== undefined) {
              spsafepwdPopupLoader.stop();
          }
      } 
  };
  
  window.spsafepwdLoader = spsafepwdLoader;
  
  /*
   * Percentage Loader
   */
  var spsafepwdLoaderLine = {
      currentLine: 1,
      time_per_percent: 700,
      load_function: '',
      start: function(){
          
           window.spsafepwdLoaderLine.load_function = setInterval(function(){

            if(window.spsafepwdLoaderLine.currentLine <= 99
               && !window.spsafepwdPercentageLoader.is_stop) {
                $('.safepwd-load-line-on').css('width', window.spsafepwdLoaderLine.currentLine+'%');
                $('#safepwd-message3').html(window.spsafepwdLoaderLine.currentLine+'%');
                window.spsafepwdLoaderLine.currentLine++; 
            }
          }, window.spsafepwdLoaderLine.time_per_percent);
      },
      stop: function(){
          $('.safepwd-load-line-on').css('width', '100%');
          $('#safepwd-message3').html('100%');
          clearInterval(window.spsafepwdLoaderLine.load_function);
      } 
  };
  
  window.spsafepwdLoaderLine = spsafepwdLoaderLine;
  
  var spsafepwdPopupPercentageLoader = {
      start: function(){
          $('#safepwd-popup').addClass('safepwd-loader-custom');
          $('#safepwd-popup').addClass('safepwd-open');
          $('#safepwd-popup').css('left', 0);
        
          $('.safepwd-box').addClass('safepwd-invisible');
          $('.safepwd-loader').removeClass('safepwd-invisible');
          
          spsafepwdDelay(function(){
              $('#safepwd-popup .safepwd-popup-box').addClass('safepwd-open');
              $('#safepwd-popup').css('left', 0);
              
              spsafepwdDelay(function(){
                  spsafepwdLoaderLine.start();
              }, 300);
          }, 300);
      },
      
      stop: function(){
          
          spsafepwdDelay(function(){
              $('#safepwd-popup .safepwd-popup-box').removeClass('safepwd-open');
          
              spsafepwdDelay(function(){
                  $('#safepwd-popup').removeClass('safepwd-loader-custom');
                  $('#safepwd-popup').removeClass('safepwd-open');
                  spsafepwdLoaderLine.stop();
              }, 300);
          }, 700);
      } 
  };
  
  window.spsafepwdPopupPercentageLoader = spsafepwdPopupPercentageLoader;
  
  var spsafepwdPercentageLoader = {
      is_stop: false,
      start: function(message1,
                      message2){
          
          $('.safepwd-load').addClass('safepwd-hidden');
          $('.safepwd-load-line-on').css('width', '1%');
          $('.safepwd-load-line').removeClass('safepwd-hidden');
          $('#safepwd-message2').addClass('safepwd-load-message-left');
          $('#safepwd-message3').addClass('safepwd-load-message-right');
          window.spsafepwdPercentageLoader.is_stop = false;
          
          $('#safepwd-message1').html(message1);
          $('#safepwd-message2').html(message2);
          $('#safepwd-message3').html('1%');
          window.spsafepwdLoaderLine.currentLine = 1;
          spsafepwdPopupPercentageLoader.start();
      },
      stop: function(message1,
                     message2,
                     close){
          $('#safepwd-message1').html(message1);
          $('#safepwd-message2').html(message2);
          $('#safepwd-message3').html('100%');
            $('.safepwd-load-line-on').css('width', '100%');
          window.spsafepwdPercentageLoader.is_stop = true;
        
          if(close !== undefined) {
              spsafepwdPopupPercentageLoader.stop();
          }
      } 
  };
  
  window.spsafepwdPercentageLoader = spsafepwdPercentageLoader;
  
  /*
   * Info Box
   */
  
  var spsafepwdInfo = {
      start: function(message1,
                      message2,
                      yesText,
                      noText,
                      yesFunction,
                      noFunction,
                      yesClass = ''){
        
          $('#safepwd-info1').html(message1);
        
          if(noFunction === undefined){
              noFunction = spsafepwdInfo.stop;
          }
        
          if(yesFunction !== undefined) {
              $('#safepwd-popup').removeClass('safepwd-loader-custom');
          }
        
          if(typeof message2 === 'object') {
              // Start Form
              spsafepwdForm.start($('#safepwd-info2'), message2);
          } else {
              $('#safepwd-info2').html(message2);         
          }
          
          $('.safepwd-info-yes').html(yesText);
          $('.safepwd-info-no').html(noText);
          
          $('#safepwd-popup').addClass('safepwd-open');
          $('#safepwd-popup').css('left', 0);
        
          $('.safepwd-box').addClass('safepwd-invisible');
          $('.safepwd-info').removeClass('safepwd-invisible');
        
          spsafepwdInfo.events(yesFunction,
                            noFunction);
        
          spsafepwdDelay(function(){
              $('#safepwd-popup .safepwd-popup-box').addClass('safepwd-open');
              $('#safepwd-popup').css('left', 0);
              
              if(yesClass != '') {
                  $('.safepwd-info-yes').addClass(yesClass);
              }
          }, 300);
      },
      stop: function(){
          $('#safepwd-popup .safepwd-popup-box').removeClass('safepwd-open');

          spsafepwdDelay(function(){
              $('#safepwd-popup').removeClass('safepwd-open');
              $('.safepwd-info-yes').removeClass('safepwd-selected');
              $('#safepwd-info-buttons').removeClass('safepwd-invisible');
          }, 300);
      },
      events: function(yesFunction,
                       noFunction){
          
          $('.safepwd-info-yes').unbind('click');
          $('.safepwd-info-yes').bind('click', function(){
              yesFunction();
          });
        
          $('.safepwd-info-no, .safepwd-info-close').unbind('click');
          $('.safepwd-info-no, .safepwd-info-close').bind('click', function(){
              noFunction();
          });
      }
  };
  
  window.spsafepwdInfo = spsafepwdInfo;
  
  /*
   * Warning Box
   */
  
  var spsafepwdWarning = {
      time: 10,
      start: function(message1,
                      message2,
                      messagetime){
          $('#safepwd-warning1').html(message1);
          $('#safepwd-warning2').html(message2);
          
          $('#safepwd-popup').addClass('safepwd-open');
          $('#safepwd-popup').addClass('safepwd-loader-custom');
          $('#safepwd-popup').css('left', 0);
        
          $('.safepwd-box').addClass('safepwd-invisible');
          $('.safepwd-warning').removeClass('safepwd-invisible');
        
          spsafepwdWarning.events();
        
          spsafepwdDelay(function(){
              $('#safepwd-popup .safepwd-popup-box').addClass('safepwd-open');
              $('#safepwd-popup').css('left', 0);
          }, 300);
          
          
          spsafepwdWarning.timer.start(messagetime);
      },
      timer: 
      {
          start: function(messagetime){
              spsafepwdWarning.time = messagetime;
              spsafepwdWarning.timer.reload();
          },
          reload: function(){
              $('.safepwd-time').html(spsafepwdWarning.time);
              
              if(spsafepwdWarning.time > 0) {
                  setTimeout(spsafepwdWarning.timer.reload, 1000);
                  spsafepwdWarning.time--;
              } else {
                  spsafepwdWarning.stop();
              }
          }
      },
      stop: function(){
          $('#safepwd-popup .safepwd-popup-box').removeClass('safepwd-open');

          spsafepwdDelay(function(){
              $('#safepwd-popup').removeClass('safepwd-open');
              $('#safepwd-popup').removeClass('safepwd-loader-custom');
          }, 300);
      },
      events: function(){
        
          $('.safepwd-warning-close').unbind('click');
          $('.safepwd-warning-close').bind('click', function(){
              spsafepwdWarning.stop();
          });
      }
  };
  
  window.spsafepwdWarning = spsafepwdWarning;
  
})(jQuery);