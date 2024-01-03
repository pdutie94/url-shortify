<?php
// Initialize the session
session_start();
require_once('connection.php');

$conn = DB::getInstance();
$long_url = $_POST['long_url'];

if ( !empty( $long_url ) && filter_var( $long_url, FILTER_VALIDATE_URL) ) {
    $ran_url = substr(md5(microtime()), rand(0,26), 10);
    echo json_encode(array('success' => true, 'short_id' => $ran_url, 'long_url' => $long_url));
} else {
    echo json_encode(array('success' => false, 'message' => 'Link không hợp lệ'));
}
exit();
