<?php

global $spcms;

$spcms['database'] = array();

/*
 * Options
 */
$spcms['database']['spbm_options'] = array();
$spcms['database']['spbm_options']['fields'] = array();

$spcms['database']['spbm_options']['fields'][0] = array('name' => 'id',
                                                        'type' => 'bigint',
                                                        'size' => 20, // -1 infinit
                                                        'unsigned' => true, // UNSIGNED
                                                        'null' => false, // NOT NULL
                                                        'default' => '',
                                                        'auto_increment' => true, // AUTO_INCREMENT
                                                        'key' => true); 

$spcms['database']['spbm_options']['fields'][1] = array('name' => 'user_id',
                                                        'type' => 'bigint',
                                                        'size' => 20, // -1 infinit
                                                        'unsigned' => true, // UNSIGNED
                                                        'null' => false, // NOT NULL
                                                        'default' => 0,
                                                        'auto_increment' => false, 
                                                        'key' => false); // AUTO_INCREMENT

$spcms['database']['spbm_options']['fields'][2] = array('name' => 'version',
                                                        'type' => 'varchar',
                                                        'size' => 12, // -1 infinit
                                                        'unsigned' => false, // UNSIGNED
                                                        'null' => false, // NOT NULL
                                                        'default' => '1.0',
                                                        'auto_increment' => false, 
                                                        'key' => false); // AUTO_INCREMENT

$spcms['database']['spbm_options']['fields'][3] = array('name' => 'option_name',
                                                        'type' => 'varchar',
                                                        'size' =>  256, // -1 infinit
                                                        'unsigned' => false, // UNSIGNED
                                                        'null' => false, // NOT NULL
                                                        'default' => '',
                                                        'auto_increment' => false, 
                                                        'key' => false); // AUTO_INCREMENT

$spcms['database']['spbm_options']['fields'][4] = array('name' => 'option_value',
                                                        'type' => 'longtext',
                                                        'size' =>  -1, // -1 infinit
                                                        'unsigned' => false, // UNSIGNED
                                                        'null' => false, // NOT NULL
                                                        'default' => '',
                                                        'auto_increment' => false, 
                                                        'key' => false); // AUTO_INCREMENT

$spcms['database']['spbm_options']['fields'][5] = array('name' => 'option_type',
                                                        'type' => 'varchar',
                                                        'size' =>  32, // -1 infinit
                                                        'unsigned' => false, // UNSIGNED
                                                        'null' => false, // NOT NULL
                                                        'default' => 'main',
                                                        'auto_increment' => false, 
                                                        'key' => false); // AUTO_INCREMENT

$spcms['database']['spbm_options']['fields'][6] = array('name' => 'option_date',
                                                        'type' => 'datetime',
                                                        'size' => -1, 
                                                        'unsigned' => false, // UNSIGNED
                                                        'null' => false, // NOT NULL
                                                        'default' => '0000-00-00 00:00:00',
                                                        'auto_increment' => false, 
                                                        'key' => false); // AUTO_INCREMENT