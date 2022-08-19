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

    $sql_select = "SELECT * FROM platform_complain pc inner join sikephi_users sur WHERE pc.p_comp_device_id = sur.device_id AND pc.districts LIKE '%$district%' order by pc.p_comp_id desc";
    $sql_exec = $method->select($sql_select);

    if($sql_exec){
        return json_encode(array("rows"=>count($sql_exec),"data"=>$sql_exec));
    }
    else{
        return json_encode(array("rows"=>0,"data"=>"[]"));
    }

});

$app->map(['GET','POST'],'/map_observer_replies', function (Request $request, Response $response){

    $data = json_decode(file_get_contents('php://input'));
    $district = $data->district;
    global $method;

    $sql_select = "SELECT * FROM  admin_platform_replies WHERE district='$district' ORDER BY a_reply_id DESC";
    $sql_exec = $method->select($sql_select);

    if($sql_exec){
        return json_encode(array("rows"=>count($sql_exec),"data"=>$sql_exec));
    }
    else{
        return json_encode(array("rows"=>0,"data"=>"[]"));
    }

});

$app->run();
