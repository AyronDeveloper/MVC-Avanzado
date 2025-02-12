<?php
namespace configs\Router;

use configs\Router\Navigate;

class Api extends Navigate{
    
    public static function get($link,$method){
        $request_method=$_SERVER["REQUEST_METHOD"];

        if($request_method=="GET"){
            $link=$link==""?"api":"api/$link";
            self::navigate($link,$method);
        }

    }

    public static function post($link,$method){
        $request_method=$_SERVER["REQUEST_METHOD"];

        if($request_method=="POST"){
            $link=$link==""?"api":"api/$link";
            self::navigate($link,$method);
        }
    }

    public static function put($link,$method){
        $request_method=$_SERVER["REQUEST_METHOD"];

        if($request_method=="PUT"){
            $link=$link==""?"api":"api/$link";
            self::navigate($link,$method);
        }
    }

    public static function delete($link,$method){
        $request_method=$_SERVER["REQUEST_METHOD"];

        if($request_method=="DELETE"){
            $link=$link==""?"api":"api/$link";
            self::navigate($link,$method);
        }
    }

    public static function combo($request,$link,$method){
        $request_method=$_SERVER["REQUEST_METHOD"];

        foreach($request as $res){
            $res=strtoupper($res);

            if($request_method==$res){
                $link=$link==""?"api":"api/$link";
                self::navigate($link,$method);
            }

        }

    }

}
?>