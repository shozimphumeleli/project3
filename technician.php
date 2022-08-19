<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
require './config/functions.php';

$app = new \Slim\App;
$method = new Methods();

$app->map(['GET','POST'],'/districts', function (Request $request, Response $response){
  global $method;
  $region = $request->getParam('region');
  return json_encode($method->select("SELECT distinct line FROM pppabxznag.locations_by_line where line NOT LIKE '%,%' AND `region`='$region' ORDER BY line asc"));
});

$app->run();

?>
