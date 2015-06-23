<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller
{

  const CITY_FIELD_NAME = '縣市';
  const ROAD_FIELD_NAME = '路段';
  const LANDNO_FIELD_NAME = '段號';

  const API_URL = 'http://twland.ronny.tw/index/search';
  const UPLOAD_FILE_PATH = 'upload_files/csv/';

  private $was_parse_csv = false;

  private $response_json = [
    'status' => 'fail',
    'field_names' => [],
    'geo_json' => [],
    'msg' => ''
  ];

  private $record_list = [];
  private $filed_name_list = [];

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
        // TODO : throw an exception if appear undefined index.
        // 
        $city_name = trim($record[self::CITY_FIELD_NAME]);
        $road_name = trim($record[self::ROAD_FIELD_NAME]);
        $land_no = trim($record[self::LANDNO_FIELD_NAME]);

        $land_info = "$city_name,$road_name,$land_no";

        $api_url = $this->_generateAPIUrl($land_info);

        $content = file_get_contents($api_url);
        $feature_array = json_decode($content, TRUE);
        $feature = $feature_array['features'][0];

        // TODO : add the properties
        //
        foreach($this->field_name_list as $field_name) {
          $feature['properties'][$field_name] = $record[$field_name];
        }

        $geo_json['features'][] = $feature;

        // TODO : save the geo_json into db
        //
      }

      $this->response_json['status'] = 'success';
      $this->response_json['geo_json'] = $geo_json;
      $this->response_json['field_names'] = $this->field_name_list;
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
      $this->field_name_list = $field_name_list = fgetcsv($fp, 0, ",", "\"","\"");
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