<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller
{

  const API_URL = 'http://twland.ronny.tw/index/search';
  const UPLOAD_FILE_PATH = 'upload_files/csv/';

  private $was_parse_csv = false;

  private $response_json = [
    'status' => 'fail',
    'geo_json' => [],
    'msg' => ''
  ];

  private $record_list = [];

  public function upload(Request $request)
  {

    // TODO : handle the csv file and make GeoJSON.
    // 
    $new_file_name = md5(rand());
    $target_path = self::UPLOAD_FILE_PATH;

    if ($request->hasFile('land_csv_file')) {
        $upload_file = $request->file('land_csv_file');
        // TODO : check the file is valid.
        $ext_file_name = $upload_file->getClientOriginalExtension();
        $new_file_name = "$new_file_name.$ext_file_name";
        $upload_file->move($target_path, $new_file_name);
    }

    // TODO : parse the csv file
    //
    $full_new_file_name = $target_path.$new_file_name;

    $this->was_parse_csv = $this->_readCsvFile($full_new_file_name);

    // TODO : generate GeoJson
    //
    if ($this->was_parse_csv) {
      $geo_json = [
        "type"=>"FeatureCollection",
        "features" => []
      ];
      foreach($this->record_list as $record) {
        $city_name = trim($record['市名']);
        $road_name = trim($record['段名']);
        $land_no = trim($record['地號']);

        $land_info = "$city_name,$road_name,$land_no";

        // TODO : suppor the multiple rows
        // 
        $api_url = $this->_generateAPIUrl($land_info);

        $content = file_get_contents($api_url);
        $feature_array = json_decode($content, TRUE);
        $feature = $feature_array['features'];
        $geo_json['features'] = array_merge($geo_json['features'] ,$feature);
      }
      $this->response_json['status'] = 'success';
      $this->response_json['geo_json'] = $geo_json;
    }

    return response()->json($this->response_json);
  }

  private function _generateAPIUrl($info)
  {
    $api_url = self::API_URL.'?lands[]='.$info;

    return $api_url;
  }

  private function _readCsvFile($file_name)
  {
    $fp = fopen($file_name, 'r');
   
    if ( $fp !== FALSE) {
      $field_name_list = fgetcsv($fp, 0, ",", "\"","\"");
      $field_count = count($field_name_list);

      while (($data = fgetcsv($fp, 0, ",", "\"","\"")) !== FALSE) {
        for ($index = 0; $index < $field_count; $index++) {
          $record[$field_name_list[$index]] = $data[$index];
        }

        $this->record_list[] = $record;
      }

      fclose($fp);

      return true;
    }

    return false;
  }

}