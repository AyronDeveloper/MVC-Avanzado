<?php
use configs\Router\Api;
use controllers\Api\ApiController;

Api::controller(ApiController::class)->group(function(){
    Api::get("","index");
});
?>