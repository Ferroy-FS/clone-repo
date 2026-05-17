<?php

use Illuminate\Support\Facades\Route;

// Just keep this simple
Route::get('/', function () {
    return view('welcome');
});