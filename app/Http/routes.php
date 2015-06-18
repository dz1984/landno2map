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

$app->post('upload', function() use ($app) {
    // TODO : handle the csv file and make GeoJSON.
    // 
    $new_file_name = md5(rand());
    $target_path = 'upload_files/csv/';

    if (Request::hasFile('land_csv_file')) {
        $upload_file = Request::file('land_csv_file');
        // TODO : check the file is valid.
        $ext_file_name = $upload_file->getClientOriginalExtension();
        $new_file_name = "$new_file_name.$ext_file_name";
        $upload_file->move($target_path, $new_file_name);
    }
    
    return $new_file_name;
});

$app->get('/api/version', function() {
  return response()->json([
    'version' => API_VERSION,
  ]);
});
