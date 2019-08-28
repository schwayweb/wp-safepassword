<?php 

global $spclasses;
global $sptext;
global $spcms;

?>
<div class="safepwd-header">
    <ul class="safepwd-menu">
        <?php if($spcms['role'] == 'admin') { ?>
        <li class="safepwd-line"></li>
        <li class="<?php echo $spcms['page'] == 'connection' ? 'safepwd-selected':''; ?>" onclick="location.href='?page=spsafepwd-connection'"><?php echo $sptext['connection']; ?></li>
        <?php } ?>
        
    </ul>
</div>