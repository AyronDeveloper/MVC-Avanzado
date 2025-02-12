<?php
namespace configs\Router;

use configs\Router\Navigate;

class Route extends Navigate{
    
    public static function get($link,$method){
        $request_method=$_SERVER["REQUEST_METHOD"];

        if($request_method=="GET"){
            self::navigate($link,$method);
        }

    }

    public static function post($link,$method){
        $request_method=$_SERVER["REQUEST_METHOD"];

        if($request_method=="POST"){
            if(!array_key_exists("PUT",$_POST) && !array_key_exists("DELETE",$_POST)){
                self::navigate($link,$method);
            }
        }
    }

    public static function put($link,$method){
        $request_method=$_SERVER["REQUEST_METHOD"];

        if($request_method=="POST" && isset($_POST["PUT"]) && $_POST["PUT"]=="_PUT"){
            self::navigate($link,$method);
        }
    }

    public static function delete($link,$method){
        $request_method=$_SERVER["REQUEST_METHOD"];

        if($request_method=="POST" && isset($_POST["DELETE"]) && $_POST["DELETE"]=="_DELETE"){
            self::navigate($link,$method);
        }
    }

    public static function combo($request,$link,$method){
        $request_method=$_SERVER["REQUEST_METHOD"];

        foreach($request as $res){
            $res=strtoupper($res);

            if($request_method==$res){
                if($request_method=="GET"){
                    self::navigate($link,$method);
                }
                if($request_method=="POST"){
                    if(!array_key_exists("PUT",$_POST) && !array_key_exists("DELETE",$_POST)){
                        self::navigate($link,$method);
                    }
                    elseif($_POST["PUT"]=="_PUT"){
                        self::navigate($link,$method);
                    }
                    elseif($_POST["DELETE"]=="_DELETE"){
                        self::navigate($link,$method);
                    }
                }
            }

        }

    }
}
?>