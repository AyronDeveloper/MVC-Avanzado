<?php
namespace configs\Database;

use PDO;

class CnxPg{
    public static function connect(){
        $pgsql=new PDO("pgsql:host=$_ENV[PG_HOST];port=$_ENV[PG_PORT];dbname=$_ENV[PG_DBNAME];",$_ENV["PG_USER"], $_ENV["PG_PASSWORD"]);
        $pgsql->exec("SET NAMES 'utf8'");
        return $pgsql;
    }
}
?>