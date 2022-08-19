<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
require './config/functions.php';

$app = new \Slim\App;
$method = new Methods();

$app->map(['GET','POST'],'/districts', function (Request $request, Response $response){
  global $method;
  $data = json_decode(file_get_contents('php://input'));
  $region = $request->getParam('region');
  return json_encode($method->select("SELECT distinct line FROM pppabxznag.locations_by_line where line NOT LIKE '%,%' AND `region`='$region' ORDER BY line asc"));
});

$app->map(['GET','POST'],'/trains_from_district', function (Request $request, Response $response){
  global $method;
  $data = json_decode(file_get_contents('php://input'));
  $district = $data->district;

  $results = $method->select("SELECT distinct title FROM pppabxznag.locations_by_line where line like '%$district%'");
  if ($results) {
    $encode_results = json_encode($results);
    $decode_results = json_decode($encode_results);

    $stations = "";

    for ($i=0; $i < count($decode_results); $i++) {
      $value = $decode_results[$i];
      if ($i == count($decode_results) - 1) {
        $stations .= "'$value->title'";
       } else {
         $stations .= "'$value->title'".',';
       }
      // code...
    }


    // select trains in $stations
    if (!empty($stations)) {
      // code...
      $sql_select = "SELECT distinct train_no FROM pppabxznag.trains where train_route in($stations)";
      $results_exec = $method->select($sql_select);
      if ($results_exec) {
        echo json_encode(array("rows"=>count($results_exec),"data"=>$results_exec));
      }
      else {
        echo json_encode(array("rows" => 0, "data"=>[]));
      }
    }
  }
});

$app->run();
