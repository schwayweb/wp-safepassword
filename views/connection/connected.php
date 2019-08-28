<?php 

global $spclasses;
global $sptext;
global $spcms;

$email = $spclasses->option->get('email');

?>
  
<h2><?php echo $sptext['connected_to']; ?> <?php echo $sptext['title']; ?></h2>

<div class="safepwd-disconnected">
  <input type="text" id="safepwd-connection-email" class="safepwd-field safepwd-disabled" readonly="true" value="<?php echo $email; ?>">
  <input type="button" class="safepwd-button safepwd-disconnect safepwd-half" value="Disconnect" onclick="javascript:spsafepwdConnection.disconnect();">
</div>