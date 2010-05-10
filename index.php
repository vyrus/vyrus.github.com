<?php

    require_once 'init.php';
    
    $config = array(
        'pages'  => 'D:\\uni\\диплом\\записка',
        'layout' => ROOT . DS . 'layout'
    );
    
    $config['pages'] = iconv('UTF-8', 'Windows-1251', $config['pages']);
    
    App::create()
        ->setConfig($config)
        ->display()
    ;
    
?>