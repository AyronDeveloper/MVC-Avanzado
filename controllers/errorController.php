<?php
namespace controllers;

class errorController{

    public static function web(){
        require_once("./views/errorView/index.php");
    }

    public static function api(){
        echo json_encode(["error"=>"Endpoint not found"]);
    }

}
?>