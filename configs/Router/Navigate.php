<?php
namespace configs\Router;

use configs\Router\NaviMiddleware;

class Navigate extends NaviMiddleware{

    private static $controller;
    protected static $global=true;


    
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


        foreach(self::$status_middleware as $middleware){

            if($middleware["active"]){

                if(!$middleware["status"]){ 

                    echo json_encode($middleware["response"]);
                    return false;
                };
            }
        }


        return $result;
    }



    public static function navigate($link,$method){

        /**
         * VALIDA SI YA ENCONTRO LA URL REQUERIDA
         */
        if(isset($_SESSION["navigate"]["route_found"]) && $_SESSION["navigate"]["route_found"]){
            return;
        }

        /**
         * RECUPERA LA URL ACTUAL DE LA PAGINA
         */
        $url_next=self::url();



        $existeHttps=strpos($url_next,"?");
        /**
         * VALIDA SI LA URL TIENE PARAMETROS
         */
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

            /**
             * SI ENCUENTRA LA URL SOLICITADA
             * GENERA UNA SESION PARA QUE NO SIGA EJECUTANDO EL RESTO DE CODIGO
             */
            $_SESSION["navigate"]["route_found"]=true;


            /**
             * VERIFICA QUE LOS MIDDLEWARE ESTEN CORRECTAMENTE
             */
            if(self::headers()){

                array_shift($matches);

                $deleteSlash=array_search("/",$matches);
                if($deleteSlash!==false){
                    array_splice($matches,$deleteSlash,1);
                }

                
                foreach($matches as $i=>$val){
                    if(is_string($val)){
                        $val=urldecode($val);
                    }
                    $matches[$i]=$val;
                }


                $nameMetodo=$method;
        
                $controlador=self::$controller;
                call_user_func_array([$controlador,$nameMetodo],$matches);
            }
        
        }

    
    }



    public static function controller($controller){

        self::$status_middleware=[
            "bearer"=>[
                "active"=>false,
                "status"=>false,
                "response"=>[]
            ],
            "auth"=>[
                "active"=>false,
                "status"=>false,
                "response"=>[]
            ]
        ];

        self::$controller=new $controller();

        return new self;
    }



    public function group($function){
        $function();
    }
    


    public static function middleware($middleware=[]){

        foreach($middleware as $middle){

            self::$middle();
        } 

        return new self;
    }
}
?>