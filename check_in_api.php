<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
require './config/functions.php';

$app = new \Slim\App;
$method = new Methods();

$app->map(['GET','POST'],'/user_messages', function (Request $request, Response $response){

    $data = json_decode(file_get_contents('php://input'));
    $district = $data->district;
    global $method;

    $sql_select = "SELECT * FROM checked_in_complain WHERE c_district LIKE '%$district%' ORDER BY `c_comp_id` DESC";
    $sql_exec = $method->select($sql_select);

    if($sql_exec){
        return json_encode(array("rows"=>count($sql_exec),"data"=>$sql_exec));
    }
    else{
        return json_encode(array("rows"=>0,"data"=>"[]"));
    }

});

$app->run();
