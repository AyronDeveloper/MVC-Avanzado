<?php
namespace configs\Router;

use controllers\ErrorController;

class ErrorNavigate{

    public static function error(){
        if(!isset($_SESSION["navigate"]["route_found"]) || !$_SESSION["navigate"]["route_found"]){

            if($_SESSION["navigate"]["type_request"]=="api"){
                ErrorController::api();
            }
            elseif($_SESSION["navigate"]["type_request"]=="web"){
                ErrorController::web();
            }

        }

        unset($_SESSION["navigate"]["route_found"]);
    }
}
?>