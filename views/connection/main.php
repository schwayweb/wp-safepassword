<?php 

global $spclasses;
global $sptext;
global $spcms;


// No Access
if($spcms['role'] != 'admin') {
    $spclasses->display->view('no-access'); 
    exit;
}

?>

<div class="safepwd-connection">
  <?php 
  
  if($spcms['page_disabled']) {
      $spclasses->display->view('connection/disconnected');
  } else {
      $spclasses->display->view('connection/connected');
  }
  
  ?>
</div>