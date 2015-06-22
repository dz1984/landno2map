<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
  public function version()
  {
    return response()->json([
      'version' => API_VERSION,
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
    $response_json = [];

    return response()->json($response_json);
  }

  public function land_render($id)
  {
    // TODO : search the record via id and render it.
    //
    $response_json = [
      'status' => 'fail',
      'msg' => 'Error'
    ];

    if (isset($id)) {
      $response_json = [
        'id' => $id,
        'geo_json' => []
      ];
    }

    return response()->json($response_json);
  }
}