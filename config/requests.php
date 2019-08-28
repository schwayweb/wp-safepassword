<?php

global $spcms;

$spcms['requests'] = array();

/*
 * Requests
 */
$spcms['requests'][0] = array('name' => 'connect',
                               'function' => 'connect',
                               'class' => 'main',
                               'type' => 'both'); // frontend / backend

$spcms['requests'][1] = array('name' => 'disconnect',
                               'function' => 'disconnect',
                               'class' => 'main',
                               'type' => 'both'); // frontend / backend

$spcms['requests'][2] = array('name' => 'safepassword',
                               'function' => 'safepassword',
                               'class' => 'main',
                               'type' => 'both'); // frontend / backend

$spcms['requests'][3] = array('name' => 'login',
                               'function' => 'login',
                               'class' => 'main',
                               'type' => 'both'); // frontend / backend

$spcms['requests'][4] = array('name' => 'register',
                               'function' => 'register',
                               'class' => 'main',
                               'type' => 'both'); // frontend / backend

$spcms['requests'][5] = array('name' => 'unregister',
                               'function' => 'unregister',
                               'class' => 'main',
                               'type' => 'both'); // frontend / backend

$spcms['requests'][6] = array('name' => 'detect_country',
                               'function' => 'detect_country',
                               'class' => 'main',
                               'type' => 'both'); // frontend / backend

$spcms['requests'][7] = array('name' => 'server',
                               'function' => 'server',
                               'class' => 'main',
                               'type' => 'both'); // frontend / backend

$spcms['requests'][8] = array('name' => 'recharge',
                               'function' => 'recharge',
                               'class' => 'main',
                               'type' => 'both'); // frontend / backend
