<?php
    
    require_once 'markdown.php';

    class App {
        
        const WEB_PAGE_EXT = 'html';
        
        const MDOWN_PAGE_EXT = 'md';
        
        /**
        * Разделитель, используемый в строке запроса.
        * 
        * @var const
        */
        const URL_DELIMITER = '/';
        
        protected $_config;
        
        public static function create() {
            return new self();
        }
        
        public function setConfig(array $config) {
            $this->_config = (object) $config;
            return $this;
        }
        
        public function display() {
            /* Определяем строку запроса */
            $server = $_SERVER;
            if (isset($server['REDIRECT_URL'])) {
                $path = strtolower($server['REDIRECT_URL']);
            } else {
                $path = '/index.' . self::WEB_PAGE_EXT;
            }
            
            $path = iconv('UTF-8', 'Windows-1251', $path);
            
            $path = explode(self::URL_DELIMITER, $path);
            array_shift($path);
            
            $file_name = end($path);
            $file_name = explode('.', $file_name);
            $file_ext  = end($file_name);
            
            if (self::WEB_PAGE_EXT == $file_ext)
            {
                $file_name = $file_name[0] . '.' . self::MDOWN_PAGE_EXT;
                
                array_pop($path);
                array_push($path, $file_name);
                
                $page_path = implode(DS, $path);
                $page_path = $this->_config->pages . DS . $page_path;
                
                $page_mdown = file_get_contents($page_path);
                $page_html = Markdown($page_mdown);
                
                $layout_path = $this->_config->layout . DS . 'layout.html';
                $layout = file_get_contents($layout_path);
                
                $output = str_replace('{{ content }}', $page_html, $layout);
                
                echo $output;
                return;
            }
                
            $file_path = implode(DS, $path);
            $file_path = $this->_config->pages . DS . $file_path;
            
            if (file_exists($file_path)) {
                return $this->_sendFile($file_path);
            }
            
            $file_path = implode(DS, $path);
            $file_path = $this->_config->layout . DS . $file_path;
            
            if (file_exists($file_path)) {
                return $this->_sendFile($file_path);
            }
            
            header('HTTP/1.0 404 Not Found');
            echo $file_path;
        }
        
        protected function _sendFile($file_path) {
            $type = $this->_getMimeType($file_path);
            header('Content-Type: ' . $type);
             
            echo file_get_contents($file_path);
        }
        
        /**
        * @link http://ru2.php.net/manual/en/function.mime-content-type.php#87856
        */
        protected function _getMimeType($filename) {
            $mime_types = array(
                'txt'  => 'text/plain',
                'htm'  => 'text/html',
                'html' => 'text/html',
                'php'  => 'text/html',
                'css'  => 'text/css',
                'js'   => 'application/javascript',
                'json' => 'application/json',
                'xml'  => 'application/xml',
                'swf'  => 'application/x-shockwave-flash',
                'flv'  => 'video/x-flv',

                /* images */
                'png'  => 'image/png',
                'jpe'  => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg'  => 'image/jpeg',
                'gif'  => 'image/gif',
                'bmp'  => 'image/bmp',
                'ico'  => 'image/vnd.microsoft.icon',
                'tiff' => 'image/tiff',
                'tif'  => 'image/tiff',
                'svg'  => 'image/svg+xml',
                'svgz' => 'image/svg+xml',

                /* archives */
                'zip' => 'application/zip',
                'rar' => 'application/x-rar-compressed',
                'exe' => 'application/x-msdownload',
                'msi' => 'application/x-msdownload',
                'cab' => 'application/vnd.ms-cab-compressed',

                /* audio/video */
                'mp3' => 'audio/mpeg',
                'qt'  => 'video/quicktime',
                'mov' => 'video/quicktime',

                /* adobe */
                'pdf' => 'application/pdf',
                'psd' => 'image/vnd.adobe.photoshop',
                'ai'  => 'application/postscript',
                'eps' => 'application/postscript',
                'ps'  => 'application/postscript',

                /* ms office */
                'doc' => 'application/msword',
                'rtf' => 'application/rtf',
                'xls' => 'application/vnd.ms-excel',
                'ppt' => 'application/vnd.ms-powerpoint',

                /* open office */
                'odt' => 'application/vnd.oasis.opendocument.text',
                'ods' => 'application/vnd.oasis.opendocument.spreadsheet'
            );

            $ext = strtolower(array_pop(explode('.',$filename)));
            if (array_key_exists($ext, $mime_types)) {
                return $mime_types[$ext];
            }
            elseif (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME);
                $mimetype = finfo_file($finfo, $filename);
                finfo_close($finfo);
                return $mimetype;
            }
            else {
                return 'application/octet-stream';
            }
        }
    }
    
?>