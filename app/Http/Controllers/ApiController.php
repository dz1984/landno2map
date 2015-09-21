<?php

namespace App\Http\Controllers;

use App\Summary;
use App\Detail;
use Illuminate\Http\Request;

class ApiController extends Controller
{
  const API_VERSION = '0.0.1';
  
  public function version()
  {
    return response()->json([
      'version' => self::API_VERSION,
    ]);
  }

  public function time()
  {
    return response()->json([
      'time' => time(),
    ]);
  }

  public function date()
  {
    return response()->json([
      'date' => date('Y-m-d'),
    ]);
  }

  public function land_list()
  {
    // TODO : list the already search of csv files.
    //
    $summary = Summary::all();
    $response_json = $summary;

    return response()->json($response_json);
  }

  public function land_render($id)
  {
    $response_json = $this->_find_land($id);

    return response()->json($response_json);
  }

  public function land_download($id)
  {
    $response_json = $this->_find_land($id);

    $status = 200;
    $headers = ['Content-disposition' => 'attachment'];

    return response()->json($response_json, $status, $headers);
  }

  private function _find_land($id)
  {
    // TODO : search the record via id and render it.
    //

    if (isset($id) === FALSE)
      return [
        'status' => 'fail',
        'msg' => 'ID is empty.'
      ];

    $summary = Summary::find($id);

    if ($summary == null)
      return [
        'status' => 'fail',
        'msg' => 'Could not find out any records.'
      ];

    $field_name_list = unserialize($summary->fields);
    $summary->increment("query_count");

    $details = Detail::where('summary_id', $id)->get();

    if ($details == null)
      return [
        'status' => 'fail',
        'msg' => 'Could not find any record.'
      ];

    $geo_json_list = [];

    foreach($details as $detail) {
      $geo_json_list[] = $detail->geo_json;
    }

    $geo_json = [
      "type" => "FeatureCollection",
      "features" => $geo_json_list
    ];

    return [
      'status' => 'success',
      'msg' => 'done',
      'id' => $id,
      'field_names' => $field_name_list,
      'geo_json' => $geo_json
    ];
  }
}