<?php
use configs\Router\ErrorNavigate;

require_once(__DIR__."/vendor/autoload.php");

$dotenv=Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


session_start();

date_default_timezone_set('America/Lima');

//header("Access-Control-Allow-Origin: $_ENV[URL_FRONTEND]");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

require_once("./autoload.php");
require_once("./configs/Helpers/Parameters.php");


require_once("./web/route.php");
require_once("./web/api.php");

ErrorNavigate::error();
?>