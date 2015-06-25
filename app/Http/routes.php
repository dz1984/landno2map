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

$app->get('/', 'SiteController@index');
$app->get('/history', 'SiteController@history');
$app->post('upload', 'SiteController@upload');

$app->group(['namespace'=> 'App\Http\Controllers','prefix' => 'api'], function() use($app) {
    $app->get('version', 'ApiController@version');
    $app->get('time', 'ApiController@time');
    $app->get('date', 'ApiController@date');
    $app->get('land/render/{id}', 'ApiController@land_render');
    $app->get('land/list', 'ApiController@land_list');
});