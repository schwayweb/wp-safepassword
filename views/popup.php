<?php 

global $sptext;
global $spcms;

?>

<div id="safepwd-popup" style="display:none" class="safepwd-popup">
  
  <div class="safepwd-popup-box">
    
    <!-- Loader : START !-->
    <div class="safepwd-loader safepwd-box">
      <h3 id="safepwd-message1">Loading...</h3>
      <div class="safepwd-load">
        <div class="safepwd-load-circle safepwd-on2"></div>
        <div class="safepwd-load-circle safepwd-left-less"></div>
        <div class="safepwd-load-circle safepwd-left-less"></div>
        <div class="safepwd-load-circle safepwd-left-less"></div>
        <div class="safepwd-load-circle safepwd-left-less"></div>
        <div class="safepwd-load-circle safepwd-left-less"></div>
        <div class="safepwd-load-circle safepwd-left-less"></div>
        <div class="safepwd-load-circle safepwd-left-less"></div>
      </div>
      <div class="safepwd-load-line">
        <div class="safepwd-load-line-on"></div>
      </div>
      <div id="safepwd-message2" class="safepwd-load-message">
        Please wait... will be finished soon...
      </div>
      <div id="safepwd-message3" class="safepwd-load-message"></div>
    </div>
    <!-- Loader : END !-->
    
    <!-- Info : START !-->
    <div class="safepwd-info safepwd-box safepwd-invisible">
      <div class="safepwd-info-close safepwd-close">X</div>
      <h3 id="safepwd-info1">Are you sure that you disconnect ?</h3>
      <div id="safepwd-info2" class="safepwd-info-text">
        You will not be able to use our dashboard...
      </div>
      <div id="safepwd-info-buttons" class="safepwd-info-buttons">
        <div class="safepwd-info-yes safepwd-button">
          Yes, I'm Agree
        </div>
        <div class="safepwd-info-no safepwd-button">
          No, Cancel...
        </div>
      </div>
    </div>
    <!-- Info : END !-->
    
    <!-- Warning : START !-->
    <div class="safepwd-warning safepwd-box safepwd-invisible">
      <div class="safepwd-time">10</div>
      <div class="safepwd-warning-close safepwd-close">X</div>
      <h3 id="safepwd-warning1">Warning</h3>
      <div class="safepwd-warning-icon">!</div>
      <div id="safepwd-warning2" class="safepwd-warning-message">
        Was an error at request... Please try again later or <a href="<?php echo $spcms['support_url'] ?>"><?php echo $sptext['warning_contact']; ?></a>
      </div>
    </div>
    <!-- Warning : END !-->
    
    <!-- Clear-->
    <div class="safepwd-clear"></div>
    
  </div>
  
</div>