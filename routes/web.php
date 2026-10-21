<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('dss');
});
// Route::get('/dss-area', function () {
//     return view('dss-area');
// });

Route::get('/dss-area', function () {
    return view('dssarea');
});