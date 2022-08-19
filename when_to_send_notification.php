<?php
/**
 * Write a message to a file in the same directory
 */

date_default_timezone_set('Africa/Johannesburg');

require 'config/functions.php';

$now = date('d-m-yy H:i');

$file = dirname(__FILE__) . '/testfile.txt';
$data = "";

$method = new Methods();


// Select from the notification table
$sql_query = "SELECT * FROM `user_notifications` WHERE `notification_status`='1'";

$exec_sel = $method->select($sql_query);

if($exec_sel){

    foreach($exec_sel as $val){
        $not_date = substr($val["notification_send_date"], 0, -3);

        if($not_date == $now){
            //send notification
            $token = $method->getToken($val["device_id"]);
            $res = $method->sendNotification($not_date,$val["notification_message"],$token,$val["not_type"]);
            $data .= "Now ".$now." Notification date ".$not_date."Equal \n";

            echo $res;
        }
        else{
            // $data .= "Now ".$now." Notification date ".$not_date." Not equal\n";
        }

    }

    file_put_contents($file, $data, FILE_APPEND);

}
else{
    echo "Hello World";
}