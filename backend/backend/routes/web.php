<?php

 use Illuminate\Support\Facades\Route;

 Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return 'Hello from Laravel!';
});

Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});
