<?php
namespace configs\Router;

class Navigate{
    private static $controller;
    protected static $global=true;
    private static $status=["mode"=>"route","status"=>true];
    protected static $bearerActive=false;

    protected static function url(){
        
        $protocol=isset($_SERVER["HTTPS"])&&$_SERVER["HTTPS"]=="on"?"https":"http";

        $host=$_SERVER["HTTP_HOST"];

        $uri=$_SERVER["REQUEST_URI"];

        $url=$protocol."://".$host."$_ENV[ROUTER_MAIN]";

        $completeURL=$protocol."://".$host.$uri;

        $url_len=strlen($url);

        $url_next=substr($completeURL,$url_len);

        return $url_next;
    }

    protected static function isApi(){
        return str_starts_with(self::url(),"api/") || str_starts_with(self::url(),"api");
    }

    protected static function headers(){
        $result=true;

        if(self::$bearerActive){
            $bearer=getallheaders();

            if(isset($bearer["Authorization"]) && preg_match('/^Bearer\s+(\S+)$/i',$bearer["Authorization"],$matches)){

                if($matches[1]==$_ENV["BEARER"]){
                    $result=true;
                }else{
                    echo json_encode(["result"=>false,"message"=>"Denegado"]);
                    $result=false;
                }
            }else{
                echo json_encode(["result"=>false,"message"=>"No tiene autorizacion"]);
                $result=false;
            }
            
        }

        return $result;
    }

    public static function getStatusGlobal($param=null){
        if(!empty($param)){
            return self::$status[$param];
        }else{
            return self::$status;
        }
    }

    public static function navigate($link, $method){

        if(self::$status["status"]==false){
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

            self::$status=["status"=>false];

            if(self::headers()){

                array_shift($matches);

                $deleteSlash=array_search("/",$matches);
                if($deleteSlash!==false){
                    array_splice($matches,$deleteSlash,1);
                }


                $nameMetodo=$method;
        
                $controlador=self::$controller;
                call_user_func_array([$controlador,$nameMetodo],$matches);
            }
        
        }

    
    }

    public static function controller($controller){

        self::$bearerActive=false;

        self::$controller=new $controller();

        return new self;
    }

    public function group($function){
        $function();
    }

    public static function bearer(){
        self::$bearerActive=true;
        return new self;
    }
}
?>