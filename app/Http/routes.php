<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

const API_VERSION = '0.0.1';

$app->get('/', function () use ($app) {
    return view('index');
});

$app->post('upload', 'SiteController@upload');

$app->get('/api/version', function() {
  return response()->json([
    'version' => API_VERSION,
  ]);
});
