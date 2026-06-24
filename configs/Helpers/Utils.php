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

    public static function encrypt($texto,$key){
        
        $metodo="AES-256-CBC";
        $iv=openssl_random_pseudo_bytes(openssl_cipher_iv_length($metodo));

        $cifrado=openssl_encrypt($texto,$metodo,$key,0,$iv);
        
        return base64_encode($iv.$cifrado);
    }

    public static function decrypt($texto,$key){
        
        $metodo="AES-256-CBC";

        $data=base64_decode($texto);
        $iv_length=openssl_cipher_iv_length($metodo);
        $iv=substr($data,0,$iv_length);
        $cifrado=substr($data,$iv_length);

        return openssl_decrypt($cifrado,$metodo,$key,0,$iv);
    }
}
?>