<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
require './config/functions.php';

$app = new \Slim\App;
$method = new Methods();

$app->map(['GET','POST'],'/getMessages', function (Request $request, Response $response){

    global $method;
    $data = json_decode(file_get_contents('php://input'));
    $district = $data->district;
    $sql_exec = $method->select("SELECT * FROM admin_platform_queries Where district='$district' ORDER BY a_reply_id DESC");

    if($sql_exec){
        return json_encode(array("rows"=>count($sql_exec),"data"=>$sql_exec));
    }
    else {
      return json_encode(array("rows"=>0,"data"=>"[]"));
    }

});


$app->map(['GET','POST'],'/getQueries', function (Request $request, Response $response){

  global $method;
  $data = json_decode(file_get_contents('php://input'));
  $district = $data->district;
  



  //$sql_select = "SELECT * FROM checked_in_complain WHERE c_district = '$district'";
  $sql_exec = $method->select("SELECT * FROM checked_in_complain Where tag_comp ='0' ");

  if($sql_exec){
      return json_encode(array("rows"=>count($sql_exec),"data"=>$sql_exec));
  }
  else {
    return json_encode(array("rows"=>0,"data"=>"[]"));
  }

});



$app->map(['GET','POST'],'/driver_updates', function(Request $request,Response $response){
  global $method;
  $data = json_decode(file_get_contents('php://input'));
  $district = $data->district;
  $sql_exec = $method->select("SELECT * FROM driver_updates WHERE district like '%$district%'");
  if($sql_exec){
      return json_encode(array("rows"=>count($sql_exec),"data"=>$sql_exec));
  }
  else{
     return json_encode(array("rows"=>0,"data"=>"[]"));
  }
});

$app->map(['GET','POST'],'/comm_notification', function(Request $request,Response $response){
  global $method;
  $data = json_decode(file_get_contents('php://input'));
  $msg = $data->message;
  $train_no = $data->train_no;

  $tokens = [];

  /** Get all the users who booked train no */
  $sql_select = "SELECT * FROM pppabxznag.bookings where `train_no`='$train_no'";

  $sql_exec = $method->select($sql_select);

  if($sql_exec){

    $count = count($sql_exec);

    $device_ids = "";

    for($i=0;$i<$count;$i++){
      $value = $sql_exec[$i];
      if($i == $count  - 1){
        $device_ids .= "'".$value["device_id"]."'";
      }else{
        $device_ids .= "'".$value["device_id"]."'".",";
      }
    }   

    if(!empty($device_ids)){

      /** Select all tokens using device id from register table */
      $sql_sel_tokens = "SELECT * FROM pppabxznag.fcm_token where `device_id` in ($device_ids)";
      $toke_exec = $method->select($sql_sel_tokens);

      if($toke_exec){
        $count_1 = count($toke_exec);

        for($j=0;$j<$count_1;$j++){
          $value = $toke_exec[$j];
          array_push($tokens,$value["token"]);
        }

        $tokens_count = count($tokens);

        if($tokens_count > 0){

          for($t=0;$t<$tokens_count;$t++){

            // echo $tokens[$t]."<br/>"." ";

          define( 'API_ACCESS_KEY', 'AAAA88VD3Wg:APA91bEHHjpJwSvuEzmm0TT8YYNCJy8j1-D64s8lYaDKiAUnsMbIhj2ZL1xyuJqlzRh6A_Y-T4U2emCngwtjYJvDpcc6yXIs7SKGOtXE4wkQtuLOeLjzhF_NWEmRlQ3gj0P2vtOPlnDv' );

          $data = array('title'=>'SikephiApp','message'=> $msg);
          $fields = array
          (
             'to'  => $tokens[$t],
            'data' => $data
          );
  
          $headers = array
          (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
          );

          $ch = curl_init();
          curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
          curl_setopt( $ch,CURLOPT_POST, true );
          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
          curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
          curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
          curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
          $result = curl_exec($ch );
           if ($result === FALSE) {
            die('Oops! FCM Send Error: ' . curl_error($ch));
             }else{
              //  echo "notification sent successfully";
             }
          curl_close( $ch );
          }
          return json_encode(array("rows"=>1,"data"=>"MESSAGE SENT SUCCESSFULLY"));


        }
        else{
          return json_encode(array("rows"=>0,"data"=>"MESSAGE NOT SENT SUCCESSFULLY"));
        }
      }
      else{
        return json_encode(array("rows"=>0,"data"=>"MESSAGE NOT SENT SUCCESSFULLY"));
      }

    }else{
      return json_encode(array("rows"=>0,"data"=>"MESSAGE NOT SENT SUCCESSFULLY"));
    }

  }else{
    return json_encode(array("rows"=>0,"data"=>"MESSAGE NOT SENT SUCCESSFULLY"));
  }

});



$app->map(['GET','POST'],'/plantform', function (Request $request, Response $response){

    global $method;
    $data = json_decode(file_get_contents('php://input'));
    $district = $data->district;
    
  
  
  
    //$sql_select = "SELECT * FROM checked_in_complain WHERE c_district = '$district'";
    $sql_exec = $method->select("SELECT * FROM  platform_complain Where tag_comp ='0' ");

   $sql_exec = $method->select("Select  t1.p_comp_id,  t1.p_comp_device_id, t1.p_comp_text, t1.p_comp_date, t1.districts,  t2.status  ,t2.a_reply_text from platform_complain t1  INNER JOIN admin_platform_replies  t2 ON t1.a_reply_id = t2.a_reply_id" );
  
    if($sql_exec){
        return json_encode(array("rows"=>count($sql_exec),"data"=>$sql_exec));
    }
    else {
      return json_encode(array("rows"=>0,"data"=>"[]"));
    }
  
  });


$app->map(['GET','POST'],'/ggeneral', function (Request $request, Response $response){

    global $method;
    $data = json_decode(file_get_contents('php://input'));
    $district = $data->district;
    
  
  
  
    //$sql_select = "SELECT * FROM checked_in_complain WHERE c_district = '$district'";
    $sql_exec = $method->select("select  t1.g_id,t1.device_id, t1.g_text , t1.g_date, t1.status, t2.cm_id  ,t2.comp_text from general_rpl t1  INNER JOIN complain  t2 ON t1.cm_id = t2.cm_id ");
  
    if($sql_exec){
        return json_encode(array("rows"=>count($sql_exec),"data"=>$sql_exec));
    }
    else {
      return json_encode(array("rows"=>0,"data"=>"[]"));
    }
  
  });
  

$app->map(['GET','POST'],'/engineer_chat',function(Request $request, Response $response){
  date_default_timezone_set('Africa/Johannesburg');
  $current_date = ''.date("Y:m:d H:i:s");
  global $method;
  $data = json_decode(file_get_contents('php://input'));
  $user_id = $data->user_id;
  $eng_message = $data->eng_message;
  $device_id = $data->device_id;
  $district = $data->district;

  $sql_select = $method->select("SELECT `device_id` FROM pppabxznag.engineers_reg WHERE `user_id`='$user_id' GROUP BY device_id");

  if($sql_select){
      $arr_enc = json_encode($sql_select);
      $arr_dec = json_decode($arr_enc);
      $device_id = "";
      foreach($arr_dec as $value){
          $device_id = $value->device_id;
      }

      $sql_msg_insert = "INSERT INTO pppabxznag.engineer_messages(`msg_id`,`message`,`msg_type`,`msg_inteded_for`,`msg_date`,`msg_status`,`user_device_id`,`district`)VALUE(null,'$eng_message','in','Field','$current_date','unseen','$device_id','$district')";

      $sql_inser = $method->query($sql_msg_insert);

      if($sql_inser){
          $sql_select_token = $method->select("SELECT * FROM pppabxznag.eng_tokens WHERE `eng_device_id`='$device_id'");
          if($sql_select_token){
              $res_enc = json_encode($sql_select_token);
              $res_dec = json_decode($res_enc);

              foreach($res_dec as $tk_value){
                  $token = $tk_value->eng_token;
              }

              define('API_ACCESS_KEY', 'AAAA3oWYidM:APA91bFR5O7MSPDdtfSuHaRoeThcHyreeygzhtpF7pdUqiR30-7LguvhWhmjZZBmbfYhwan-B6I0SWGcxW03ZjHp4znPJjE92QGN3zS-5f41LVRdlGJBYdSSj8nk-75KJcpbx2KcLJEw');
              // API access key from Google API's Console
              $data = array('title'=>'SikephiApp','message'=> $eng_message);

              $fields = array
              (
                 'to'  => $token,
                'data' => $data
              );


              $headers = array
              (
                'Authorization: key=' . API_ACCESS_KEY,
                'Content-Type: application/json'
              );

              $ch = curl_init();
              curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
              curl_setopt( $ch,CURLOPT_POST, true );
              curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
              curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
              curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
              curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
              $result = curl_exec($ch );
               if ($result === FALSE) {
                die('Oops! FCM Send Error: ' . curl_error($ch));
                 }
                 else{

                     curl_close( $ch );
                     return json_encode(array("rows"=>1,"data"=>"notification sent sucessfully"));
                 }
          }
          else{
            return json_encode(array("rows"=>0,"data"=>"can't select"));
          }

      }
      else{
        return json_encode(array("rows"=>0,"data"=>"can't insert to engineer messages"));
      }
  }
  else{
    return json_encode(array("rows"=>0,"data"=>"can't fetch device id_ ".$user_id));
  }

});




$app->map(['GET','POST'],'/check_in_admin_replies', function(Request $request, Response $redponse){

  session_start();
  date_default_timezone_set('Africa/Johannesburg');
  $current_date = ''.date("Y:m:d H:i:s");

  $train_no = $request->getParam('train_no');
  $reply_txt = $request->getParam('txt_not');
  $user_id = $request->getParam('device_id');
  $comp_id = $request->getParam('comp_id');
  global $method;

  echo $user_id." ".$reply_txt." ".$train_no." ".$comp_id;

  $sql_statement = "INSERT INTO `admin_check-in_replies`(`ad_chk_id`, `ad_chk_text`, `ad_chk_name`, `ad_chk_train_no`, `ad_chk_timestamp`, `user_chk_id`, `comp_chk_id`, `status_chk`) VALUES (null,'$reply_txt','$admin_name','$train_no','$current_date','$user_id','$comp_id','unseen')";

  $results = $method->query($sql_statement);

    if($results){
        return json_encode(array("rows"=>1,"data"=>"MESSAGE SENT SUCCESSFULLY"));
  }
  else{
    return json_encode(array("rows"=>0,"data"=>"MESSAGE SENT SUCCESSFULLY"));
  }

});

$app->map(['GET','POST'],'/forward_to_agent', function(Request $request, Response $redponse){

  session_start();
  date_default_timezone_set('Africa/Johannesburg');
  $current_date = ''.date("Y:m:d H:i:s");
  $data = json_decode(file_get_contents('php://input'));

  $train_no = $data->train_no;
  $reply_txt = $data->txt_not;
  // $comp_id = $request->getParam('comp_id');
  global $method;

  $sql_statement = "INSERT INTO `driver_query`(`dr_query_id`, `dr_query_text`, `dr_query_train_no`, `dr_quey_date`,`status`) VALUES (null,'$reply_txt','$train_no','$current_date','unseen')";

  $results = $method->query($sql_statement);

  $response = array();

  if($results){
    return json_encode(array("rows"=>1,"data"=>"MESSAGE SENT SUCCESSFULLY"));
  }
else{
    return json_encode(array("rows"=>0,"data"=>"MESSAGE SENT SUCCESSFULLY"));
  }

});



$app->run();
