<?php
$conn = mysqli_connect("178.128.45.152","pppabxznag","tYvepBm3CD","pppabxznag");

if(!$conn){
  die("Connection Failed");
}
else{
  echo "Connected Successfully";
}

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
require './config/functions.php';

$app = new \Slim\App;
$method = new Methods();
$app->map(['GET','POST'],'/test_route', function(Request $request, Response $response){
    echo "hello world";
});

$app->map(['GET','POST'],'/userlogin', function(Request $request, Response $response){
    
    session_start();

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = ''.date("Y:m:d H:i:s");

    $response = array();
    $email = $request->getParam('loginEmail');
    $password = $request->getParam('loginPwd');


    $sql_stmt="SELECT * FROM admin_user WHERE mail='$email' AND pwdRegister='$password'";

    $result = $method->select($sql_stmt);

    if ($result){
        $code="login_success";
        array_push($response,array("code"=>$code));
        return json_encode($response);
    }
    else{
        $code="login_failed";
        array_push($response,array("code"=>$code));
        return json_encode($response);
    }
        

});

$app->run();
?>