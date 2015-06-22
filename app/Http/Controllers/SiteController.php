<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller
{

  public function upload(Request $request)
  {
    // TODO : handle the csv file and make GeoJSON.
    // 
    $new_file_name = md5(rand());
    $target_path = 'upload_files/csv/';

    if ($request->hasFile('land_csv_file')) {
        $upload_file = $request->file('land_csv_file');
        // TODO : check the file is valid.
        $ext_file_name = $upload_file->getClientOriginalExtension();
        $new_file_name = "$new_file_name.$ext_file_name";
        $upload_file->move($target_path, $new_file_name);
    }

    // TODO : parse the csv file
    // 
    

    // TODO : generate GeoJson
    //
    $geo_json = [];

    $response_json = [
      'status' => 'success',
      'geo_json' => $geo_json
    ];

    return response()->json($response_json);
  }
}