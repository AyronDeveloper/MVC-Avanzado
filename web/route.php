<?php
use configs\Router\Route;
use controllers\HomeController;

Route::controller(HomeController::class)->group(function(){
    Route::get("","index");
});
?>