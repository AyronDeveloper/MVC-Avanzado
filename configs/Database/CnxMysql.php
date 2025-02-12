<?php
namespace configs\Database;

use PDO;

class CnxMysql{
    public static function connect(){
        $mysql=new PDO("mysql:host=$_ENV[MYSQL_HOST]; dbname=$_ENV[MYSQL_DBNAME]; ",$_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"]);
        $mysql->exec("SET NAMES 'utf8'");
        return $mysql;
    }
}
?>