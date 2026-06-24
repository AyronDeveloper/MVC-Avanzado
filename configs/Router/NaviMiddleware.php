<?php
namespace configs\Router;

use configs\Helpers\Utils;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class NaviMiddleware{

    protected static $status_middleware=[
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
    


    public static function bearer(){

        $server=$_SERVER;

        $bearer_status=true;

        $res=[];
        


        if(isset($server["HTTP_AUTHORIZATION"]) && preg_match('/^Bearer\s+(\S+)$/i',$server["HTTP_AUTHORIZATION"],$matches)){

            if($matches[1]==$_ENV["BEARER"]){
                $bearer_status=true;
            }else{
                $res=["result"=>false,"message"=>"Denegado"];
                $bearer_status=false;
            }
        }else{
            $res=["result"=>false,"message"=>"No tiene autorizacion"];
            $bearer_status=false;
        }



        self::$status_middleware["bearer"]["active"]=true;
        self::$status_middleware["bearer"]["status"]=$bearer_status;
        self::$status_middleware["bearer"]["response"]=$res;
    }


    public static function auth(){
        $status_auth=true;
        $token=$_COOKIE["token_auth"]??null;
        $res=[];
        
        if($token){
            try{
                $token=Utils::decrypt($token,$_ENV["CIPHER_KEY"]);
                $key=$_ENV["JWT_KEY"];
                $decode=JWT::decode($token,new Key($key,"HS256"));

                /*$auth=$decode->sub;

                $_SESSION["auth"]=[
                    "id_usuario"=>$auth->id_usuario,
                    "id_empresa"=>$auth->id_empresa,
                    "porcent_igv"=>$auth->porcent_igv,
                    "full_name"=>$auth->full_name,
                    "image"=>$auth->image,
                    "nivel"=>$auth->nivel,
                    "id_sucursal"=>$auth->id_sucursal,
                    "nom_sucursal"=>$auth->nom_sucursal,
                    "rol"=>$auth->rol
                ];*/

                $auth=(array) $decode->sub;
                $_SESSION["auth"]=$auth;

            }catch(ExpiredException $e){
                $status_auth=false;
                $res=["result"=>false,"message"=>"Session Expirada","auth"=>false];
            }catch(Exception $e){
                $status_auth=false;
                //http_response_code(401);

                $res=["result"=>false,"message"=>"Token invalido","auth"=>false];
            }

        }else{
            $status_auth=false;
            $res=["result"=>false,"message"=>"Session Expirada","auth"=>false];
        }

        self::$status_middleware["auth"]["active"]=true;
        self::$status_middleware["auth"]["status"]=$status_auth;
        self::$status_middleware["auth"]["response"]=$res;
    }
}
?>