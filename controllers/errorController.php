<?php
namespace controllers;

class ErrorController{

    public static function web(){
        require_once("./views/errorView/index.php");
    }

    public static function api(){
        echo json_encode(["error"=>"Endpoint not found"]);
    }

}
?>