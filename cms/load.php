<?php

global $spcms;
global $spclasses;
global $spDB;
$spclasses = new stdClass;


/*
 *  Include CMS Files
 */ 
include_once 'protect.php';
include_once 'installation.php';  
include_once 'database.php';
include_once 'requests.php'; // Ajax Requests
include_once 'option.php';

// Language
include_once 'language.php';  

include_once 'mymenu.php';    
include_once 'display.php';    
include_once 'resources.php';   
include_once 'api.php';
include_once 'main.php';


/*
 *  Start Extensions Main
 */ 

$spclasses->protect      = class_exists('spcmsProtect') ? new spcmsProtect():'Class does not exist!';
$spclasses->installation = class_exists('spcmsInstallation') ? new spcmsInstallation():'Class does not exist!';
$spclasses->db           = class_exists('spcmsDatabase') ? new spcmsDatabase():'Class does not exist!';
$spclasses->option       = class_exists('spcmsOption') ? new spcmsOption():'Class does not exist!';
$spclasses->language     = class_exists('spcmsLanguage') ? new spcmsLanguage():'Class does not exist!';
$spclasses->display      = class_exists('spcmsDisplay') ? new spcmsDisplay():'Class does not exist!';
$spclasses->menu         = class_exists('spcmsMenu') ? new spcmsMenu():'Class does not exist!';
$spclasses->resources    = class_exists('spcmsResources') ? new spcmsResources():'Class does not exist!';
$spclasses->api          = class_exists('spcmsApi') ? new spcmsApi():'Class does not exist!';
$spclasses->main         = class_exists('spcmsMain') ? new spcmsMain():'Class does not exist!';

?>