<?php
namespace configs\Router;

class Navigate{
    private static $controller;
    private static $statusGlobal=true;

    private static function url(){
        
        $protocol=isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']=='on'?'https':'http';

        $host=$_SERVER['HTTP_HOST'];

        $uri=$_SERVER['REQUEST_URI'];

        $url=$protocol.'://'.$host."$_ENV[ROUTER_MAIN]";

        $completeURL=$protocol.'://'.$host.$uri;

        $url_len=strlen($url);

        $url_next=substr($completeURL,$url_len);

        return $url_next;
    }

    public static function setStatusGlobal($status){
        self::$statusGlobal=$status;
    }

    public static function getStatusGlobal(){
        return self::$statusGlobal;
    }

    public static function navigate($link, $method){
        if(!self::$statusGlobal){
            return;
        }

        $url_next=self::url();

        $existeHttps=strpos($url_next,"?");
    
        if($existeHttps!==false){
            $url_next=substr($url_next,0,$existeHttps);
        }

        if(substr($url_next,-1)!="/"){
            $url_next=$url_next."/";
        }

        $paramOpcional=preg_replace('/:\w+\?/','([^/]+)?',$link);
        $paramObliga=preg_replace('/:\w+/','([^/]+)',$paramOpcional);
        $param='#^'.str_replace('/','\/', $paramObliga).'(\/)?$#';

        if(preg_match($param,$url_next,$matches)){
            array_shift($matches);

            $deleteSlash=array_search("/",$matches);
            if($deleteSlash!==false){
                array_splice($matches,$deleteSlash,1);
            }

            self::$statusGlobal=false;

            $nameMetodo=$method;
    
            $controlador=self::$controller;
            call_user_func_array([$controlador,$nameMetodo],$matches);
        }
    
    }

    public static function controller($controller){
        self::$controller=new $controller();

        return new self;
    }

    public function group($function){
        $function();
    }
}

?>