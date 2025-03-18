<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => 'auth'], function () {
    $webRoutes = glob(__DIR__ . '/web/*.php');

    foreach ($webRoutes as $route) {
        require $route;
    }
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
