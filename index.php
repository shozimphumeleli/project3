<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
require './config/functions.php';

$app = new \Slim\App;
$method = new Methods();

$app->map(['GET', 'POST'], '/hello', function (Request $request, Response $response) {

    global $method;
    return 'hello';

global $method;

});

$app->map(['GET', 'POST'], '/logout', function (Request $request, Response $responce) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");


    if (isset($_POST['logout'])) {
        session_start();
        session_unset();
        return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/index.php');
    }
});

$app->map(['GET', 'POST'], '/userRegistration', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");


    if (isset($_POST['submit-register'])) {


        session_start();
        $firstName = $request->getParam('firstName');
        $lastName = $request->getParam('lastName');
        $mail = $request->getParam('mail');
        $pwdRegister = $request->getParam('pwdRegister');
        $pwdConfirm = $request->getParam('pwdConfirm');
        $department = $request->getParam('department');

        global $method;

        echo $department;


        if (empty($firstName) || empty($lastName) || empty($mail) || empty($pwdRegister) || empty($pwdConfirm) || empty($department)) {
            echo 'empty fields';
            return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/register.php?error=emptyfields&name=' . $firstName . '&lastname=' . $lastName . '&mail=' . $mail);
            exit();
        } else if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            echo 'invalid email';
            return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/register.php?error=invalidemail&name=' . $firstName . '&lastname=' . $lastName);
            exit();
        } elseif ($pwdRegister !== $pwdConfirm) {
            echo 'password dont match';
            $_SESSION['pwdError'] = "Password Don't match";
            return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/register.php?error=passwordcheck&name=' . $firstName . '&lastname=' . $lastName . '&mail=' . $mail);
            exit();
        } else {
            // TO-DO sql prepare statement
            //  echo 'email already exists';
            $sql_statement = "SELECT `mail`FROM `admin_user` WHERE `mail`='$mail'";
            $results = $method->select($sql_statement);

            if ($results) {
                return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/register.php?error=usertaken&name=' . $firstName . '&lastname=' . $lastName);
                exit();
            } else {

                // echo 'fields inserted';
                $sql_statement = "INSERT INTO `admin_user`(`uid`, `firstName`, `lastName`, `mail`, `pwdRegister`, `pwdConfirm`, `department`) VALUES (null,'$firstName','$lastName','$mail','$pwdRegister','$pwdConfirm','$department')";

                $result = $method->query($sql_statement);

                if ($result) {

                    echo 'Hello';

                    return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/register-success.php');
                }
            }
        }
    }
});

$app->map(['GET', 'POST'], '/newtest', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $name = $request->getParam('name');
    $contact_no = $request->getParam('contact_no');
    $email_address = $request->getParam('email_address');
    $home_station = $request->getParam('home_station');
    $train_no = $request->getParam('train_no');

    // echo $name, $contact_no, $email_address, $home_station, $train_no;

    global $method;

    $sql = "INSERT INTO `test` (`id`, `name`, `contact_no`, `email_address`, `home_station`, `train_no`) VALUES (null, '$name', '$contact_no', '$email_address', '$home_station', '$train_no')";

    $results = $method->query($sql);

    if ($results) {
        echo 'user registered successfully';
    } else {
        echo 'error in registration';
    }
});

$app->map(['GET', 'POST'], '/login', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $email = $request->getParam('email_address');
    $password = $request->getParam('Password');

    global $method;
    $result = $method->select("SELECT * FROM `users_table` WHERE `email` like '$email' AND `password`='$password'");

    $responce = array();

    if ($result) {

        $code = "login_success";
        $message = "login sccessfully...";
        array_push($responce, array("code" => $code, "message" => $message));

        return json_encode($responce);
    } else {
        $code = "login_failed";
        $message = "login failed...";
        array_push($responce, array("code" => $code, "message" => $message));

        return json_encode($responce);
    }
});

$app->map(['GET', 'POST'], '/signup', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $name = $request->getParam('dr_name');
    $contact = $request->getParam('dr_contact_no');
    $email = $request->getParam('dr_email');
    $password = $request->getParam('dr_pass');
    $employeeno = $request->getParam('dr_employee_no');
    $region = $request->getParam('region');
    $device_id = $request->getParam('device_id');

    global $method;

    $check_driver = "SELECT * FROM 'users_table' WHERE '$email'"; //

    $check_results = $method->select($check_driver); //

    if ($check_results) {
        $code = "sign_up_failed";
        $message = "sign up failed...";
        array_push($responce, array("code" => $code, "message" => $message));

        return json_encode($responce);
    } else {
        $sql_statement = "INSERT INTO `users_table`(`user_id`, `access_id`, `name`, `contact_no`, `email`, `password`, `employee_no`, `region`, `device_id`) VALUES (null,'1','$name','$contact','$email','$password','$employeeno','$region','$device_id')";


        $result = $method->query($sql_statement);

        $responce = array();

        if ($result) {

            $code = "sign_up_success";
            $message = "sign up successfully...";
            array_push($responce, array("code" => $code, "message" => $message));

            return json_encode($responce);
        } else {
            $code = "sign_up_failed";
            $message = "sign up failed...";
            array_push($responce, array("code" => $code, "message" => $message));

            return json_encode($responce);
        }
    }
});

$app->map(['GET', 'POST'], '/en_login', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");


    $email = $request->getParam('email_address');
    $password = $request->getParam('Password');
    $responce = array();
    global $method;

    $result = $method->select("SELECT * FROM `engineers_reg` WHERE `email` like '$email' AND `password`='$password'");

    if ($result) {

        $result = $method->select("SELECT * FROM `engineers_reg` WHERE `email` like '$email' AND `password`='$password'");

        $responce = array();

        $code = "login_success";
        $message = "login sccessfully...";
        array_push($responce, array("code" => $code, "message" => $message));

        return json_encode($responce);
    } else {
        $code = "login_failed";
        $message = "login failed...";
        array_push($responce, array("code" => $code, "message" => $message));

        return json_encode($responce);
    }
});

$app->map(['GET', 'POST'], '/en_sign_up', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $name = $request->getParam('full_name');
    $contact = $request->getParam('contact_no');
    $email = $request->getParam('email_address');
    $password = $request->getParam('password');
    $employeeno = $request->getParam('employee_no');
    $region = $request->getParam('region');
    $device_id = $request->getParam('device_id');

    global $method;

    $check_user = "SELECT * FROM `engineers_reg` WHERE `email`='$email'";

    $check_results = $method->select($check_user);

    $responce = array();


    if ($check_results) {
        $code = "user_exists";
        $message = "User already exist.";
        array_push($responce, array("code" => $code, "message" => $message));
        return json_encode($responce);
    } else {

        $sql_stmt = "INSERT INTO `engineers_reg`(`user_id`, `en_name`, `contact_no`, `email`, `password`, `employee_no`,`region`,`status`,`device_id`,`work_area`,`district`)
                        VALUES (null,'$name','$contact','$email','$password','$employeeno','$region','n','$device_id','','')";

        $result = $method->query($sql_stmt);


        if ($result) {
            $code = "sign_up_success";
            $message = "sign up successfully...";
            array_push($responce, array("code" => $code, "message" => $message));
            return json_encode($responce);
        } else {
            $code = "sign_up_failed";
            $message = "sign up failed...";
            array_push($responce, array("code" => $code, "message" => $message));
            return json_encode($responce);
        }
    }
});

$app->map(['GET', 'POST'], '/newComplaint', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");


    $comp_text = $request->getParam('complain');
    $device_id = $request->getParam('device_id');
    $home_station = $request->getParam('home_station');
    $comp_img_url = $request->getParam('comImg');

    global $method;

    //get district
    $sql_get_district = "SELECT distinct `line` FROM pppabxznag.locations_by_line where `title` =  '$home_station'";

    $sql_get_exec = $method->select($sql_get_district);

    if ($sql_get_exec) {
        $encode_msg = json_encode($sql_get_exec);
        $decode_msg = json_decode($encode_msg);

        $district = $decode_msg[0]->line;


        $sql = "INSERT INTO `complain`(`cm_id`, `comp_text`, `comp_date`, `comp_img`,`device_id`, `districts`)
                 VALUES (null,'$comp_text','$current_date','$comp_img_url','$device_id', '$district')";

        $results = $method->query($sql);

        $response = array();

        if ($results) {
            $code = "comp_success";

            array_push($response, array("code" => $code));
            return json_encode($response);
        } else {
            $code = "comp_failed";
            array_push($response, array("code" => $code));

            return json_encode($response);
        }
    }
});

$app->get('/hello/{name}', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello $name");

    return $response;
});

$app->map(['GET', 'POST'], '/locations', function (Request $request, Response $redponse) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    global $method;

    // echo "called";
    $device_id = $request->getParam('device_id');

    $sql_select_region = "SELECT DISTINCT `region` FROM sikephi_users WHERE `device_id`='$device_id'";

    $reg_results = $method->select($sql_select_region);

    if ($reg_results) {
        $encoded_region = json_encode($reg_results);
        $decoded_region = json_decode($encoded_region);

        $region = $decoded_region[0]->region;

        $sql_statement = "SELECT * FROM `locations_by_line` WHERE region='$region' ORDER BY `title` ASC";

        $results = $method->select($sql_statement);

        $response = array();

        if ($results) {
            return json_encode($results);
        } else {
            echo 'Cant fetch locations';
        }
    }

    // $results = $method->select($sql_statement);

    // $sql_statement = "SELECT * FROM `locations_by_line` WHERE placeholder='KZN' ORDER BY `title` ASC";

    // $results = $method->select($sql_statement);

    // $response = array();

    // if($results){
    //     return json_encode($results);
    // }
    // else{
    //     echo 'Cant fetch locations';
    // }

});

$app->map(['GET', 'POST'], '/trains', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $now = new DateTime();
    $st_dest = $request->getParam('st_dest');
    $st_time = $request->getParam('st_time');
    $st_depart = $request->getParam('st_depart');
    $st_train_no = $request->getParam('op_train_no');

    $st_time_plus_six_hrs = date('H:i:s', strtotime($st_time) + 108000);


    // print_r([$st_dest,$st_time,$st_depart,$st_train_no]);

    global $method;
    $district = "";

    // AND trains.train_time >='$st_time'
    // AND trains.train_time <='$st_time_plus_six_hrs'

    $sql_statement = "SELECT tr.train_id, tr.train_route, tr.platform_no, tr.train_time, tr.arrival_time, tr.train_no, tr.train_logo, lbl.line, tr.comments, tr.statuss
    FROM trains tr INNER JOIN locations_by_line lbl
    ON tr.train_route = lbl.title
    WHERE tr.train_route='$st_depart'
    AND tr.statuss='available'
    AND tr.train_no LIKE '%$st_train_no%'
    AND tr.train_time >= '$st_time'
    AND tr.train_time < '$st_time_plus_six_hrs'
    ORDER BY tr.train_time ASC";

    $results = $method->select($sql_statement);

    $response_1 = array();
    $response_2 = array();



    if ($results) {

        //get arrival info
        $encoded_results = json_encode($results);
        $decoded_results = json_decode($encoded_results);


        // return $encoded_results;
        foreach ($decoded_results as $decoded_result) {

            $train_id = $decoded_result->train_id;
            $train_route = $decoded_result->train_route;
            $platform_no = $decoded_result->platform_no;
            $train_time = $decoded_result->train_time;
            $arrival_time = $decoded_result->arrival_time;
            $train_no = $decoded_result->train_no;
            $train_logo = $decoded_result->train_logo;
            $line = $decoded_result->line;
            $comments = $decoded_result->comments;
            $statuss = $decoded_result->statuss;

            if (!empty($train_no)) {

                // echo $st_dest;
                $sql_select = "SELECT `arrival_time` FROM `trains` WHERE `train_no`='$train_no' AND `train_route`='$st_dest'";

                $final_results = $method->select($sql_select);

                if ($final_results) {

                    $encode_final_results = json_encode($final_results);
                    $decode_final_results = json_decode($encode_final_results);

                    foreach ($decode_final_results as $decode_final_result) {

                        $dest_arrival_time = $decode_final_result->arrival_time;


                        array_push($response_1, array("train_id" => $train_id, "train_route" => $train_route, "platform_no" => $platform_no, "train_time" => $train_time, "arrival_time" => $arrival_time, "train_no" => $train_no, "train_logo" => $train_logo, "line" => $line, "destination" => $st_dest, "dest_arrive" => $dest_arrival_time, "comments" => $comments, "status" => $statuss, "transferring" => "no"));

                        // echo 'arrival time : '.$arrival_time.'<br>';

                    }
                } elseif (!$final_results) {
                    //get line for departure
                    $sql_stmt = "SELECT `line` FROM `locations_by_line` WHERE `title`='$st_depart'";

                    $test_results = $method->select($sql_stmt);

                    if ($test_results) {

                        $depart_line = $test_results[0]['line'];

                        if (!empty($depart_line)) {

                            //get the transferring stations
                            $sql_transfr = "SELECT tp.trains_title, tr.arrival_time, tr.train_time 
                                        FROM transfr_platforms tp
                                        INNER JOIN trains tr
                                        ON tp.trains_title = tr.train_route               
                                        WHERE tr.train_no='$train_no' 
                                        AND tp.trans_line 
                                        LIKE '%$depart_line%'";

                            // $sql_tranfer="SELECT `trains_title` FROM `transfr_platforms` WHERE `trans_line` ";

                            $test_results = $method->select($sql_transfr);


                            if ($test_results) {
                                $encode_sql_tranfer = json_encode($test_results);
                                $decode_sql_tranfer = json_decode($encode_sql_tranfer);

                                foreach ($decode_sql_tranfer as $decode_sql_tran) {

                                    $temp_dest = $decode_sql_tran->trains_title;
                                    $arrive_time = $decode_sql_tran->arrival_time;

                                    //select the next available train_no after the current train arrives at destination

                                    $sql_next_available = $method->select("SELECT tr_1.train_no, tr_1.arrival_time, tr_1.train_time, tr_1.platform_no, tr_2.arrival_time as final_arrival FROM `trains` tr_1 left outer join `trains` tr_2 ON tr_1.train_no = tr_2.train_no WHERE tr_1.train_route='$temp_dest' AND tr_1.arrival_time > '$arrive_time' AND tr_1.statuss = 'available' AND tr_2.train_route='$st_dest'  LIMIT 1");

                                    if ($sql_next_available) {

                                        // print_r($sql_next_available);
                                        $next_av_train = json_encode($sql_next_available);
                                        $decode_next_av_train = json_decode($next_av_train);

                                        foreach ($decode_next_av_train as $next_train_no) {

                                            $transfer_train_no = $next_train_no->train_no;
                                            $platform_no_next = $next_train_no->platform_no;
                                            $sec_train_arrive = $next_train_no->arrival_time;
                                            $sec_train_depart = $next_train_no->train_time;
                                            $final_arrival = $method->getArrivalTime($next_train_no->train_no, $st_dest);


                                            array_push($response_2, array("train_id" => $train_id, "train_route" => $train_route, "platform_no" => $platform_no, "train_time" => $train_time, "arrival_time" => $arrival_time, "train_no" => $train_no, "train_logo" => $train_logo, "line" => $line, "destination" => $temp_dest . ' ' . $transfer_train_no, "dest_arrive" => $arrive_time, "comments" => $comments, "status" => $statuss, "sec_train_arrive" => $sec_train_arrive . ' PL-' . $platform_no_next, "sec_train_depart" => $sec_train_depart, "final_arrival" => $final_arrival, "transferring" => "yes"));
                                        }
                                    }
                                }
                                //   return json_encode($response);


                            }
                        }
                    }
                } else {
                    // return json_encode($results);
                }
            } else {
                echo "Hello error!";
            }
        }
        if (sizeof($response_1) > 0) {
            return json_encode($response_1);
        } elseif (count($response_2) > 0) {
            return json_encode($response_2);
        }
    } else {
        echo 'Cant fetch loca';
    }
});

$app->map(['GET', 'POST'], '/complains', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    global $method;
    $data = json_decode(file_get_contents('php://input'));
    $district = $data->district;

    $sql_statement = "SELECT * FROM pppabxznag.complain where districts like ='%$district%' ORDER BY cm_id DESC ";

    $results = $method->select($sql_statement);

    if ($results) {
        return json_encode($results);
    } else {
        echo 'Cant fetch loca';
    }
});

// Wednesday 5/18

$app->map(['GET', 'POST'], '/newNot', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $data = json_decode(file_get_contents('php://input'));


    $not_text = $data->notif_panel;
    $district = $data->district;
    $current_time = '' . date("Y:m:d H:i:s");

    // echo str_replace(" ","",$district);


    define('API_ACCESS_KEY', 'AAAA88VD3Wg:APA91bEHHjpJwSvuEzmm0TT8YYNCJy8j1-D64s8lYaDKiAUnsMbIhj2ZL1xyuJqlzRh6A_Y-T4U2emCngwtjYJvDpcc6yXIs7SKGOtXE4wkQtuLOeLjzhF_NWEmRlQ3gj0P2vtOPlnDv');

    $data = array('title' => 'SikephiApp', 'message' => $not_text);
    $fields = array(
        'to'  => '/topics/' . str_replace(" ", "", $district),
        'data' => $data
    );

    $headers = array(
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Oops! FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);



    global $method;

    $sql_statement = "INSERT INTO `news_tables`(`news_updates_id`, `news_updates_text`, `news_updates_date`, `district`) VALUES (null,'$not_text','$current_time','$district')";

    $result = $method->query($sql_statement);

    if ($result) {

        return json_encode(array("rows" => 1, "data" => "SUCCESS", "message" => $not_text));
    } else {
        return json_encode(array("rows" => 0, "data" => "ERROR"));
    }
});

$app->get('/driver_updates', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    return json_encode($method->select("SELECT * FROM `driver_updates`"));
});

$app->map(['GET', 'POST'], '/call_center_agents', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $not_text = $request->getParam('txt_not');
    $not_train_no = $request->getParam('train_no');
    $check_val;

    global $method;

    $results = $method->select("SELECT * FROM `bookings` WHERE `train_no` like '$not_train_no' AND `status`='y'");

    // print_r($results);

    if ($results) {

        $response = json_encode($results);

        $results = json_decode($response);

        foreach ($results as $result) {

            $device_id = $result->device_id;


            $sql_statement = "INSERT INTO `call_center_agents`(`update_id`, `device_id`, `update_text`, `up_date`, `train_no`, `target`) VALUES (null,'$device_id','$not_text','$current_date',$not_train_no,'check-in')";

            $results = $method->query($sql_statement);

            if ($results) {

                $check_val = "inserted";
            }
        }

        if (!empty($check_val)) {
            return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/map-observer/success.php');
        }
    } else {
        $results = $method->select("SELECT * FROM `platform_delayed_users` WHERE `platform_delayed_train_no` like '$not_train_no'");

        if ($results) {

            $response = json_encode($results);

            $results = json_decode($response);

            foreach ($results as $result) {

                $device_id = $result->platform_delayed_device_id;

                $sql_statement = "INSERT INTO `call_center_agents`(`update_id`, `device_id`, `update_text`, `up_date`, `train_no`, `target`) VALUES (null,'$device_id','$not_text','$current_date',$not_train_no,'platform')";

                $results = $method->query($sql_statement);

                if ($results) {
                    $check_val = "inserted";
                }
            }
            if (!empty($check_val)) {
                return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/success.php');
            }
        }
    }
});

$app->map(['GET', 'POST'], '/engineerNot', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $not_text = $request->getParam('txt_not');
    $not_train_no = $request->getParam('train_no');

    $to = '/topics/' . $not_train_no;

    define('API_ACCESS_KEY', 'AAAA88VD3Wg:APA91bEHHjpJwSvuEzmm0TT8YYNCJy8j1-D64s8lYaDKiAUnsMbIhj2ZL1xyuJqlzRh6A_Y-T4U2emCngwtjYJvDpcc6yXIs7SKGOtXE4wkQtuLOeLjzhF_NWEmRlQ3gj0P2vtOPlnDv');

    $data = array('title' => 'SikephiApp', 'message' => $not_text);
    $fields = array(
        'to'  => $to,
        'data' => $data
    );

    $headers = array(
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Oops! FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    echo "notification sent sucessfully";


    global $method;

    $results = $method->select("SELECT * FROM `bookings` WHERE `train_no` like '%$not_train_no%' AND `status`='y'");


    $response = json_encode($results);

    $results = json_decode($response);

    foreach ($results as $result) {

        $device_id = $result->device_id;

        $sql_statement = "INSERT INTO `user_updates`(`update_id`, `device_id`, `update_text`, `up_date`) VALUES (null,'$device_id','$not_text','$current_date')";

        $results = $method->query($sql_statement);

        if ($results) {

            return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/success.php');
        }
    }
});

$app->map(['GET', 'POST'], '/plat_line_update', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $not_text = $request->getParam('txt_not');
    $not_train_no = $request->getParam('train_no');

    $to = '/topics/delayed';

    define('API_ACCESS_KEY', 'AAAA88VD3Wg:APA91bEHHjpJwSvuEzmm0TT8YYNCJy8j1-D64s8lYaDKiAUnsMbIhj2ZL1xyuJqlzRh6A_Y-T4U2emCngwtjYJvDpcc6yXIs7SKGOtXE4wkQtuLOeLjzhF_NWEmRlQ3gj0P2vtOPlnDv');

    $data = array('title' => 'SikephiApp', 'message' => $not_text);
    $fields = array(
        'to'  => $to,
        'data' => $data
    );

    $headers = array(
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Oops! FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    echo "notification sent sucessfully";



    return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/platform/success.php');
});

$app->get('/line_updates', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    return json_encode($method->select("SELECT * FROM `notifications`"));
});

$app->get('/engineer_updates', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    return json_encode($method->select("SELECT * FROM `not_table`"));
});

$app->map(['GET', 'POST'], '/newReport', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $report_text = $request->getParam('report_text');
    $train_no = $request->getParam('train_no');
    $district = $request->getParam('district');

    global $method;

    $sql_statement = "INSERT INTO `driver_updates`(`updates_id`, `update_text`, `update_date`, `train_no`,`district`) VALUES (null,'$report_text','$current_date','$train_no','$district')";

    $result = $method->query($sql_statement);
    $response = array();

    if ($result) {

        $code = "comp_success";
        array_push($response, array("code" => $code));
        return json_encode($response);
    } else {
        $code = "comp_failed";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/user_notification', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    $data = json_decode(file_get_contents('php://input'));

    $home_station = $data->home_station;
    $home_station = $request->getParam('home_station');

    global $method;

    //get district
    $sql_get_district = "SELECT distinct `line` FROM pppabxznag.locations_by_line where `title` =  '$home_station'";

    $sql_get_exec = $method->select($sql_get_district);

    if ($sql_get_exec) {
        $encode_msg = json_encode($sql_get_exec);
        $decode_msg = json_decode($encode_msg);

        $district = $decode_msg[0]->line;

        $news_array = explode(",",$district);

        $coma_del = "";

        for ($i = 0; $i <count($news_array); $i++) {
            if ($i == count($news_array) - 1) {
                $coma_del .= '"'.$news_array[$i].'"';
            } else {
                $coma_del .= '"'.$news_array[$i].'",';
            }
    
        }

        // echo "comma del ".$coma_del;

        return json_encode($method->select("SELECT * FROM `news_tables` WHERE `district` IN ($coma_del)"));
    }
});

//point to point booking

$app->map(['GET', 'POST'], '/book_train', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $device_id = $request->getParam('device_id');
    $train_no = $request->getParam('train_no');
    $train_reminder_timer = $request->getParam('train_reminder_time');
    $train_reminder_interval = $request->getParam('train_reminder_interval');
    $train_departure = $request->getParam('train_departure');
    $train_arrival_time = $request->getParam('train_arrival_time');
    $train_time = $request->getParam('train_time');
    $dest_arrive = $request->getParam('dest_arrive');
    $train_destination = $request->getParam('train_destination');
    $session_type = $request->getParam('session_type');

    $extracted_date = explode(" ", $train_reminder_timer)[0];

    //convert train departure time string to date
    $train_start_time = new DateTime(date('Y-m-d') . $train_time);

    //convert train arrival time string to time
    $con_train_arrival_time = new DateTime(date('Y-m-d') . $train_arrival_time);

    $now = new DateTime();

    //time remaining for departure of train
    $train_ETA = $train_start_time->diff($now);
    //   $train_ETA = $train_ETA.format("%H:%I:%S");

    //time remaining for arrival of train
    $train_eta_arrive = $con_train_arrival_time->diff($now);
    //   $train_eta_arrive = $train_eta_arrive->format("%H:%I:%S");



    //hours and minutes for departure
    $h = $train_ETA->h;
    $i = $train_ETA->i;
    $s = $train_ETA->s;

    //hours and minutes for arrival
    $h_a = $train_eta_arrive->h;
    $i_a = $train_eta_arrive->i;
    $s_a = $train_eta_arrive->s;


    //train departure
    if ($h < 10) {
        $h = "0" . $h;
    }
    if ($i < 10) {
        $i = "0" . $i;
    }
    if ($s < 10) {
        $s = "0" . $s;
    }

    //train arrival
    if ($h_a < 10) {
        $h_a = "0" . $h_a;
    }
    if ($i_a < 10) {
        $i_a = "0" . $i_a;
    }
    if ($s_a < 10) {
        $s_a = "0" . $s_a;
    }


    $train_eta_confirm = $h . ":" . $i . ":" . $s;
    //   echo 'departure : '.$train_eta_confirm.'<br>';

    $train_eta_arrive = $h_a . ":" . $i_a . ":" . $s_a;
    //   echo 'arrive : '.$train_eta_arrive;


    $sql_statement = "INSERT INTO `bookings`(`bookings_id`, `device_id`, `train_no`, `booking_date`,`status`, `session_type`) VALUES (null,'$device_id','$train_no','$current_date','y','$session_type')";

    global $method;

    $results = $method->query($sql_statement);

    $response = array();

    if ($results) {
        //   get district using train no 
        $sql_get_route = "SELECT DISTINCT `train_route` FROM pppabxznag.trains WHERE `train_no` = '$train_no'";

        $sql_get_exec = $method->select($sql_get_route);

        if ($sql_get_exec) {
            $encode_msg = json_encode($sql_get_exec);
            $decode_msg = json_decode($encode_msg);

            $route = $decode_msg[0]->train_route;

            if (!empty($route)) {

                //get district
                $sql_get_district = "SELECT distinct `line` FROM pppabxznag.locations_by_line where `title` = '$route'";

                $sql_get_exec = $method->select($sql_get_district);

                if ($sql_get_exec) {
                    $encode_msg = json_encode($sql_get_exec);
                    $decode_msg = json_decode($encode_msg);

                    $district = $decode_msg[0]->line;

                    if (!empty($district)) {
                        //get booking id
                        $booking_id = $method->getBookingId($device_id);
                        //succesful booking
                        $code = "Booking_Successful";
                        //fcm user registration token
                        $user_token = $method->getToken($device_id);
                        //current date
                        $curr_date = date('d-m-yy h:i:s');
                        //pushing to response
                        array_push($response, array("code" => $code, "train_time" => $train_time, "arrival_time" => $train_arrival_time, "district" => $district, "user_token" => $user_token, "booking_id" => $booking_id));

                        //insert the required notifications
                        $sql_insert = "INSERT INTO `user_notifications` (`notification_id`,`device_id`,`notification_message`,`notification_send_date`,`notification_status`,`notification_train_no`,`notification_date`, `not_type`)";

                        $sql_insert .=  "VALUES(null,'$device_id','Reminder your train will be arriving in the next $train_reminder_interval minutes at $train_departure','$train_reminder_timer','1','$train_no','$curr_date', 'reminder'),
                                       (null,'$device_id','Your train has arrived please check in','$extracted_date $train_arrival_time','1','$train_no','$curr_date','train_arrival'),
                                       (null,'$device_id','Your train is now leaving the platform','$extracted_date $train_time','1','$train_no','$curr_date','train_departure'),
                                       (null,'$device_id','Your train has arrived at your destination','$extracted_date $dest_arrive','1','$train_no','$curr_date','train_arrival_dest')";

                        $not_send_res = $method->query($sql_insert);

                        if ($not_send_res) {
                            // print_r($notification_res);
                            return json_encode($response);
                        } else {
                            echo "Couldn't send notification error :";
                        }
                    } else {
                        echo "ditrict is empty";
                    }
                } else {
                    echo "no district found";
                }
            } else {
                echo "route is empty";
            }
        } else {
            echo "no route found";
        }
    } else {
        $code = "Booking_Failed";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

//transfer booking
$app->map(['GET', 'POST'], '/book_train_transfer', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $device_id = $request->getParam('device_id');

    //first trip
    $train_no = $request->getParam('train_no');
    $train_reminder_timer = $request->getParam('train_reminder_time');
    $train_reminder_interval = $request->getParam('train_reminder_interval');
    $train_departure_station = $request->getParam('train_departure');
    $train_arrival_time = $request->getParam('train_arrival_time');
    $train_time = $request->getParam('train_departure_station');
    $train_station_dest_arrival = $request->getParam('train_station_dest_arrival');

    //second trip
    $train_no_2 =  $request->getParam('train_no_2');
    $sec_reminder_time =  $request->getParam('sec_reminder_time');
    $sec_reminder_interval = $request->getParam('sec_reminder_interval');
    $sec_departure =  $request->getParam('sec_departure');
    $dest_sec_train_arrival_time =  $request->getParam('sec_train_arrival_time');
    $sec_train_time =  $request->getParam('sec_train_time');
    $sec_train_station_dest_arrival =  $request->getParam('sec_train_station_dest_arrival');
    $final_destination =  $request->getParam('final_destination');


    $extracted_date = explode(" ", $train_reminder_timer)[0];

    //convert train departure time string to date
    $train_start_time = new DateTime(date('Y-m-d') . $train_time);

    //convert train arrival time string to time
    $con_train_arrival_time = new DateTime(date('Y-m-d') . $train_arrival_time);

    $now = new DateTime();

    //time remaining for departure of train
    $train_ETA = $train_start_time->diff($now);
    //   $train_ETA = $train_ETA.format("%H:%I:%S");

    //time remaining for arrival of train
    $train_eta_arrive = $con_train_arrival_time->diff($now);
    //   $train_eta_arrive = $train_eta_arrive->format("%H:%I:%S");



    //hours and minutes for departure
    $h = $train_ETA->h;
    $i = $train_ETA->i;
    $s = $train_ETA->s;

    //hours and minutes for arrival
    $h_a = $train_eta_arrive->h;
    $i_a = $train_eta_arrive->i;
    $s_a = $train_eta_arrive->s;


    //train departure
    if ($h < 10) {
        $h = "0" . $h;
    }
    if ($i < 10) {
        $i = "0" . $i;
    }
    if ($s < 10) {
        $s = "0" . $s;
    }

    //train arrival
    if ($h_a < 10) {
        $h_a = "0" . $h_a;
    }
    if ($i_a < 10) {
        $i_a = "0" . $i_a;
    }
    if ($s_a < 10) {
        $s_a = "0" . $s_a;
    }


    $train_eta_confirm = $h . ":" . $i . ":" . $s;
    //   echo 'departure : '.$train_eta_confirm.'<br>';

    $train_eta_arrive = $h_a . ":" . $i_a . ":" . $s_a;
    //   echo 'arrive : '.$train_eta_arrive;


    $sql_statement = "INSERT INTO `bookings`(`bookings_id`, `device_id`, `train_no`, `booking_date`,`status`) 
                            VALUES (null,'$device_id','$train_no','$current_date','y'),
                                   (null,'$device_id','$train_no_2','$current_date','y')";

    global $method;

    $results = $method->query($sql_statement);

    $response = array();

    if ($results) {
        //   get district using train no 
        $sql_get_route = "SELECT DISTINCT `train_route` FROM pppabxznag.trains WHERE `train_no` = '$train_no'";

        $sql_get_exec = $method->select($sql_get_route);

        if ($sql_get_exec) {
            $encode_msg = json_encode($sql_get_exec);
            $decode_msg = json_decode($encode_msg);

            $route = $decode_msg[0]->train_route;

            if (!empty($route)) {

                //get district
                $sql_get_district = "SELECT distinct `line` FROM pppabxznag.locations_by_line where `title` = '$route'";

                $sql_get_exec = $method->select($sql_get_district);

                if ($sql_get_exec) {
                    $encode_msg = json_encode($sql_get_exec);
                    $decode_msg = json_decode($encode_msg);

                    $district = $decode_msg[0]->line;

                    if (!empty($district)) {
                        $code = "Booking_Successful";
                        //fcm user registration token
                        $user_token = $method->getToken($device_id);
                        //current date
                        $curr_date = date('d-m-yy h:i:s');
                        //pushing to response
                        array_push($response, array("code" => $code, "train_time" => $train_time, "arrival_time" => $train_arrival_time, "district" => $district, "user_token" => $user_token));


                        //insert the required notifications
                        $sql_insert = "INSERT INTO `user_notifications` (`notification_id`,`device_id`,`notification_message`,`notification_send_date`,`notification_status`,`notification_train_no`,`notification_date`, `not_type`)";

                        $sql_insert .=  "VALUES(null,'$device_id','Reminder your train will be arriving in the next $train_reminder_interval minutes at $train_departure_station','$train_reminder_timer','1','$train_no','$curr_date', 'reminder'),
                                       (null,'$device_id','Your train has arrived please check in','$extracted_date $train_arrival_time','1','$train_no','$curr_date','train_arrival'),
                                       (null,'$device_id','Your train is now leaving the platform','$extracted_date $train_time','1','$train_no','$curr_date','train_departure'),
                                       (null,'$device_id','Your train has arrived at your transferring station','$extracted_date $train_station_dest_arrival','1','$train_no','$curr_date','train_arrival_dest'),
                                       (null,'$device_id','Reminder your next train will be arriving in the next $sec_reminder_interval minutes at $sec_departure','$sec_reminder_time','1','$train_no_2','$curr_date', 'reminder'),
                                       (null,'$device_id','Your train has arrived please check in','$extracted_date $dest_sec_train_arrival_time','1','$train_no_2','$curr_date','train_arrival'),
                                       (null,'$device_id','Your train is now leaving the platform','$extracted_date $sec_train_time','1','$train_no_2','$curr_date','train_departure'),
                                       (null,'$device_id','Your train has arrived at your destination','$extracted_date $sec_train_station_dest_arrival','1','$train_no_2','$curr_date','train_arrival_dest')";

                        $not_send_res = $method->query($sql_insert);

                        if ($not_send_res) {
                            // print_r($notification_res);
                            return json_encode($response);
                        } else {
                            echo "Couldn't send notification error :";
                        }
                    } else {
                        echo "ditrict is empty";
                    }
                } else {
                    echo "no district found";
                }
            } else {
                echo "route is empty";
            }
        } else {
            echo "no route found";
        }
    } else {
        $code = "Booking_Failed";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});



$app->map(['GET', 'POST'], '/send_to_engineer', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    $data = json_decode(file_get_contents('php://input'));

    $not_text = $data->txt_not;
    $train_no = $data->train_no;
    $district = $data->district;

    global $method;

    $sql_statement = "INSERT INTO `not_table`(`engineer_not_id`, `engineer_not_text`, `engineer_not_date`, `engineer_train_no`,`district`) VALUES (null,'$not_text','$current_date','$train_no','$district')";

    $results = $method->query($sql_statement);

    if ($results) {
        return json_encode(array("rows" => 1, "data" => "[]"));
    } else {
        return json_encode(array("rows" => 0, "data" => "[]"));
    }
});

$app->map(['GET', 'POST'], '/engineer_feedback', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    $feedbk_text = $request->getParam('feedbk_text');
    $train_no = $request->getParam('train_no');

    $sql_statement = "INSERT INTO `notifications`(`not_id`, `not_text`, `not_date`, `train_no`) VALUES (null,'$feedbk_text','$current_date','$train_no')";

    global $method;

    $results = $method->query($sql_statement);

    $response = array();

    if ($results) {
        $code = "Feedback_Success";
        array_push($response, array("code" => $code));
        return json_encode($response);
    } else {
        $code = "Feedback_Failed";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/user_updates', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $device = $request->getParam('device_id');

    global $method;

    return json_encode($method->select("SELECT * FROM `user_updates` WHERE `device_id` like '%$device%'"));
});

// 15/05/19

$app->map(['GET', 'POST'], '/check_complain', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $device = $request->getParam('device_id');

    global $method;
    $results = $method->select("SELECT `train_no` FROM `bookings` WHERE `device_id` like '$device' AND `status`='y' ORDER BY `booking_date` DESC");

    $response = array();

    if ($results) {
        return json_encode($results);
    } else {
        return json_encode($results);
    }
});

$app->map(['GET', 'POST'], '/delay_comp', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $device_id = $request->getParam('device_id');
    $complain_text = $request->getParam('complain');
    $train_no = $request->getParam('train_no');
    $district = $request->getParam('district');

    $sql_statement = "INSERT INTO `platform_complain`(`p_comp_id`, `p_comp_text`, `p_comp_train_no`, `p_comp_device_id`, `p_comp_date`,`districts`,`p_comp_img`) VALUES (null,'$complain_text','$train_no','$device_id','$current_date','$district','https://source.unsplash.com/random/200x200')";

    global $method;

    $results = $method->query($sql_statement);

    $response = array();

    if ($results) {
        //insert into train complains
        $sql_insert = "INSERT INTO `train_complains`(`train_comp_id`, `train_comp_no`, `comp_date`) VALUES (null,'$train_no','$current_date')";

        $my_results = $method->query($sql_insert);

        if ($my_results) {

            $code = "comp_success";

            array_push($response, array("code" => $code));
            return json_encode($response);
        }
    } else {
        $code = "comp_failed";
        array_push($response, array("code" => $code));

        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/route_comp', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    $device_id = $request->getParam('device_id');
    $complain_text = $request->getParam('complain');
    $train_no = $request->getParam('train_no');
    $district = $request->getParam('district');

    $sql_statement = "INSERT INTO `checked_in_complain`(`c_comp_id`, `c_comp_text`, `c_comp_train_no`, `c_comp_device_id`, `c_comp_date`, `c_comp_img`, `c_district`)
     VALUES (null,'$complain_text','$train_no','$device_id','$current_date','https://source.unsplash.com/random/200x200', '$district')";

    global $method;

    $results = $method->query($sql_statement);

    $response = array();

    if ($results) {
        //insert into train complains
        $sql_insert = "INSERT INTO `train_complains`(`train_comp_id`, `train_comp_no`, `comp_date`) VALUES (null,'$train_no','$current_date')";

        $my_results = $method->query($sql_insert);

        if ($my_results) {

            $code = "comp_success";

            array_push($response, array("code" => $code));
            return json_encode($response);
        }
    } else {
        $code = "comp_failed";
        array_push($response, array("code" => $code));

        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/train_comp', function (Request $request, Response $response) {
    global $method;
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    $device_id = $request->getParam('device_id');
    $complain_text = $request->getParam('complain');
    $train_no = $request->getParam('train_no');
    $district = $request->getParam('district');
    $session_type = $request->getParam('session_type');
    $com_img_url = $request->getParam('comImg');

    if (empty($com_img_url)) {
        $com_img_url = "http://sikephiapp.co.za/img/placeholder-image.png";
    }

    if ($session_type == "PLATFORM" || $session_type == "DELAYED") {

        $sql = "INSERT INTO `platform_complain`(`p_comp_id`, `p_comp_text`, `p_comp_train_no`, `p_comp_device_id`, `p_comp_date`,`districts`,`p_comp_img`) VALUES (null,'$complain_text','$train_no','$device_id','$current_date','$district','$com_img_url')";
    } else if ($session_type == "CHECKED_IN") {

        $sql = "INSERT INTO `checked_in_complain`(`c_comp_id`, `c_comp_text`, `c_comp_train_no`, `c_comp_device_id`, `c_comp_date`, `c_comp_img`, `c_district`) VALUES (null,'$complain_text','$train_no','$device_id','$current_date','$com_img_url', '$district')";
    }


    $results = $method->query($sql);

    $response = array();

    if ($results) {
        //insert into train complains
        $sql_insert = "INSERT INTO `train_complains`(`train_comp_id`, `train_comp_no`, `comp_date`) VALUES (null,'$train_no','$current_date')";

        $my_results = $method->query($sql_insert);

        if ($my_results) {

            $code = "comp_success";

            array_push($response, array("code" => $code));
            return json_encode($response);
        }
    } else {
        $code = "comp_failed";
        array_push($response, array("code" => $code));

        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/delay_complains', function (Request $request, Response $redponse) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    $sql_statement = "SELECT * FROM `platform_complain` ORDER by p_comp_id DESC";

    $results = $method->select($sql_statement);


    if ($results) {
        return json_encode($results);
    } else {
        echo 'cant fetch data from server';
    }
});

$app->map(['GET', 'POST'], '/admin_reply', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    session_start();
    $data = json_decode(file_get_contents('php://input'));
    $admin_name = $_SESSION["firstName"] . " " . $_SESSION["lastName"];
    $train_no = $data->train_no;
    $reply_txt = $data->txt_not;
    $user_id = $data->device_id;
    $comp_id = $data->comp_id;
    $district = $data->district;

    global $method;

    $sql_statement = "INSERT INTO `admin_replies`(`a_reply_id`, `a_reply_text`, `a_reply_date`, `a_reply_train_no`,`user_id`,`comp_id`,`status`,`district`) VALUES (null,'$reply_txt','$current_date','$train_no','$user_id','$comp_id','unseen','$district')";

    $results = $method->query($sql_statement);

    if ($results) {

        return json_encode(array("rows" => 1, "data" => "SENT SUCCEESFULLY"));
          // return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/platform/success.php');
    } else {
        return json_encode(array("rows" => 0, "data" => "SENT SUCCEESFULLY"));
    }
});

$app->map(['GET', 'POST'], '/admin_platform_queries', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    session_start();
    $data = json_decode(file_get_contents('php://input'));

    $admin_name = $_SESSION["firstName"] . " " . $_SESSION["lastName"];
    $train_no = $data->train_no;
    $reply_txt = $data->txt_not;
    $user_id = $data->device_id;
    $comp_id = $data->comp_id;
    $district = $data->district;

    // echo $train_no." ".$reply_txt." ".$user_id." ".$comp_id;

    global $method;

    $sql_statement = "INSERT INTO `admin_platform_queries`(`a_reply_id`, `a_reply_text`, `a_reply_date`, `a_reply_train_no`,`user_ids`,`comp_id`,`statuss`,`district`) VALUES (null,'$reply_txt','$current_date','$train_no','$user_id','$comp_id','unseen','$district')";

    $results = $method->query($sql_statement);

    if ($results) {
        return json_encode(array("rows" => 1, "data" => "MESSAGE FORWADED SUCCESSFULLY"));
    } else {
        return json_encode(array("rows" => 0, "data" => "MESSAGE FORWADING FAILED"));
    }
});

$app->map(['GET', 'POST'], '/get_platform_queries', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    global $method;
    $sql_get_stmt = "SELECT * FROM `admin_platform_queries` ORDER BY `a_reply_date` ASC";

    $res = $method->select($sql_get_stmt);

    if ($res) {
        return json_encode($res);
    }
});

$app->map(['GET', 'POST'], '/admin_platform_replies', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    $data = json_decode(file_get_contents('php://input'));
    session_start();
    $admin_name = $_SESSION["firstName"] . " " . $_SESSION["lastName"];
    $train_no = $data->train_no;
    $reply_txt = $data->txt_not;
    $user_id = $data->device_id;
    $comp_id = $data->comp_id;
    $district = $data->district;

    global $method;

    $sql_statement = "INSERT INTO `admin_platform_replies`(`a_reply_id`, `a_reply_text`, `a_reply_date`, `a_reply_train_no`,`user_id`,`comp_id`,`status`,`district`)
     VALUES (null,'$reply_txt','$current_date','$train_no','$user_id','$comp_id','unseen','$district')";

    $results = $method->query($sql_statement);

    if ($results) {
        return json_encode(array("rows" => count($results), "data" => $results));
    } else {
        return json_encode(array("rows" => 0, "data" => "[]"));
    }
});

$app->map(['GET', 'POST'], '/get_platform_replies', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    global $method;
    $sql_get_stmt = "SELECT * FROM `admin_platform_replies` ORDER BY `a_reply_date` ASC";

    $res = $method->select($sql_get_stmt);

    if ($res) {
        return json_encode($res);
    }
});

$app->map(['GET', 'POST'], '/admin_line_reply', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    $data = json_decode(file_get_contents('php://input'));

    session_start();

    $admin_name = $_SESSION["firstName"] . " " . $_SESSION["lastName"];
    $train_no = $data->train_no;
    $reply_txt = $data->txt_not;
    $user_id = $data->device_id;
    $comp_id = $data->comp_id;
    $district = $data->district;
    global $method;

    // echo $user_id." ".$reply_txt." ".$train_no." ".$comp_id;

    $sql_statement = "INSERT INTO `admin_line_replies`(`ad_id`, `ad_text`, `ad_name`, `ad_train_no`, `ad_timestamp`,`user_id`,`comp_id`, `status`,`district`) VALUES (null,'$reply_txt','$admin_name','$train_no','$current_date','$user_id','$comp_id','unseen','$district')";

    $results = $method->query($sql_statement);

    if ($results) {
        return json_encode(array("rows" => count($results), "data" => $results));
    } else {
        return json_encode(array("rows" => 0, "data" => "[]"));
    }
});

$app->map(['GET', 'POST'], '/check_in_query', function (Request $request, Response $redponse) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    $data = json_decode(file_get_contents('php://input'));


    session_start();

    $admin_name = $_SESSION["firstName"] . " " . $_SESSION["lastName"];
    $train_no = $data->train_no;
    $reply_txt = $data->txt_not;
    $user_id = $data->device_id;
    $comp_id = $data->comp_id;
    $district = $data->district;
    global $method;

    // echo $user_id." ".$reply_txt." ".$train_no." ".$comp_id;

    $sql_statement = "INSERT INTO `admin_check_in_queries`(`ad_chk_id`, `ad_chk_text`, `ad_chk_name`, `ad_chk_train_no`, `ad_chk_timestamp`, `user_chk_id`, `comp_chk_id`, `status_chk`,`district`) VALUES (null,'$reply_txt','$admin_name','$train_no','$current_date','$user_id','$comp_id','unseen','$district')";

    $results = $method->query($sql_statement);

    if ($results) {
        return json_encode(array("rows" => count($results), "data" => "DATA INSERTED SUCCESSFULLY"));
    } else {
        return json_encode(array("rows" => 0, "data" => "DATA INSERT FAILED"));
    }
});

$app->map(['GET', 'POST'], '/get_check_in_query', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    $sql_stmt = "SELECT * FROM `admin_check-in_queries` ORDER BY `ad_chk_timestamp` ASC";

    $results = $method->select($sql_stmt);

    if ($results) {
        return json_encode($results);
    }
});

$app->map(['GET', 'POST'], '/check_in_admin_replies', function (Request $request, Response $redponse) {

    session_start();
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $admin_name = $_SESSION["firstName"] . " " . $_SESSION["lastName"];
    $train_no = $request->getParam('train_no');
    $reply_txt = $request->getParam('txt_not');
    $user_id = $request->getParam('device_id');
    $comp_id = $request->getParam('comp_id');
    global $method;

    echo $user_id . " " . $reply_txt . " " . $train_no . " " . $comp_id;

    $sql_statement = "INSERT INTO `admin_check-in_replies`(`ad_chk_id`, `ad_chk_text`, `ad_chk_name`, `ad_chk_train_no`, `ad_chk_timestamp`, `user_chk_id`, `comp_chk_id`, `status_chk`) VALUES (null,'$reply_txt','$admin_name','$train_no','$current_date','$user_id','$comp_id','unseen')";

    $results = $method->query($sql_statement);

    if ($results) {
        return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/check-in/success.php');
    } else {
    }
});

$app->map(['GET', 'POST'], '/get_check_in_replies', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    global $method;

    $sql_stmt = "SELECT * FROM `admin_check-in_replies` ORDER BY `ad_chk_timestamp`";

    $results = $method->select($sql_stmt);

    if ($results) {
        return json_encode($results);
    }
});

$app->map(['GET', 'POST'], '/platform_comp', function (Request $request, Response $redponse) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;
    $androidDeviceId = $request->getParam('device_id');

    $sql_statement = "SELECT * FROM `platform_complain` WHERE `p_comp_device_id` like '$androidDeviceId' ORDER by p_comp_id DESC";

    $results = $method->select($sql_statement);


    if ($results) {
        return json_encode($results);
    } else {
        echo 'cant fetch data from server';
    }
});

$app->map(['GET', 'POST'], '/line_comp', function (Request $request, Response $redponse) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;
    $androidDeviceId = $request->getParam('device_id');

    $sql_statement = "SELECT * FROM `checked_in_complain` WHERE `c_comp_device_id` LIKE '$androidDeviceId' ORDER BY `c_comp_id` DESC";

    $results = $method->select($sql_statement);


    if ($results) {
        return json_encode($results);
    } else {
        echo 'cant fetch data from server';
    }
});


$app->map(['GET', 'POST'], '/admin_line_replies', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    global $method;
    $train_no = $request->getParam('train_no');
    $user_id = $request->getParam('device_id');
    $comp_id = $request->getParam('comp_id');

    $sql_statement = "SELECT * FROM `admin_line_replies` WHERE `ad_train_no` like '$train_no' AND `user_id`='$user_id' AND `comp_id`='$comp_id' AND `status`='unseen' ORDER by `ad_id` ASC";

    $results = $method->select($sql_statement);


    if ($results) {
        return json_encode($results);
    } else {
        echo 'cant fetch data from my server';
    }
});

$app->map(['GET', 'POST'], '/admin_replies', function (Request $request, Response $redponse) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;
    $train_no = $request->getParam('train_no');
    $user_id = $request->getParam('device_id');
    $comp_id = $request->getParam('comp_id');

    $sql_statement = "SELECT * FROM `admin_replies` WHERE `a_reply_train_no`='$train_no' AND `user_id`='$user_id' AND `comp_id`='$comp_id' AND `status`='unseen' ORDER by a_reply_id ASC";

    $results = $method->select($sql_statement);


    if ($results) {
        return json_encode($results);
    } else {
        echo 'cant fetch data from server';
    }
});

$app->map(['GET', 'POST'], '/driver_allocation', function (Request $request, Response $redponse) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    $device_id = $request->getParam('device_id');
    $dr_train_no = $request->getParam('train_no');

    global $method;

    $sql_statement = "INSERT INTO `driver_train_allocation`(`dr_allocation_id`, `dr_allocation_device_id`, `dr_allocation_train_no`, `dr_allocation_date`) VALUES (null,'$device_id','$dr_train_no','$current_date')";

    $results = $method->query($sql_statement);

    if ($results) {
    } else {
    }
});

$app->map(['GET', 'POST'], '/check_driver', function (Request $request, Response $redponse) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    global $method;
    $dr_device_id = $request->getParam('dr_device_id');

    $sql_statement = "SELECT * FROM `driver_train_allocation` WHERE `dr_allocation_device_id` = '$dr_device_id'";

    $results = $method->select($sql_statement);

    $response = array();

    if ($results) {
        $code = "driver_exists";

        array_push($response, array("code" => $code));
        return json_encode($response);
    } else {
        $code = "driver_doesnt_exists";
        array_push($response, array("code" => $code));

        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/check_in_driver', function (Request $request, Response $redponse) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    global $method;

    $dr_device_id = $request->getParam('dr_device_id');
    $train_no = $request->getParam('train_no');
    $distr = $request->getParam('distr');

    $check_if_train_booked = "SELECT * FROM `driver_train_allocation` WHERE `dr_allocation_train_no`='$train_no'";

    $result_check = $method->select($check_if_train_booked);

    $response = array();


    if ($result_check) {
        $code = "train_booked";
        array_push($response, array("code" => $code));
        return json_encode($response);
    } else {

        $sql_statement = "INSERT INTO `driver_train_allocation`(`dr_allocation_id`, `dr_allocation_device_id`, `dr_allocation_train_no`, `dr_allocation_date`,`status`,`district`)
         VALUES (null,'$dr_device_id','$train_no','$current_date','active','$distr')";

        $results = $method->query($sql_statement);


        if ($results) {
            //make train available for commuters to book
            $sql_stmt = "UPDATE `trains` SET `statuss`='available' WHERE `train_no`='$train_no'";

            $sql_execute = $method->query($sql_stmt);

            if ($sql_execute) {
                $code = "allocation_success";
                array_push($response, array("code" => $code));
                return json_encode($response);
            } else {

                $code = "allocation_failed";
                array_push($response, array("code" => $code));

                return json_encode($response);
            }
        } else {
            $code = "allocation_failed";
            array_push($response, array("code" => $code));

            return json_encode($response);
        }
    }
});

$app->map(['GET', 'POST'], '/train_status', function (Request $request, Response $redponse) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;
    $train_no = $request->getParam('train_no');

    $sql_statement = "SELECT `status` FROM `driver_train_allocation` WHERE `dr_allocation_train_no`='$train_no'";

    $results = $method->select($sql_statement);

    $response = array();

    if ($results) {

        return json_encode($results);
    } else {
        $code = "status_fecth_failed";
        array_push($response, array("code" => $code));

        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/update_train_status', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;
    $train_no = $request->getParam('train_no');
    $update_info = $request->getParam('update_info');
    $startTime = $request->getParam('start_time');
    $stopTime = $request->getParam('stop_time');

    $sql_statement = "UPDATE `driver_train_allocation` SET `status`='$update_info' WHERE `dr_allocation_train_no`='$train_no'";

    $results = $method->query($sql_statement);

    $resp = array();

    if ($results) {
        if ($update_info == "delayed") {

            //make train unavailable for commuters to book
            $sql_stmt = "UPDATE `trains` SET `statuss`='unavailable' WHERE `train_no`='$train_no'";

            $sql_execute = $method->query($sql_stmt);

            if ($sql_execute) {

                $update_resp = "info_updated";
                array_push($resp, array("code" => $update_resp, "update_text" => $update_info));
                return json_encode($resp);
            }
        } else if ($update_info == "active") {

            //make train available for commuters to book
            $sql_stmt = "UPDATE `trains` SET `statuss`='available' WHERE `train_no`='$train_no'";

            $sql_execute = $method->query($sql_stmt);

            if ($sql_execute) {

                if (!empty($startTime) && !empty($stopTime)) {

                    $startTime = new DateTime(date('Y-m-d') . $startTime);
                    $stopTime = new DateTime(date('Y-m-d') . $stopTime);


                    $time_delayed = $startTime->diff($stopTime);

                    // echo 'not empty';

                    //hours and minutes for departure
                    $h = $time_delayed->h;
                    $i = $time_delayed->i;
                    $s = $time_delayed->s;

                    if ($h < 10) {
                        $h = "0" . $h;
                    }
                    if ($i < 10) {
                        $i = "0" . $i;
                    }
                    if ($s < 10) {
                        $s = "0" . $s;
                    }

                    //full delayed time
                    $full_delayed_time = $h . ":" . $i . ":" . $s;

                    //   echo $full_delayed_time;

                    if (!empty($full_delayed_time)) {

                        $sql_stmt = "SELECT `train_time`, `arrival_time` FROM `trains` WHERE `train_no`='$train_no'";

                        $select_results = $method->select($sql_stmt);
                        if ($select_results) {

                            // echo count($select_results);

                            $results_encoded = json_encode($select_results);
                            $results_decoded = json_decode($results_encoded);

                            // echo count($results_decoded);


                            foreach ($results_decoded as $results_deco) {
                                // echo 'hello';
                                $depart_time = $results_deco->train_time;
                                $arrive_time = $results_deco->arrival_time;

                                // echo $depart_time;
                                $new_depart;
                                $new_arrive;

                                if ($depart_time != "00:00:00") {

                                    $secs_1 = strtotime($depart_time) - strtotime("00:00:00");
                                    $new_depart = date("H:i:s", strtotime($full_delayed_time) + $secs_1);

                                    // echo 'new depart time :'.$new_depart;

                                }
                                if ($arrive_time != "00:00:00") {

                                    $secs_2 = strtotime($arrive_time) - strtotime("00:00:00");
                                    $new_arrive = date("H:i:s", strtotime($full_delayed_time) + $secs_2);
                                    // echo 'new arrive time :'.$new_depart;

                                }

                                if (!empty($new_depart) && !empty($new_arrive)) {

                                    $count = 0;
                                    //update time in database
                                    $update_sql = "UPDATE `trains` SET `train_time`='$new_depart',`arrival_time`='$new_arrive' WHERE `train_no`='$train_no' AND `train_time`='$depart_time' AND `arrival_time`='$arrive_time'";

                                    $sql_execute = $method->query($update_sql);

                                    if ($sql_execute) {
                                        $update_resp = "info_updated";
                                        array_push($resp, array("code" => $update_resp, "update_text" => $update_info, "delayed_time" => $full_delayed_time, "new_depart_time" => $new_depart, "new_Arrive_time" => $new_arrive));
                                    }
                                }
                            }

                            return json_encode($resp);
                        }

                        $update_resp = "info_updated";
                        array_push($resp, array("code" => $update_resp, "update_text" => $update_info, "delayed_time" => $full_delayed_time));

                        return json_encode($resp);
                    }
                } else {
                    $update_resp = "info_updated";
                    array_push($resp, array("code" => $update_resp, "update_text" => $update_info));
                    return json_encode($resp);
                }
            }
        }
    } else {
        $update_resp = "info_update_failed";
        array_push($resp, array("code" => $update_resp));
        return json_encode($resp);
    }
});

$app->map(['GET', 'POST'], '/select_delay_comp', function (Request $request, Response $redponse) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    $sql_statement = "SELECT `p_comp_train_no` FROM `platform_complain`";

    $results = $method->select($sql_statement);

    $response = array();

    if ($results) {
        return json_encode($results);
    } else {
    }
});

$app->map(['GET', 'POST'], '/query_driver', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    session_start();

    global $method;

    $dr_query = $request->getParam('dr_query');
    $train_no = $request->getParam('train_no');

    // echo $dr_query.' '.$train_no;

    $sql_statement = "INSERT INTO `driver_query`(`dr_query_id`, `dr_query_text`, `dr_query_train_no`, `dr_quey_date`,`status`) VALUES (null,'$dr_query','$train_no','$current_date','unseen')";

    $results = $method->query($sql_statement);

    $response = array();

    if ($results) {
        return $this->response->withStatus(200)->withHeader('Location', 'http://178.128.45.152/tokiso/updatedpanel/tokiso/map-observer/success.php');
    } else {
    }
});

$app->map(['GET', 'POST'], '/is_driver_present', function (Request $request, Response $redponse) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;
    $device_id = $request->getParam('device_id');

    $sql_statement = "SELECT * FROM `driver_train_allocation` WHERE `dr_allocation_device_id` = '$device_id'";

    $results = $method->select($sql_statement);

    $response = array();

    if ($results) {
        return json_encode($results);
    } else {
        $code = "driver_doesnt_exists";
        array_push($response, array("code" => $code));

        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/admin_queries', function (Request $request, Response $redponse) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;
    $train_no = $request->getParam('train_no');

    $sql_statement = "SELECT * FROM `driver_query` WHERE `dr_query_train_no`='$train_no' AND `status`='unseen'";

    $results = $method->select($sql_statement);

    $response = array();

    if ($results) {
        return json_encode($results);
    } else {
        $code = "query_doesnt_exists";
        array_push($response, array("code" => $code));

        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/dr_location', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $lat = $request->getParam('Latitude');
    $longi = $request->getParam('Longitude');
    $device_id = $request->getParam('device_id');

    global $method;

    $sql_check = "SELECT * FROM `driver_location_test` WHERE `device_id`='$device_id'";

    $result = $method->select($sql_check);

    if ($result) {

        $sql_stmt = "UPDATE `driver_location_test` SET `Latitude`='$lat', `Longitutde`='$longi' WHERE `device_id`='$device_id'";

        $up_results = $method->query($sql_stmt);

        if ($up_results) {

            $response = array();

            $code = "data_updated";

            array_push($response, array("code" => $code));

            return json_encode($response);
        } else {

            $response = array();

            $code = "data_update_failed";

            array_push($response, array("code" => $code));
            return json_encode($response);
        }
    } else {

        $sql_insert = "INSERT INTO `driver_location_test`(`id`, `Latitude`, `Longitutde`,`device_id`, `book_date`) VALUES (null,'$lat','$longi','$device_id','$current_date')";

        $insert_results = $method->query($sql_insert);

        if ($insert_results) {

            $response = array();

            $code = "data_inserted";

            array_push($response, array("code" => $code));

            return json_encode($response);
        } else {

            $response = array();

            $code = "data_insert_failed";

            array_push($response, array("code" => $code));

            return json_encode($response);
        }
    }
});

$app->map(['GET', 'POST'], '/read_dr_location', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    $sql_stmt = "SELECT * FROM `driver_location_test`";

    $results = $method->select($sql_stmt);

    if ($results) {
        return json_encode($results);
    } else {
        $response = array();
        $code = "data_failed";
        array_push($response, array("code" => $code));
    }
});

$app->map(['GET', 'POST'], '/select_train', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    $train_no = $request->getParam('train_no');

    $sql_stmt = "SELECT `dr_allocation_device_id` FROM `driver_train_allocation` WHERE `dr_allocation_train_no` ='$train_no'";

    $results = $method->select($sql_stmt);

    if ($results) {

        return json_encode($results);
    } else {
        $response = array();
        $code = "data_failed";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/train_location', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    $device_id = $request->getParam('device_id');

    $sql_stmt = "SELECT `Latitude`, `Longitutde` FROM `driver_location_test` WHERE `device_id` ='$device_id'";

    $results = $method->select($sql_stmt);

    if ($results) {

        return json_encode($results);
    } else {
        $response = array();
        $code = "data_failed";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

// checking if you are tranfering platforms or not

$app->map(['GET', 'POST'], '/trans_platform', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $depart = $request->getParam('st_depart');
    $destination = $request->getParam('st_destination');
    $check = $request->getParam('st_check');


    $comp_depart;
    $comp_dest;

    global $method;

    if (!empty($depart) && !empty($destination)) {

        $get_line_depart = "SELECT `line` FROM `locations_by_line` WHERE `title`='$depart'";


        $get_line_results = $method->select($get_line_depart);

        if ($get_line_results) {
            $results_encoded = json_encode($get_line_results);
            $results_decoded = json_decode($results_encoded);

            foreach ($results_decoded as $result_decoded) {
                $comp_depart = $result_decoded->line;
                //  echo 'depart line : '.$comp_depart.'<br />';
                if (!empty($comp_depart)) {
                    break;
                }
            }

            if (!empty($comp_depart)) {

                //check if line for depart is the same with line for dest

                $get_line_dest = "SELECT `line` FROM `locations_by_line` WHERE `title`='$destination'";

                $get_dest_result = $method->select($get_line_dest);
                if ($get_dest_result) {

                    $results_encoded = json_encode($get_dest_result);
                    $results_decoded = json_decode($results_encoded);

                    foreach ($results_decoded as $result_decoded) {
                        $comp_dest = $result_decoded->line;
                        //  echo 'dest line : '.$comp_dest.'';

                        if ($comp_depart == $comp_dest) {
                            break;
                        }
                    }
                }
            }
        }
    }

    if (!empty($comp_depart) && !empty($comp_dest)) {

        $found = false;
        $transferring_areas = array();


        $departArr = explode(",", $comp_depart);
        $arriveArr = explode(",", $comp_dest);

        // print_r($departArr);
        // echo '<br>';
        // print_r($arriveArr);


        for ($i = 0; $i < count($departArr); $i++) {
            for ($k = 0; $k < count($arriveArr); $k++) {
                if ($departArr[$i] == $arriveArr[$k]) {
                    $found = true;
                    break;
                }
            }
        }


        if ($found) {
            if (!empty($check)) {
                $check = "point to point";
                $response = array();
                array_push($response, array("check" => $check));
                return json_encode($response);
            }
        } else {
            if (!empty($comp_depart) && !empty($comp_dest)) {
                // echo $comp_dest;
                // echo $comp_depart;
                $transferring_areas = array();



                for ($i = 0; $i < count($departArr); $i++) {
                    for ($k = 0; $k < count($arriveArr); $k++) {

                        $sql_stmt = "SELECT `trains_title` FROM `transfr_platforms` WHERE `trans_line` LIKE '%$departArr[$i]%' AND `trans_line` LIKE '%$arriveArr[$k]%'";
                        $trans_platfomr_sta = $method->select($sql_stmt);
                        if ($trans_platfomr_sta) {
                            $result_encode = json_encode($trans_platfomr_sta);
                            $result_decoded = json_decode($result_encode);

                            // print_r($result_decoded);
                            // echo count($result_decoded).'<br/>';
                            for ($j = 0; $j < count($result_decoded); $j++) {
                                # code...
                                // echo $result_decoded[$j]->trains_title;
                                // $transferring_areas[] = $result_decoded[$j]->trains_title;
                                array_push($transferring_areas, array("trains_title" => $result_decoded[$j]->trains_title));
                                // print_r($result_decoded)
                            }


                            // array_push($transferring,array($result_decode));

                        }
                    }
                }



                if (count($transferring_areas) > 0) {
                    //    echo count($transferring_areas);
                    // echo 'here2';
                    if (!empty($check)) {
                        $check = "transferring platforms";
                        $response = array();
                        array_push($response, array("check" => $check));
                        return json_encode($response);
                    } else {
                        // echo "not checking";
                        return json_encode($transferring_areas);
                    }
                }
            }
        }
    }
});

$app->map(['GET', 'POST'], '/route_complains', function (Request $request, Response $redponse) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    $sql_statement = "SELECT * FROM `checked_in_complain` ORDER by c_comp_id DESC";

    $results = $method->select($sql_statement);


    if ($results) {
        return json_encode($results);
    } else {
        echo 'cant fetch data from server';
    }
});

$app->map(['GET', 'POST'], '/delete_driver', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $device_id = $request->getParam('device_id');

    global $method;

    $sql_stmt = "DELETE FROM `driver_train_allocation` WHERE `dr_allocation_device_id`='$device_id'";

    $result = $method->query($sql_stmt);

    $response = array();

    if ($result) {

        $sql_stmt_location = "DELETE FROM `driver_location_test` WHERE `device_id`='$device_id'";

        $result = $method->query($sql_stmt_location);

        if ($result) {
            $code = "delete_success";
            echo $code;
            array_push($response, array("code" => $code));
            return json_encode($response);
        }
    } else {
        $code = "delete_failed";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/delete_admin_reply', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $id = $request->getParam('id');

    global $method;
    $response = array();

    $delete_stmt = "DELETE FROM `admin_replies` WHERE `a_reply_id`='$id'";


    $delete = $method->query($delete_stmt);

    if ($delete) {
        $code = "deleted_admin_plat_reply";

        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/delete_admin_line_reply', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $id = $request->getParam('id');

    global $method;
    $response = array();

    $delete_stmt = "DELETE FROM `admin_line_replies` WHERE `ad_id`='$id'";

    $delete = $method->query($delete_stmt);

    if ($delete) {
        $code = "deleted_admin_line_reply";

        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/delete_admin_gen_reply', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $id = $request->getParam('id');

    global $method;
    $response = array();

    $delete_stmt = "DELETE FROM `general_rpl` WHERE `g_id`='$id'";

    $delete = $method->query($delete_stmt);

    if ($delete) {
        $code = "deleted_admin_gen_reply";

        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/commuter_registration', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $name = $request->getParam('name');
    $email = $request->getParam('email');
    $contact = $request->getParam('contact');
    $home_station = $request->getParam('home_station');
    $train_no = "";
    $region = $request->getParam('region');
    $device_id = $request->getParam('device_id');

    global $method;


    //check if device exists

    $sql_check_stmt = "SELECT * FROM `sikephi_users` WHERE `device_id`='$device_id'";


    $result_check = $method->select($sql_check_stmt);

    $response = array();


    if ($result_check) {

        echo "Here";


        $update_stmt = "UPDATE `sikephi_users` SET `first_name`='$name',`email_address`='$email',`contact_no`='$contact',`home_station`='$home_station',`train_no`='$train_no',`region`='$region',`device_id`='$device_id' WHERE `device_id`='$device_id'";

        $update_result = $method->query($update_stmt);

        if ($update_result) {


            //get district
            $sql_get_district = "SELECT distinct `line` FROM pppabxznag.locations_by_line where `title` =  '$home_station'";

            $sql_get_exec = $method->select($sql_get_district);

            if ($sql_get_exec) {
                $encode_msg = json_encode($sql_get_exec);
                $decode_msg = json_decode($encode_msg);

                $district = $decode_msg[0]->line;

                $code = "registration_success";
                array_push($response, array("code" => $code, "district" => $district));
                return json_encode($response);
            }
        } else {

            $code = "update_failed";
            array_push($response, array("code" => $code));
            return json_encode($response);
        }
    } else {

        echo "user doenst exist";

        $sql_insert_stmt = "INSERT INTO `sikephi_users`(`user_id`, `first_name`, `email_address`, `contact_no`, `home_station`,`train_no`, `region`, `device_id`) VALUES (null,'$name','$email','$contact','$home_station','$train_no','$region','$device_id')";

        $results = $method->query($sql_insert_stmt);

        if ($results) {

            //get district
            $sql_get_district = "SELECT distinct `line` FROM pppabxznag.locations_by_line where `title` =  '$home_station'";

            $sql_get_exec = $method->select($sql_get_district);

            if ($sql_get_exec) {
                $encode_msg = json_encode($sql_get_exec);
                $decode_msg = json_decode($encode_msg);

                $district = $decode_msg[0]->line;
                $code = "registration_success";
                array_push($response, array("code" => $code, "district" => $district));
                return json_encode($response);
            }
        } else {

            $code = "registration_failed";
            array_push($response, array("code" => $code));

            return json_encode($response);
        }
    }
});

$app->map(['GET', 'POST'], '/get_commuter_info', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $device_id = $request->getParam('device_id');

    global $method;

    $sql_get_stmt = "SELECT * FROM `sikephi_users` WHERE `device_id`='$device_id'";

    $results = $method->select($sql_get_stmt);
    $response = array();

    if ($results) {

        // $code = "fetch_success";
        // array_push($response,array("code"=>$code));
        return json_encode($results);
    } else {

        $code = "fetch_failed";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/rate_us', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $device_id = $request->getParam('device_id');
    $rating = $request->getParam('rating');


    global $method;

    $sql_stmt = "INSERT INTO `rating`(`rating_id`, `rating`, `rating_device`, `rating_date`) VALUES (null,'$device_id','$rating','$current_date')";

    $result = $method->query($sql_stmt);
    $response = array();

    if ($result) {
        $code = "rating_success";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

// testing firebase notifications

$app->map(['GET', 'POST'], '/fcm_test', function (Request $request, Response $response) {
    global $method;
    date_default_timezone_set('Africa/Johannesburg');

    $current_date = '' . date("Y-m-d H:i:s");

    $token = $request->getParam('token');
    $device_id = $request->getParam('device_id');

    //    Check if user has an existing token
    $token_check_sql = "SELECT * FROM `fcm_token` WHERE `device_id`='$device_id'";

    $check_exec = $method->select($token);

    if ($check_exec) {
        $stmt = "UPDATE `fcm_token` SET `token`='$token', `token_date`='$current_date' WHERE `device_id`='$device_id'";
    } else {
        $stmt = "INSERT INTO `fcm_token`(`id`, `token`,`device_id`, `token_date`) VALUES (null,'$token','$device_id', '$current_date')";
    }

    $results = $method->query($stmt);
    $response = array();

    if ($results) {
        $code = "token_registered";
        array_push($response, array("code" => $code));
        return json_encode($response);
    } else {
        echo "Error";
    }
});

//getting default replies
$app->map(['GET', 'POST'], '/get_default_reply', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $device_id = $request->getParam('device_id');
    $message = $request->getParam('message');

    global $method;

    $my_token;

    $stmt = "SELECT * FROM `fcm_token` WHERE `device_id`='$device_id'";

    $results = $method->select($stmt);

    if ($results) {
        $decoded_json = json_encode($results);

        $json_encode = json_decode($decoded_json);

        foreach ($json_encode as $token) {
            $my_token = $token->token;
            // echo $my_token;
        }

        define('API_ACCESS_KEY', 'AAAA88VD3Wg:APA91bEHHjpJwSvuEzmm0TT8YYNCJy8j1-D64s8lYaDKiAUnsMbIhj2ZL1xyuJqlzRh6A_Y-T4U2emCngwtjYJvDpcc6yXIs7SKGOtXE4wkQtuLOeLjzhF_NWEmRlQ3gj0P2vtOPlnDv');

        $data = array('title' => 'SikephiApp', 'message' => $message, "notType" => "default");

        $fields = array(
            'to'  => $my_token,
            'data' => $data
        );

        $headers = array(
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Oops! FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        echo "notification sent sucessfully";
    }
});

//getting default replies
$app->map(['GET', 'POST'], '/arrival_time_not', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $device_id = $request->getParam('device_id');

    global $method;

    $my_token;


    $stmt = "SELECT * FROM `fcm_token` WHERE `device_id`='$device_id'";

    $results = $method->select($stmt);

    $decoded_json = json_encode($results);

    $json_encode = json_decode($decoded_json);

    foreach ($json_encode as $token) {
        $my_token = $token->token;
    }

    define('API_ACCESS_KEY', 'AAAA88VD3Wg:APA91bEHHjpJwSvuEzmm0TT8YYNCJy8j1-D64s8lYaDKiAUnsMbIhj2ZL1xyuJqlzRh6A_Y-T4U2emCngwtjYJvDpcc6yXIs7SKGOtXE4wkQtuLOeLjzhF_NWEmRlQ3gj0P2vtOPlnDv');

    $data = array('title' => 'SikephiApp', 'message' => 'Your train has arrived');

    $fields = array(
        'to'  => $my_token,
        'data' => $data
    );

    $headers = array(
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Oops! FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    echo "notification sent sucessfully";
});

$app->map(['GET', 'POST'], '/sendnotification', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $method = $request->getParam('method');
    $advertID = $request->getParam('advertID');
    define('API_ACCESS_KEY', 'AAAA88VD3Wg:APA91bEHHjpJwSvuEzmm0TT8YYNCJy8j1-D64s8lYaDKiAUnsMbIhj2ZL1xyuJqlzRh6A_Y-T4U2emCngwtjYJvDpcc6yXIs7SKGOtXE4wkQtuLOeLjzhF_NWEmRlQ3gj0P2vtOPlnDv');

    $data = array('title' => 'SikephiApp', 'message' => 'FCM test from php');
    $train_no = '9188';
    $to = '/topics/' . $train_no;

    $fields = array(
        'to'  => $to,
        'data' => $data
    );

    $headers = array(
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Oops! FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    echo "notification sent sucessfully";
});

$app->map(['GET', 'POST'], '/delete_not', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $id = $request->getParam('not_id');

    global $method;

    $sql_stmt = "DELETE FROM `news_tables` WHERE `news_updates_id`='$id'";

    $result = $method->query($sql_stmt);
    $response = array();

    if ($result) {
        $code = "delete_success";
        array_push($response, array("code" => $code));
        return json_encode($response);
    } else {
        $code = "delete_failed";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

// delete user from booking if making train delayed complain

$app->map(['GET', 'POST'], '/delete_bookings', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $android_deivce_id = $request->getParam('device_id');

    $stmt_delete = "DELETE FROM `bookings` WHERE `device_id` ='$android_deivce_id'";

    global $method;

    $results = $method->query($stmt_delete);

    $response = array();

    if ($results) {
        $code = "delete_success";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

//general admin replies
$app->map(['GET', 'POST'], '/general_replies', function (Request $request, Response $response) {
    global $method;
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    $data = json_decode(file_get_contents('php://input'));
    $device_id = $data->device_id;
    $reply_text = $data->txt_reply;
    $district = $data->district;

    $stmt_insert = "INSERT INTO `general_rpl`(`g_id`, `g_text`, `device_id`, `g_date`,`status`, `district`) VALUES (null,'$reply_text','$device_id','$current_date','unseen','$district')";

    $results = $method->query($stmt_insert);

    if ($results) {
        return json_encode(array("rows" => 1, "data" => "message sent successfully"));
    } else {
        return json_encode(array("rows" => 0, "data" => "message not sent successfully"));
    }
});

//forward to map-observer
$app->map(['GET', 'POST'], '/map_gen_comp_queries', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $device_id = $request->getParam('device_id');
    $reply_text = $request->getParam('txt_reply');

    global $method;

    $stmt_insert = "INSERT INTO `gen_comp_queries`(`g_id`, `g_text`, `g_device_id`, `g_date`, `status`) VALUES (null,'$reply_text','$device_id','$current_date','unseen')";

    $results = $method->query($stmt_insert);

    if ($results) {
        return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/general/success.php');
    }
});

//get all the general responder queries
//forward to map-observer
$app->map(['GET', 'POST'], '/get_gen_queries', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    $stmt_insert = "SELECT * FROM `gen_comp_queries`";

    $results = $method->select($stmt_insert);

    if ($results) {
        return json_encode($results);
    }
});

//reply to general query --- mab-observer

$app->map(['GET', 'POST'], '/reply_to_gen_query', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $device_id = $request->getParam('device_id');
    $message = $request->getParam('message');

    global $method;

    $sql_stmt = "INSERT INTO `map_observer_gen_query`(`m_g_id`, `m_g_text`, `m_g_device_id`, `m_g_date`, `m_g_status`,`districts`) VALUES (null,'$message','$device_id','$current_date','unseen',$dd)";

    $results = $method->query($sql_stmt);

    if ($results) {

        return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/map-observer/success.php');
    }
});

$app->map(['GET', 'POST'], '/get_map_gen_replies', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;
    $sql_stmt = "SELECT * FROM `map_observer-gen_query`";
    $results = $method->select($sql_stmt);

    if ($results) {
        return json_encode($results);
    }
});

//get general replies
$app->map(['GET', 'POST'], '/get_general_replies', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $device_id = $request->getParam('device_id');
    global $method;

    $stmt_get = "SELECT * FROM general_rpl WHERE `device_id`='$device_id'";
    $results = $method->select($stmt_get);

    if ($results) {
        return json_encode($results);
    } else {
        echo 'no records';
    }
});

//check if train is available
$app->map(['GET', 'POST'], '/check_train_availabaility', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $train_no = $request->getParam('train_no');
    global $method;

    $sql_stmt = "SELECT `statuss` FROM `trains` WHERE `train_no`='$train_no'";

    $myResults = $method->select($sql_stmt);
    $response = array();

    if ($myResults) {
        return json_encode($myResults);
    }
});

$app->map(['GET', 'POST'], '/set_to_seen_query', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $reply_id = $request->getParam('dr_query_id');
    global $method;

    $sql_stmt = "UPDATE `driver_query` SET `status`='seen' WHERE `dr_query_id`='$reply_id'";

    $results = $method->query($sql_stmt);
    $response = array();

    if ($results) {
        $code = "updated_successfully";
        array_push($response, array("code" => $code));
        return json_encode($response);
    } else {
        $code = "update_failed";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/set_to_seen_en_q', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $reply_id = $request->getParam('query_id');
    global $method;

    $sql_stmt = "UPDATE `contact-engineer` SET `status`='seen' WHERE `c_e_id`='$reply_id'";

    $results = $method->query($sql_stmt);
    $response = array();

    if ($results) {
        $code = "updated_successfully";
        array_push($response, array("code" => $code));
        return json_encode($response);
    } else {
        $code = "update_failed";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/set_to_seen', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $reply_id = $request->getParam('reply_id');
    global $method;

    $sql_stmt = "UPDATE `admin_replies` SET `status`='seen' WHERE `a_reply_id`='$reply_id'";

    $results = $method->query($sql_stmt);
    $response = array();

    if ($results) {
        $code = "updated_successfully";
        array_push($response, array("code" => $code));
        return json_encode($response);
    } else {
        $code = "update_failed";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/set_to_seen_line', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $reply_id = $request->getParam('reply_id');
    global $method;

    $sql_stmt = "UPDATE `admin_line_replies` SET `status`='seen' WHERE `ad_id`='$reply_id'";

    $results = $method->query($sql_stmt);
    $response = array();

    if ($results) {
        $code = "updated_successfully";
        array_push($response, array("code" => $code));
        return json_encode($response);
    } else {
        $code = "update_failed";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

// my time update test
$app->map(['POST', 'GET'], '/testin_time_diff', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    $test_time = $request->getParam('time');
    $test_time_2 = $request->getParam('time2');


    $sql_stmt = "SELECT * FROM `myTestTable`";

    $results = $method->select($sql_stmt);

    if ($results) {
        $json_encode = json_encode($results);
        $json_decoded = json_decode($json_encode);
        foreach ($json_decoded as $json_deco) {

            $db_time = $json_deco->test_time;
            $db_time_2 = $json_deco->test_time2;

            $secs_1 = strtotime($db_time) - strtotime("00:00:00");
            $result = date("H:i:s", strtotime($test_time) + $secs_1);

            $secs_2 = strtotime($db_time_2) - strtotime("00:00:00");
            $result_2 = date("H:i:s", strtotime($test_time) + $secs_2);

            $sql_update_stmt = "UPDATE `myTestTable` SET `test_time`='$result',`test_time2`='$result_2' WHERE `test_time`='$db_time' AND `test_time2`='$db_time_2'";

            $exec_query = $method->query($sql_update_stmt);

            if ($exec_query) {
                echo 'arrival' . ' ' . $result . '<br>';
                echo 'depart' . ' ' . $result_2 . '<br>';
            }
        }
    }
});

$app->map(['GET', 'POST'], '/updated_train_time', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");


    $train_no = $request->getParam('train_no');
    $st_depart = $request->getParam('st_depart');

    global $method;

    $sql_stmt = "SELECT * FROM `trains` WHERE `train_no`='$train_no' AND `train_route`='$st_depart'";

    $my_sql_execute = $method->select($sql_stmt);
    $response = array();

    if ($my_sql_execute) {
        //encode response
        $depart_time = $my_sql_execute[0]['train_time'];
        $arrival_time = $my_sql_execute[0]['arrival_time'];

        //convert to date
        $con_depart = new DateTime(date('Y-m-d') . $depart_time);
        $con_arrival = new DateTime(date('Y-m-d') . $arrival_time);

        //current time
        $now = new DateTime();

        //difference
        $dif_depart = $con_depart->diff($now);
        $dif_arrive = $con_arrival->diff($now);

        //get hours,minutes,seconds
        //depart
        $d_h = $dif_depart->h;
        $d_i = $dif_depart->i;
        $d_s = $dif_depart->s;
        //arrive
        $a_h = $dif_arrive->h;
        $a_i = $dif_arrive->i;
        $a_s = $dif_arrive->s;

        // add zero
        // depart
        if ($d_h < 10) {
            $d_h = "0" . $d_h;
        }
        if ($d_i < 10) {
            $d_i = "0" . $d_i;
        }
        if ($d_s < 10) {
            $d_s = "0" . $d_s;
        }

        // arrive
        if ($a_h < 10) {
            $a_h = "0" . $a_h;
        }
        if ($a_i < 10) {
            $a_i = "0" . $a_i;
        }
        if ($a_s < 10) {
            $a_s = "0" . $a_s;
        }

        $depart_conuter = $d_h . ":" . $d_i . ":" . $d_s;
        $arrive_conuter = $a_h . ":" . $a_i . ":" . $a_s;

        array_push($response, array("depart_counter" => $depart_conuter, "arrive_counter" => $arrive_conuter));
        return json_encode($response);

        // echo 'depart timer: '.$depart_conuter.'<br>'.'arrive timer: '.$arrive_conuter;

    }
});

//getting time of arrival for the booked train at destination

$app->map(['GET', 'POST'], '/time_diff_arrival', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $train_no = $request->getParam('train_no');
    $dest = $request->getParam('st_dest');

    if (!empty($train_no) && !empty($dest)) {

        global $method;

        $stmt_prepare = "SELECT `arrival_time` FROM `trains` WHERE `train_no`='$train_no' AND `train_route`='$dest'";

        $stmt_execute = $method->select($stmt_prepare);
        $response = array();

        if ($stmt_execute) {

            $arrival_time = $stmt_execute[0]['arrival_time'];

            // echo $arrival_time;

            //convert to date
            $arrival_time = new DateTime(date('Y-m-d') . $arrival_time);

            //current time
            $now = new DateTime();

            //get difference
            $dif_arrival = $arrival_time->diff($now);

            //arrival hours, minute, and seconds
            $a_h = $dif_arrival->h;
            $a_i = $dif_arrival->i;
            $a_s = $dif_arrival->s;

            // arrive
            if ($a_h < 10) {
                $a_h = "0" . $a_h;
            }
            if ($a_i < 10) {
                $a_i = "0" . $a_i;
            }
            if ($a_s < 10) {
                $a_s = "0" . $a_s;
            }

            $arrive_conuter = $a_h . ":" . $a_i . ":" . $a_s;

            if (!empty($arrive_conuter)) {

                array_push($response, array("diff_time" => $arrive_conuter));
                return json_encode($response);
            }
        }
    }
});

$app->map(['GET', 'POST'], '/select_trains_with_comp', function (Request $request, Response $redponse) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    $sql_statement = "SELECT DISTINCT `train_no` FROM `trains` ";

    $results = $method->select($sql_statement);

    $response = array();

    if ($results) {
        return json_encode($results);
    } else {
    }
});

$app->map(['GET', 'POST'], '/call_center_updates_pl', function (Request $request, Response $redponse) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    $sql_statement = "SELECT * FROM `call_center_agents` WHERE `target`='platform'";

    $results = $method->select($sql_statement);

    $response = array();

    if ($results) {
        return json_encode($results);
    } else {
    }
});

$app->map(['GET', 'POST'], '/call_center_updates', function (Request $request, Response $redponse) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    $sql_statement = "SELECT * FROM `call_center_agents` WHERE `target`='check-in'";

    $results = $method->select($sql_statement);

    $response = array();

    if ($results) {
        return json_encode($results);
    } else {
    }
});

$app->map(['GET', 'POST'], '/send_to_all_call_center_agents', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    $dr_query = $request->getParam('dr_query');

    $sql_statement = "INSERT INTO `all_call_center_agents`(`id`, `message`, `msg_date`) VALUES (null,'$dr_query','$current_date')";

    $results = $method->query($sql_statement);

    $response = array();

    if ($results) {
        return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/map-observer/success.php');
    } else {
    }
});

$app->map(['GET', 'POST'], '/get_all_call_center_agents', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    $sql_statement = "SELECT * FROM `all_call_center_agents`";

    $results = $method->select($sql_statement);

    $response = array();

    if ($results) {
        return json_encode($results);
    } else {
    }
});

//specify route
$app->map(['GET', 'POST'], '/user_dest', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    global $method;

    $departure = $request->getParam('departure');
    $device_id = $request->getParam('device_id');

    $sql_select_region = "SELECT DISTINCT `region` FROM sikephi_users WHERE `device_id`='$device_id'";

    $reg_results = $method->select($sql_select_region);

    if ($reg_results) {
        $encoded_region = json_encode($reg_results);
        $decoded_region = json_decode($encoded_region);

        $region = $decoded_region[0]->region;

        if (!empty($region)) {

            $sql_stmt = "SELECT DISTINCT `train_route` FROM `trains` WHERE `train_route`<>'$departure' AND `region` like '%$region%' ORDER BY `train_route` ASC";

            $sql_results = $method->select($sql_stmt);

            if ($sql_results) {
                return json_encode($sql_results);
            } else {
                $response = array();
                $code = "unavailable";
                array_push($response, array("train_route" => $code));
                return json_encode($response);
            }
        } else {
        }
    } else {
        $response = array();
        $code = "unavailable";
        array_push($response, array("train_route" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/train_time', function (Request $request, Response $response) {

    global $method;
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $departure = $request->getParam('departure');

    $now = new DateTime();
    $result = $now->format('H:i:s');

    $st_time_plus_six_hrs = date('H:i:s', strtotime($result) + 3600);


    // AND `train_time`>='$result' AND `train_time`<='$st_time_plus_six_hrs'

    $sql_stmt = "SELECT DISTINCT `train_time` FROM `trains` WHERE `train_route`='$departure' AND `statuss`='available' AND `train_time` >= '$result' ORDER by train_time ASC";

    $sql_results = $method->select($sql_stmt);

    if ($sql_results) {
        return json_encode($sql_results);
    } else {
        $response = array();
        $code = "unavailable";
        array_push($response, array("train_time" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/user_op_train_no', function (Request $request, Response $response) {

    $departure = $request->getParam('departure');
    $destination = $request->getParam('destination');
    $train_time = $request->getParam('train_time');

    global $method;

    $st_time_plus_six_hrs = date('H:i:s', strtotime($train_time) + 108000);


    $sql_stmt = "SELECT DISTINCT `train_no` FROM `trains` WHERE `train_route` IN('$departure','$destination') AND `train_time` >= '$train_time' AND `train_time` <= '$st_time_plus_six_hrs' ORDER by train_time ASC";

    $sql_results = $method->select($sql_stmt);

    if ($sql_results) {
        return json_encode($sql_results);
    } else {
        $response = array();
        $code = "unavailable";
        array_push($response, array("train_time" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/contact_engineer', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    $eng_query = $request->getParam('eng_query');

    $sql_statement = "INSERT INTO `contact-engineer`(`c_e_id`, `c_e_message`, `c_e_date`,`status`) VALUES (null,'$eng_query','$current_date','unseen');";

    $results = $method->query($sql_statement);

    $response = array();

    if ($results) {
        return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/map-observer/success.php');
    } else {
    }
});

$app->map(['GET', 'POST'], '/admin_engineer_queries', function (Request $request, Response $redponse) {

    global $method;
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    $sql_statement = "SELECT * FROM `contact-engineer` WHERE `status`='unseen'";

    $results = $method->select($sql_statement);

    $response = array();

    if ($results) {
        return json_encode($results);
    } else {
        $code = "query_doesnt_exists";
        array_push($response, array("code" => $code));

        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/engineer_replies', function (Request $request, Response $response) {

    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    global $method;

    $eng_query = $request->getParam('report_text');

    $sql_statement = "INSERT INTO `engineer-reply`(`r_e_id`, `r_e_message`, `r_e_date`) VALUES (null,'$eng_query','$current_date')";

    $results = $method->query($sql_statement);

    $response = array();

    if ($results) {
        $code = "success";
        array_push($response, array("code" => $code));
        return json_encode($response);
    } else {

        $code = "fail";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/get_engineer_replies', function (Request $request, Response $response) {

    global $method;

    $sql_statement = "SELECT * FROM `engineer-reply` ORDER BY `r_e_date` ASC";

    $results = $method->select($sql_statement);
    $response = array();

    if ($results) {
        return json_encode($results);
    } else {

        $code = "fail";
        array_push($response, array("code" => $code));
        return json_encode($results);
    }
});

$app->map(['GET', 'POST'], '/cancel_train', function (Request $request, Response $response) {

    global $method;
    $train_no = $request->getParam('train_no');

    $sql_statement = "UPDATE `driver_train_allocation` SET `status`='cancelled' WHERE `dr_allocation_train_no`='$train_no'";

    $results = $method->query($sql_statement);

    $resp = array();

    if ($results) {

        $sql_stmt = "UPDATE `trains` SET `status`='cancelled' WHERE `train_no`='$train_no'";

        $sql_execute = $method->query($sql_stmt);

        if ($sql_execute) {

            $update_resp = "success";
            array_push($resp, array("code" => $update_resp));
            return json_encode($resp);
        }
    }
});

$app->map(['GET', 'POST'], '/get_all_cancelled_trains', function (Request $request, Response $response) {
    global $method;
    $sql_stmt = "SELECT * FROM `driver_train_allocation` WHERE `status`='cancelled'";
    $results = $method->select($sql_stmt);
    $response = array();
    if ($results) {

        $encode_results = json_encode($results);
        $decode_results = json_decode($encode_results);

        foreach ($decode_results as $decode_result) {

            $train_no = $decode_result->dr_allocation_train_no;
            $id = $decode_result->dr_allocation_id;

            if (!empty($train_no)) {

                $sql_stmt_2 = "SELECT DISTINCT `device_id` FROM `bookings` WHERE `train_no`='$train_no' AND `status`='y'";

                $bookings_results = $method->select($sql_stmt_2);

                if ($bookings_results) {

                    $encode_bookings_results = json_encode($bookings_results);
                    $decode_bookings_results = json_decode($encode_bookings_results);

                    $length = count($decode_bookings_results);
                    if ($length != 0) {
                        array_push($response, array("id" => $id, "train_no" => $train_no, "number_of_users" => $length));
                    }
                } else {
                    array_push($response, array("id" => $id, "train_no" => $train_no, "number_of_users" => "0"));
                }
            }
        }


        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/get_all_platform_cancelled_trains', function (Request $request, Response $response) {
    global $method;
    $sql_stmt = "SELECT * FROM `driver_train_allocation` WHERE `status`='cancelled'";
    $results = $method->select($sql_stmt);
    $response = array();
    if ($results) {

        $encode_results = json_encode($results);
        $decode_results = json_decode($encode_results);

        foreach ($decode_results as $decode_result) {

            $train_no = $decode_result->dr_allocation_train_no;
            $id = $decode_result->dr_allocation_id;

            if (!empty($train_no)) {

                $sql_stmt_2 = "SELECT `platform_delayed_id` FROM `platform_delayed_users` WHERE `platform_delayed_train_no`='$train_no'";

                $bookings_results = $method->select($sql_stmt_2);

                if ($bookings_results) {

                    $encode_bookings_results = json_encode($bookings_results);
                    $decode_bookings_results = json_decode($encode_bookings_results);

                    $length = count($decode_bookings_results);
                    if ($length != 0) {
                        array_push($response, array("id" => $id, "train_no" => $train_no, "number_of_users" => $length));
                    }
                } else {
                    array_push($response, array("id" => $id, "train_no" => $train_no, "number_of_users" => "0"));
                }
            }
        }


        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/transfr_check_in_users', function (Request $request, Response $response) {

    global $method;
    $train_no = $request->getParam('train_no');
    $msg = $request->getParam('msg');
    $sql_stmt = "SELECT * FROM `bookings` WHERE `train_no`='$train_no'";
    $sql_execute = $method->select($sql_stmt);
    $text;


    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");

    if ($sql_execute) {
        $encoded_results = json_encode($sql_execute);
        $decoded_results = json_decode($encoded_results);

        foreach ($decoded_results as $decoded_result) {
            $device_id = $decoded_result->device_id;
            if (!empty($device_id)) {
                $sql_stmt_2 = "INSERT INTO `transfer_users`(`msg_id`, `msg`, `user_id`, `trans_date`,`train_no`) VALUES (null,'$msg','$device_id','$current_date','$train_no')";
                $sql_execute_2 = $method->query($sql_stmt_2);
                if ($sql_execute_2) {
                    $text = "inserted";
                }
            }
        }

        if (!empty($text)) {
            return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/check-in/success.php');
        }
    }
});

$app->map(['GET', 'POST'], '/transfr_platform_users', function (Request $request, Response $response) {
    global $method;
    $train_no = $request->getParam('train_no');
    $msg = $request->getParam('msg');
    //    echo $train_no." ".$msg;
    $sql_stmt = "SELECT * FROM `platform_delayed_users` WHERE `platform_delayed_train_no`='$train_no'";
    $sql_execute = $method->select($sql_stmt);
    $text;


    if ($sql_execute) {
        $encoded_results = json_encode($sql_execute);
        $decoded_results = json_decode($encoded_results);

        foreach ($decoded_results as $decoded_result) {
            $device_id = $decoded_result->platform_delayed_device_id;
            if (!empty($device_id)) {
                $sql_stmt_2 = "INSERT INTO `transfer_users`(`msg_id`, `msg`, `user_id`, `trans_date`,`train_no`) VALUES (null,'$msg','$device_id','$current_date','$train_no')";
                $sql_execute_2 = $method->query($sql_stmt_2);
                if ($sql_execute_2) {
                    $text = "inserted";
                }
            }
        }

        if (!empty($text)) {
            return $this->response->withStatus(200)->withHeader('Location', 'http://sikephiapp.co.za/tokiso/updatedpanel/tokiso/platform/success.php');
        }
    }
});

$app->map(['GET', 'POST'], '/insert_delay_user', function (Request $request, Response $response) {

    $device_id = $request->getParam('device_id');
    $train_no = $request->getParam('train_no');

    global $method;

    $sql_insert_stmt = "INSERT INTO `platform_delayed_users`(`platform_delayed_id`, `platform_delayed_device_id`, `platform_delayed_train_no`, `platform_delayed_date`) VALUES (null,'$device_id','$train_no','$current_date')";

    $sql_execute = $method->query($sql_insert_stmt);
    $response = array();

    if ($sql_execute) {
        $code = "success";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/check_if_train_cancel', function (Request $request, Response $response) {

    $android_id = $request->getParam('android_device_id');

    global $method;

    $sql_select = "SELECT * FROM `transfer_users` WHERE `user_id`='$android_id'";

    $sql_execute = $method->select($sql_select);

    if ($sql_execute) {
        return json_encode($sql_execute);
    }
});

//platform test
$app->map(['GET', 'POST'], '/platform', function (Request $request, Response $response) {


    global $method;

    $sql_line = "SELECT platform_no FROM `trains`";

    $sql_execute = $method->select($sql_line);

    if ($sql_execute) {
        return json_encode($sql_execute);
    }
});

$app->map(['GET', 'POST'], '/map_trains', function (Request $request, Response $response) {

    global $method;

    $sql_dr_device_id = "SELECT dr_allocation_device_id,dr_allocation_train_no FROM `driver_train_allocation` WHERE `status`='active' GROUP BY dr_allocation_device_id,dr_allocation_train_no";

    $sql_execute = $method->select($sql_dr_device_id);
    $loc_array = array();


    if ($sql_execute) {

        $encode_result = json_encode($sql_execute);
        $decode_result = json_decode($encode_result);

        foreach ($decode_result as $value) {
            $device_id = $value->dr_allocation_device_id;
            $train_no = $value->dr_allocation_train_no;
            $sql_loc_execute = $method->select("SELECT * FROM `driver_location_test` WHERE `device_id`='$device_id'");
            if ($sql_loc_execute) {
                $encode_loc_result = json_encode($sql_loc_execute);
                $decode_loc_results = json_decode($encode_loc_result);

                foreach ($decode_loc_results as $location) {
                    $device_id = $location->device_id;
                    $train_no = $train_no;
                    $Latitude = $location->Latitude;
                    $Longitutde = $location->Longitutde;
                    array_push($loc_array, array("device_id" => $device_id, "train_no" => $train_no, "Latitude" => $Latitude, "Longitutde" => $Longitutde));
                }
            }
        }

        return json_encode($loc_array);

        print_r($loc_array);
    }
});

$app->map(['GET', 'POST'], '/track_technician', function (Request $request, Response $response) {

    global $method;
    $device_id = $request->getParam('deveice_id');
    $mLat = $request->getParam('latitude');
    $mLong = $request->getParam('longitude');
    $responce = array();


    $sql_dr_device_id = "SELECT * FROM `track_technician` WHERE `tech_device_id`='$device_id'";

    $sql_execute = $method->select($sql_dr_device_id);

    if ($sql_execute) {
        $sql_stmt = "UPDATE `track_technician` SET `tech_lat`='$mLat',`tech_long`='$mLong' WHERE `tech_device_id`='$device_id'";
        $execute = $method->query($sql_stmt);
        if ($execute) {
            $code = "tracking_updated";
            array_push($responce, array("code" => $code));
            return json_encode($responce);
        } else {
            $code = "tracking_update_failed";
            array_push($responce, array("code" => $code));
            return json_encode($responce);
        }
    } else {
        $sql_insert = "INSERT INTO `track_technician`(`tech_id`,`tech_device_id`,`tech_lat`,`tech_long`,`status`) VALUES(null,'$device_id','$mLat','$mLong','active')";

        $stmt_exec = $method->query($sql_insert);

        if ($stmt_exec) {
            $code = "tracking_started";
            array_push($responce, array("code" => $code));
            return json_encode($responce);
        } else {
            $code = "tracking_insert_failed";
            array_push($responce, array("code" => $code));
            return json_encode($responce);
        }
    }
});

$app->map(['GET', 'POST'], '/get_technician_location', function (Request $request, Response $response) {

    global $method;
    $responce = array();


    $sql_dr_device_id = "SELECT * FROM `track_technician` WHERE `status`='active'";

    $sql_execute = $method->select($sql_dr_device_id);

    if ($sql_execute) {
        return json_encode($sql_execute);
    } else {
        $code = "tracking_failed";
        array_push($responce, array("code" => $code));
        return json_encode($responce);
    }
});

$app->map(['GET', 'POST'], '/all_trains', function (Request $request, Response $response) {

    global $method;
    $responce = array();


    $sql_dr_device_id = "SELECT * FROM `trains`";

    $sql_execute = $method->select($sql_dr_device_id);

    if ($sql_execute) {
        return json_encode($sql_execute);
    } else {
        $code = "tracking_failed";
        array_push($responce, array("code" => $code));
        return json_encode($responce);
    }
});

/**
 * Check the status of the technician
 */

$app->map(['GET', 'POST'], '/check_technician_status', function (Request $request, Response $response) {

    global $method;
    $android_device_id = $request->getParam("android_id");
    $responce = array();


    $sql_dr_device_id = "SELECT `status` FROM `engineers_reg` WHERE `device_id`='$android_device_id'";

    $sql_execute = $method->select($sql_dr_device_id);

    if ($sql_execute) {
        return json_encode($sql_execute);
    } else {
        $code = "error_select";
        array_push($responce, array("code" => $code));
        return json_encode($responce);
    }
});

/**
 * End technician checking
 */


/**
 * check if Technician user exists using device_id for password reset
 */

$app->map(['GET', 'POST'], '/check_password_reset', function (Request $request, Response $response) {

    global $method;
    $email = $request->getParam("check_email");
    $responce = array();


    $sql_dr_device_id = "SELECT * FROM `engineers_reg` WHERE `email` like '$email'";

    $sql_execute = $method->select($sql_dr_device_id);

    $responce = array();

    if ($sql_execute) {
        $code = "found";
        array_push($responce, array("code" => $code));
        return json_encode($responce);
    } else {
        $code = "not_found";
        array_push($responce, array("code" => $code));
        return json_encode($responce);
    }
});
/**
 * check if Sikephi agent user exists using device_id for password reset
 */

$app->map(['GET', 'POST'], '/check_password_reset_agent', function (Request $request, Response $response) {

    global $method;
    $email = $request->getParam("check_email");
    $responce = array();


    $sql_dr_device_id = "SELECT * FROM `users_table` WHERE `email` like '$email'";

    $sql_execute = $method->select($sql_dr_device_id);

    if ($sql_execute) {
        $code = "found";
        array_push($responce, array("code" => $code));
        return json_encode($responce);
    } else {
        $code = "not_found";
        array_push($responce, array("code" => $code));
        return json_encode($responce);
    }
});

$app->map(['GET', 'POST'], '/update_pwd_technician', function (Request $request, Response $response) {

    global $method;
    $email = $request->getParam("where_email");
    $pass = $request->getParam("new_pass");
    $responce = array();

    $sql_dr_device_id = "UPDATE `engineers_reg` SET `password`='$pass' WHERE `email`='$email'";

    $sql_execute = $method->query($sql_dr_device_id);

    if ($sql_execute) {
        $code = "update_success";
        array_push($responce, array("code" => $code));
        return json_encode($responce);
    } else {
        $code = "update_error";
        array_push($responce, array("code" => $code));
        return json_encode($responce);
    }
});

$app->map(['GET', 'POST'], '/update_pwd_sikephi_agents', function (Request $request, Response $response) {

    global $method;
    $email = $request->getParam("where_email");
    $pass = $request->getParam("new_pass");
    $responce = array();

    $sql_dr_device_id = "UPDATE `users_table` SET `password`='$pass' WHERE `email`='$email'";

    $sql_execute = $method->query($sql_dr_device_id);

    if ($sql_execute) {
        $code = "update_success";
        array_push($responce, array("code" => $code));
        return json_encode($responce);
    } else {
        $code = "update_error";
        array_push($responce, array("code" => $code));
        return json_encode($responce);
    }
});


$app->map(['GET', 'POST'], '/engineer_chat', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    global $method;
    $data = json_decode(file_get_contents('php://input'));
    $user_id = $data->user_id;
    $eng_message = $data->eng_message;
    $device_id = $data->device_id;
    $district = $data->district;

    $sql_select = $method->select("SELECT `device_id` FROM pppabxznag.engineers_reg WHERE `user_id`='$user_id' GROUP BY device_id");

    if ($sql_select) {
        $arr_enc = json_encode($sql_select);
        $arr_dec = json_decode($arr_enc);
        $device_id = "";
        foreach ($arr_dec as $value) {
            $device_id = $value->device_id;
        }

        $topic = "/topics/" . str_replace(" ", "", $district);

        $sql_msg_insert = "INSERT INTO pppabxznag.engineer_messages(`msg_id`,`message`,`msg_type`,`msg_inteded_for`,`msg_date`,`msg_status`,`user_device_id`,`district`)VALUE(null,'$eng_message','in','Yard','$current_date','unseen','$device_id','$district')";

        $sql_inser = $method->query($sql_msg_insert);

        if ($sql_inser) {
            $sql_select_token = $method->select("SELECT * FROM pppabxznag.eng_tokens WHERE `eng_device_id`='$device_id'");
            if ($sql_select_token) {
                $res_enc = json_encode($sql_select_token);
                $res_dec = json_decode($res_enc);

                foreach ($res_dec as $tk_value) {
                    $token = $tk_value->eng_token;
                }

                define('API_ACCESS_KEY', 'AAAA3oWYidM:APA91bFR5O7MSPDdtfSuHaRoeThcHyreeygzhtpF7pdUqiR30-7LguvhWhmjZZBmbfYhwan-B6I0SWGcxW03ZjHp4znPJjE92QGN3zS-5f41LVRdlGJBYdSSj8nk-75KJcpbx2KcLJEw');
                // API access key from Google API's Console
                $data = array('title' => 'SikephiApp', 'message' => $eng_message);

                $fields = array(
                    'to'  => $token,
                    'data' => $data
                );


                $headers = array(
                    'Authorization: key=' . API_ACCESS_KEY,
                    'Content-Type: application/json'
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('Oops! FCM Send Error: ' . curl_error($ch));
                } else {

                    curl_close($ch);
                    return json_encode(array("responce" => "notification sent sucessfully", "district" => $district));
                }
            } else {
                echo "can't select";
            }
        } else {
            echo "can't insert to engineer messages";
        }
    } else {
        echo "can't fetch device id_ " . $user_id;
    }
});

/**
 * end
 */


$app->map(['GET', 'POST'], '/eng_token_reg', function (Request $request, Response $response) {
    global $method;
    $eng_token = $request->getParam("eng_token");
    $device_id = $request->getParam("device_id");
    $response = array();
    /*
        check if user exists
    */

    $sql_select = "SELECT * FROM pppabxznag.eng_tokens WHERE `eng_device_id`='$device_id'";

    if ($method->select($sql_select)) {
        /*
        if user exist update token
        */
        $sql_update = "UPDATE pppabxznag.eng_tokens SET `eng_token`='$eng_token' WHERE `eng_device_id`='$device_id'";

        if ($method->query($sql_update)) {
            $code = "token_updated_successfully";
            array_push($response, array("code" => $code));
            return json_encode($response);
        } else {
            $code = "token_updated_failed";
            array_push($response, array("code" => $code));
            return json_encode($response);
        }
    } else {
        /*
        insert token if user does exits
        */
        $sql_insert = "INSERT INTO pppabxznag.eng_tokens(`id_tokens`,`eng_token`,`eng_device_id`) VALUES(null,'$eng_token','$device_id')";

        if ($method->query($sql_insert)) {
            $code = "token_inserted_successfully";
            array_push($response, array("code" => $code));
            return json_encode($response);
        } else {
            $code = "token_inserted_failed";
            array_push($response, array("code" => $code));
            return json_encode($response);
        }
    }
});

$app->map(['GET', 'POST'], '/trains_for_technician', function (Request $request, Response $response) {
    global $method;
    $device_id = $request->getParam("device_id");

    /*
        get region using device_id
    */

    $sql_select = "SELECT DISTINCT `region` FROM pppabxznag.engineers_reg WHERE `device_id`='$device_id'";
    $sql_execute = $method->select($sql_select);

    if ($sql_execute) {
        $arr_enc = json_encode($sql_execute);
        $arr_dec = json_decode($arr_enc);
        $region = "";

        foreach ($arr_dec as $value) {
            $region = $value->region;
        }
        // $region;

        $sql_track = "SELECT dlt.Latitude, dlt.Longitutde, dtn.dr_allocation_train_no, dtn.status
                        FROM pppabxznag.driver_train_allocation dtn, pppabxznag.driver_location_test dlt, pppabxznag.trains trn
                        WHERE dtn.dr_allocation_device_id = dlt.device_id
                        AND trn.train_no = dtn.dr_allocation_train_no
                        AND trn.region = '$region'
                        GROUP BY dlt.Latitude, dlt.Longitutde, dtn.dr_allocation_train_no, dtn.status";

        $sql_execute = $method->select($sql_track);

        if ($sql_execute) {
            echo json_encode($sql_execute);
        }
    }
});

$app->map(['GET', 'POST'], '/other_trains_same_ergion', function (Request $request, Response $response) {
    global $method;
    $device_id = $request->getParam("device_id");

    /*
        get region using device_id
    */

    $sql_select = "SELECT DISTINCT `region` FROM pppabxznag.users_table WHERE `device_id`='$device_id'";
    $sql_execute = $method->select($sql_select);

    if ($sql_execute) {
        $arr_enc = json_encode($sql_execute);
        $arr_dec = json_decode($arr_enc);
        $region = "";

        foreach ($arr_dec as $value) {
            $region = $value->region;
        }
        // echo $region;

        $sql_track = "SELECT dlt.Latitude, dlt.Longitutde, dtn.dr_allocation_train_no, dtn.status
                        FROM pppabxznag.driver_train_allocation dtn, pppabxznag.driver_location_test dlt, pppabxznag.trains trn
                        WHERE dtn.dr_allocation_device_id = dlt.device_id
                        AND trn.train_no = dtn.dr_allocation_train_no
                        AND dtn.dr_allocation_device_id <> '$device_id'
                        AND trn.region = '$region'
                        GROUP BY dlt.Latitude, dlt.Longitutde, dtn.dr_allocation_train_no, dtn.status";

        $sql_execute = $method->select($sql_track);

        if ($sql_execute) {
            echo json_encode($sql_execute);
        }
    }
});

$app->map(['GET', 'POST'], '/get_yard_eng', function (Request $request, Response $responce) {
    global $method;
    $data = json_decode(file_get_contents('php://input'));
    $work_area = $data->work_area;
    $stmt_select = $method->select("SELECT * FROM pppabxznag.engineers_reg WHERE work_area='$work_area'");
    $data = array();

    if ($stmt_select) {
        $count = count(json_decode(json_encode($stmt_select)));
        $data["data"] = $stmt_select;
        $data["rows"] = $count;
        return json_encode($data);
    } else {
        return json_encode(array("data" => "No Data", "rows" => "0"));
    }
});

$app->map(['GET', 'POST'], '/get_eng_messages', function (Request $request, Response $responce) {
    global $method;
    $work_area = $request->getParam("work_area");
    $device_id = $request->getParam("device_id");
    $district = $request->getParam("district");
    $responce = array();

    $sql_select = "SELECT * FROM pppabxznag.engineer_messages WHERE user_device_id = '$device_id' and msg_inteded_for='$work_area' and msg_status='unseen' and msg_type='in' and district='$district'";

    $sql_exec = $method->select($sql_select);

    if ($sql_exec) {
        // $responce["data"] = $sql_exec;
        // $responce["rows"] = count($sql_exec);
        return json_encode(array("rows" => count($sql_exec), "data" => $sql_exec));
    } else {
        return json_encode(array("rows" => 0, "data" => []));
    }
});

$app->map(['GET', 'POST'], '/update_eng_status', function (Request $request, Response $response) {
    global $method;
    $work_area = $request->getParam("work_area");
    $device_id = $request->getParam("device_id");
    $district = $request->getParam("district");
    $response = [];
    // Update user status
    $sql_update_user = "UPDATE pppabxznag.engineers_reg SET status='y', work_area='$work_area', district='$district' WHERE device_id='$device_id'";

    $update_exec = $method->query($sql_update_user);

    if ($update_exec) {
        array_push($response, array("code" => "update_success", "message" => "user update successfully"));
        return json_encode($response);
    } else {
        array_push($response, array("code" => "update_failed", "message" => "user update failed"));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/eng_msg_seen', function (Request $request, Response $response) {
    global $method;

    $reply_id = $request->getParam('query_id');

    $sql_stmt = "UPDATE pppabxznag.engineer_messages SET msg_status='seen' WHERE msg_id='$reply_id'";

    $results = $method->query($sql_stmt);
    $response = array();

    if ($results) {
        $code = "updated_successfully";
        array_push($response, array("code" => $code));
        return json_encode($response);
    } else {
        $code = "update_failed";
        array_push($response, array("code" => $code));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/tech_reply', function (Request $request, Response $response) {
    date_default_timezone_set('Africa/Johannesburg');
    $current_date = '' . date("Y:m:d H:i:s");
    global $method;
    $user_message = $request->getParam("user_message");
    $device_id = $request->getParam("device_id");
    $work_area = $request->getParam("work_area");
    $district = $request->getParam("district");
    $response = array();

    $sql_insert = "INSERT INTO pppabxznag.engineer_messages(`msg_id`,`message`,`msg_type`,`msg_inteded_for`,`msg_date`,`msg_status`,`user_device_id`,`district`)VALUE(null,'$user_message','out','$work_area','$current_date','unseen','$device_id','$district')";

    // Sql Execute

    $SQL_exec = $method->query($sql_insert);

    if ($SQL_exec) {
        $code = "insert_success";
        $message = "Data inserted successfully";
        array_push($response, array("code" => $code, "message" => $message));
        return json_encode($response);
    } else {
        $code = "insert_error";
        $message = "Data insert failed";
        array_push($response, array("code" => $code, "message" => $message));
        return json_encode($response);
    }
});

$app->map(['GET', 'POST'], '/eng_dashboard_replies', function (Request $request, Response $responce) {
    global $method;
    $responce = array();

    $sql_select = "SELECT * FROM pppabxznag.engineer_messages WHERE  msg_inteded_for='Yard' and msg_status='unseen' and msg_type='out'";

    $sql_exec = $method->select($sql_select);

    if ($sql_exec) {
        $responce["data"] = $sql_exec;
        $responce["rows"] = count($sql_exec);
        return json_encode($sql_exec);
    } else {
        $responce["data"] = [];
        $responce["rows"] = "0";
        return json_encode($responce);
    }
});

$app->map(['GET', 'POST'], '/tech_end_shift', function (Request $request, Response $responce) {
    global $method;
    $responce = array();
    $device_id = $request->getParam("device_id");

    /**
     * Change Status for engineer to n;
     */

    $update_eng_status = "update pppabxznag.engineers_reg SET status='n' where device_id='$device_id'";

    $sql_exec = $method->query($update_eng_status);

    if ($sql_exec) {
        $code = "logout_success";
        array_push($responce, array("code" => $code));
        return json_encode($responce);
    } else {
        $code = "logout_failed";
        array_push($responce, array("code" => $code));
        return json_encode($responce);
    }
});


$app->map(['GET', 'POST'], '/home_station', function (Request $request, Response $response) {
    global $method;
    $province = $request->getParam('province');

    $sql_select = "SELECT `title` FROM pppabxznag.locations_by_line where `region` = '$province' order by `title`";
    $sql_exec = $method->select($sql_select);

    if ($sql_exec) {
        return json_encode($sql_exec);
    } else {
        return json_encode(array("error" => "yes"));
    }
});

$app->map(['GET', 'POST'], '/get_general_complains', function (Request $request, Response $response) {
    global $method;
    $data = json_decode(file_get_contents('php://input'));
    $district = $data->district;

    $sql_select = "SELECT * FROM complain cmp inner join sikephi_users sur Where cmp.device_id = sur.device_id and cmp.districts like '%$district%' order by cmp.cm_id desc";
    $sql_exec = $method->select($sql_select);

    if ($sql_exec) {
        return json_encode(array("rows" => count($sql_exec), "data" => $sql_exec));
    } else {
        return json_encode(array("rows" => 0, "data" => []));
    }
});

$app->map(['GET', 'POST'], '/get_user_home_station', function (Request $request, Response $response) {
    global $method;
    $data = json_decode(file_get_contents("php://input"));
    $region = $request->getParam('region');

    $sql_select = "SELECT DISTINCT `title` FROM pppabxznag.locations_by_line WHERE `region`='$region'";

    $sql_exec = $method->select($sql_select);

    if ($sql_exec) {
        return json_encode($sql_exec);
    } else {
        return json_encode(array("rows" => 0, "data" => []));
    }
});

$app->map(['GET', 'POST'], '/test_api', function (Request $request, Response $response) {
    global $method;
    $data = json_decode(file_get_contents("php://input"));

    $device_id = $request->getParam('device_id');

    $sql_query = "SELECT * FROM `sikephi_users` WHERE `device_id`='$device_id'";

    $sql_exec = $method->select($sql_query);

    if ($sql_exec) {
        return $sql_exec[0]["first_name"];
    } else {
        echo "Helloo world error";
    }
});

$app->map(['GET', 'POST'], '/update_booking', function (Request $request, Response $response) {
    global $method;

    $booking_id = $request->getParam('BOOKING_ID');
    $session_type = $request->getParam('SESSION_TYPE');

    $sql_query = "UPDATE `bookings` SET session_type='$session_type' WHERE bookings_id='$booking_id'";

    $sql_exec = $method->query($sql_query);

    if ($sql_exec) {
        return json_encode(array("rows" => 1, "data" => "UPDATED_SUCCESSFULLY"));
    } else {
        return json_encode(array("rows" => 0, "data" => "UPDATD_FAILED"));
    }
});


$app->map(['GET', 'POST'], '/covid_updates', function (Request $request, Response $response) {
    global $method;
    $region = $request->getParam("key");

    $sql_query = "SELECT * FROM `covid_19_statistics` WHERE `location`='$region'";

    $sql_exec = $method->select($sql_query);

    if ($sql_exec) {
        echo json_encode($sql_exec);
    } else {
        echo json_encode(array("rows" => 0, "data" => "SELECT_FAILED"));
    }
});

$app->map(['GET','POST'],'/check_for_update', function (Request $request, Response $responce){
    global $method;
    $data = json_decode(file_get_contents("php://input"));
    $screen = strtoupper($data->screen);

    $sql_get_updated = "SELECT DISTINCT `train_no`, `statuss`, `comments`, `update_date` FROM `trains` WHERE `update_date` <> '0000-00-00 00:00:00' AND `checked_by` NOT LIKE '%$screen%'";
    
    $sql_exec = $method->select($sql_get_updated);

    if($sql_exec){
        $set_to_seen = $method->setSeen($screen);

        if($set_to_seen > 0){
            return json_encode(array("data"=>$sql_exec,"rows"=>count($sql_exec)));
        }
    }
    else{
        return json_encode(array("data"=>"[]","rows"=>0));
    }

});

$app->run();
