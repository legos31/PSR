<?php

use App\Controllers\DashboardController;
use App\Controllers\LoginController;
use Framework\Http\Middleware\Authenticate;
use Framework\Http\Middleware\Guest;
use Framework\routing\Route;
use App\Controllers\SiteController;
use App\Controllers\RegisterController;

return [
    Route::get('/', [SiteController::class, 'index']),
    Route::get('/posts/{id:\d+}', [SiteController::class, 'view']),
    Route::get('/posts/create', [SiteController::class, 'create']),
    Route::get('/name/{name}', function (string $name){
        return new \Framework\http\Response("Hello, $name");

    }),
    Route::post('/posts', [SiteController::class, 'store']),
    Route::get('/register', [RegisterController::class, 'form'], [Guest::class]),
    Route::post('/register', [RegisterController::class, 'register']),
    Route::get('/login', [LoginController::class, 'form'], [Guest::class]),
    Route::post('/login', [LoginController::class, 'login']),
    Route::post('/logout', [LoginController::class, 'logout']),
    Route::get('/dashboard', [DashboardController::class, 'index'], [Authenticate::class]),
];