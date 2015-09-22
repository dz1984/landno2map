<?php

namespace App\Http\Controllers;

use App\Summary;
use App\Detail;
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
  private $field_name_list = [];

  public function index()
  {
    return view('index');
  }

  public function history()
  {
    $summaries = Summary::orderBy('id')->get();


    return view('history')->with('summaries', $summaries);
  }

  public function render($id)
  {
    $data = ['land_id' => $id];
    return view('render', $data);
  }

  public function upload(Request $request)
  {

    // handle the csv file and make GeoJSON.
    $unique_hash_code = md5(rand());
    $target_path = self::UPLOAD_FILE_PATH;

    if (FALSE == $request->hasFile('land_csv_file')) {
      $this->response_json['msg'] = '找不到上傳檔案，在重新上傳一次！！！';
      return response()->json($this->response_json);
    }


    $upload_file = $request->file('land_csv_file');

    // check the file is valid.
    $ext_file_name = $upload_file->getClientOriginalExtension();
    $new_file_name = "$unique_hash_code.$ext_file_name";
    $upload_file->move($target_path, $new_file_name);
    

    // parse the csv file
    $full_new_file_name = $target_path.$new_file_name;

    $this->was_parse_csv = $this->_readCsvFile($full_new_file_name);

    // generate GeoJson
    if (FALSE == $this->was_parse_csv) {
      $this->response_json['msg'] = '好像沒辦法解析檔案喔！檢查過後再重新上傳一次！！！';
      return response()->json($this->response_json);
    }
    $geo_json = [
      "type"=>"FeatureCollection",
      "features" => []
    ];

    $summary = new Summary;
    $summary->hash_code = $unique_hash_code;
    $summary->fields = serialize($this->field_name_list);
    $summary->save();

    $land_id = $summary->id;

    foreach($this->record_list as $record) {

      $land_info_str = $this->_generateLandInfoStr($record);

      $feature_array = $this->_queryFeature($land_info_str);

      // handle the Undefined index ErrorException
      $feature = $feature_array['features'][0];

      // add the properties
      $feature['properties'] = array_merge($feature['properties'], $record);

      $geo_json['features'][] = $feature;

      // save the geo_json into db
      $detail = new Detail;
      $detail->summary_id = $summary->id;
      $detail->geo_json = json_encode($feature);
      $detail->properties = serialize($record);
      $detail->save();
    }

    $this->response_json['land_id'] = $land_id;
    $this->response_json['status'] = 'success';
    $this->response_json['geo_json'] = $geo_json;
    $this->response_json['field_names'] = $this->field_name_list;

    return response()->json($this->response_json);
  }

  private function _generateLandInfoStr($record)
  {
    // TODO : throw an exception if appear undefined index.
    //

    $city_name = trim($record[self::CITY_FIELD_NAME]);
    $road_name = trim($record[self::ROAD_FIELD_NAME]);
    $land_no = trim($record[self::LANDNO_FIELD_NAME]);

    $land_info_str = "$city_name,$road_name,$land_no";

    return $land_info_str;
  }

  private function _queryFeature($land_info_str)
  {
    $api_url = $this->_generateAPIUrl($land_info_str);

    $content = file_get_contents($api_url);

    $feature_array = json_decode($content, TRUE);

    return $feature_array;
  }

  private function _generateAPIUrl($info)
  {
    $api_url = self::API_URL.'?lands[]='.urlencode($info);

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