<?php
    
    require_once 'markdown.php';
    
    $config = array(
        'pages'  => 'D:\\uni\\диплом\\практика\\отчёт\\markdown',
        'layout' => 'D:\\projects\\vyrus.github.com\\layout',
        'output' => 'Y:\\home\\diploma\\www'
    );
    
    define('DS',       DIRECTORY_SEPARATOR);
    define('ROOT',     dirname(__FILE__));
    define('LAYOUT',   $config['layout']);
    define('PAGES',    $config['pages']);
    define('RENDERED', $config['output']);

    $_skip_entries = array('.', '..');
    
    $layout = file_get_contents(LAYOUT . DS . 'layout.html');
    
    _process_dir(PAGES, RENDERED);
    
    function _process_dir($path, $render_path) {
        global $_skip_entries, $layout;
        
        $dir = dir($path);
        
        while (false !== ($entry = $dir->read()))
        {
            if (in_array($entry, $_skip_entries)) {
                continue;
            }
            
            $full_path = $path . DS . $entry;
                
            if (is_dir($full_path)) {
                _process_dir($full_path, $render_path . DS . $entry);
                continue;
            }
                
            $parts = explode('.', $entry);
            $ext = array_pop($parts);
                
            if (!in_array($ext, array('md', 'mdown'))) {
                continue;
            }
                
            $page = file_get_contents($full_path);
            
            $content = Markdown($page);
            $rendered = str_replace('{{ content }}', $content, $layout);
            
            if (!file_exists($render_path)) {
                mkdir($render_path);
            }
            
            $rendered_file = basename($entry, '.md') . '.html';
            $rendered_file = $render_path . DS . $rendered_file;
            
            file_put_contents($rendered_file, $rendered); 
        }
    }
    
?>