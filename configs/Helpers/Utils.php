<?php
namespace configs\Helpers;

class Utils{
    
    public static function sessionAdmin(){
        if(!isset($_SESSION["administradorPage"])){
            header("Location: ".url()."administrador");
        }else{
            return true;
        }
    }

}
?>