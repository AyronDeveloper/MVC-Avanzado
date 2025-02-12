<?php
use configs\Router\Route;
use controllers\homeController;

Route::controller(homeController::class)->group(function(){
    Route::get("","index");
});
?>