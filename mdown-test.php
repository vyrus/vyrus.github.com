<?php

    require_once 'markdown.php';
    
    $layout = file_get_contents('layout.html');
    //$text = file_get_contents('PHP Markdown Readme.text');
    $text = file_get_contents('index.md');
    $content = Markdown($text);
    $page = str_replace('{{ content }}', $content, $layout);
    
    file_put_contents('index.html', $page);

?>