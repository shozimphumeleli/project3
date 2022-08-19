<?php
/**
 * Write a message to a file in the same directory
 */



$file = dirname(__FILE__) . '/another_cron.txt';

$data = "Time ". date('d-m-yy H:i:s') . "\n";

file_put_contents($file, $data, FILE_APPEND);
