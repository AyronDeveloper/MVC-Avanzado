<?php
use configs\Router\Api;
use controllers\apiController;

Api::controller(apiController::class)->group(function(){
    Api::get("","index");
});
?>