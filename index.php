<?php

    require_once 'init.php';
    
    $config = array(
        'pages'  => 'D:\\projects\\vyrus.github.com\\pages',
        'layout' => ROOT . DS . 'layout',
    );
    
    App::create()
        ->setConfig($config)
        ->run()
    ;
    
?>