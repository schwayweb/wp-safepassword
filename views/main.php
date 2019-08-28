<?php 

global $spclasses;
global $sptext;

?>
<div id="safepwd-wrapper">
    <h1><?php echo $sptext['title']; ?></h1>
    
    <div class="safepwd-content-box">
        <?php $spclasses->display->view('popup'); ?>
        <?php $spclasses->display->view('header'); ?>
        <?php $spclasses->display->view('content'); ?>
    </div>
    <?php $spclasses->display->view('footer'); ?>
</div>