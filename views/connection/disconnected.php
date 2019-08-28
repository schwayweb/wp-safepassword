<?php 

global $spclasses;
global $sptext;
global $spcms;

$email = $spclasses->option->get('email');
$email = isset($email) && $email != '' ? $email:$spcms['email'];
$website = $spcms['website_full'];

?>
  
<h2><?php echo $sptext['connect_to']; ?> <?php echo $sptext['title']; ?></h2>

<div class="safepwd-disconnected">
  <input type="text" id="safepwd-connection-email" class="safepwd-field" placeholder="Your email" value="<?php echo $email; ?>">
  <input type="text" id="safepwd-connection-referral-id" class="safepwd-field safepwd-sp-v2" placeholder="Referral ID (optional)" value="">
  <input type="hidden" id="safepwd-connection-website" class="safepwd-field" value="<?php echo $website; ?>">
  <input type="checkbox" id="safepwd-terms-and-conditions">   <span><?php echo $sptext['i_m_agree']; ?></span>
  <input type="button" class="safepwd-button safepwd-connect safepwd-half" value="Connect" onclick="javascript:spsafepwdConnection.connect('1');">
</div>