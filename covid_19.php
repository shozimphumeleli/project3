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

$response = get_web_page("https://corona-stats.mobi/api/json.2.0.php?key=QdNOKBoks4lMT3Z9zvXA");
$resArr = array();
$resArr = json_decode($response);
// print_r($resArr->RSA->National->Cases);
$cases = end($resArr->RSA->National->Cases);
$recoveries = end($resArr->RSA->National->Recoveries);
$deaths = end($resArr->RSA->National->Deaths);
// print_r($resArr->RSA->National->Recoveries);
// print_r($resArr->RSA->National->Deaths);
// $insert_new_stat = $method->covidUpdate($cases,$recoveries,$deaths);

echo $insert_new_stat;

function get_web_page($url)
{
    $options = array(
        CURLOPT_RETURNTRANSFER => true,   // return web page
        CURLOPT_HEADER         => false,  // don't return headers
        CURLOPT_FOLLOWLOCATION => true,   // follow redirects
        CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
        CURLOPT_ENCODING       => "",     // handle compressed
        CURLOPT_USERAGENT      => "test", // name of client
        CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
        CURLOPT_TIMEOUT        => 120,    // time-out on response
    );

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);

    $content  = curl_exec($ch);

    curl_close($ch);

    return $content;
}
