<?php

    /* Определяем текущую директорию */
    $cur_dir = dirname(__FILE__);
    /* И загружаем класс инициализации */
    require_once $cur_dir . '/lib/Init.php';
    require_once $cur_dir . '/lib/Init/Exception.php';
        
    /* Устанавливаем основные пути в системе */
    Init::define('DS',           DIRECTORY_SEPARATOR);
    Init::define('ROOT',         dirname(realpath(__FILE__)));
    Init::define('APP',          ROOT . DS . 'app');
    Init::define('LIB',          ROOT . DS . 'lib');
    Init::define('THIRD_PARTY',  ROOT . DS . 'third_party');
    
    /* Включаем загрузку файлов из директории библиотеки */
    Init::setIncludePath( array(APP, LIB, THIRD_PARTY) );
    
    Init::define('CR',   "\r");
    Init::define('LF',   "\n");   
    Init::define('CRLF', CR . LF);
    
    Init::setLocale('ru_RU.UTF8');
    Init::setTimezone('Europe/Moscow');
    
    /* Подключаем зендовский автозагрузчик классов */
    require_once 'Zend/Loader/Autoloader.php';
    Zend_Loader_Autoloader::getInstance()
        ->setFallbackAutoloader(true)
        ->suppressNotFoundWarnings(true);
        
    Init::setupErrorHandler();
        
?>