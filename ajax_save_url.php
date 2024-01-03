<?php
// Initialize the session
session_start();
require_once('connection.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/models/user.php');

$db = DB::getInstance();

$long_url = $_POST['long_url'];
$short_id = $_POST['short_url'];

if ( ! empty( $long_url ) && ! empty( $short_id ) && filter_var( $long_url, FILTER_VALIDATE_URL ) ) {
    $sql_get_short_id = 'SELECT short_url FROM links WHERE short_url = :short_url';
    $query = $db->prepare($sql_get_short_id);
    $query->execute(array(
        ':short_url' => $short_id
    ));
    $count = $query->rowCount();
    if ( $count > 0 ) {
        echo json_encode(array('success' => false, 'message' => 'Link rút gọn đã tồn tại!'));
    } else {
        $curr_user = User::get_current_user();
        $sql_insert = 'INSERT INTO links (long_url, short_url, user_id) VALUES (:long_url, :short_url, :user_id)';
        $query = $db->prepare($sql_insert);
        $query->bindValue(':long_url', $long_url, PDO::PARAM_STR);
        $query->bindValue(':short_url', $short_id, PDO::PARAM_STR);
        $query->bindValue(':user_id', intval($curr_user['id']), PDO::PARAM_INT);
        $query->execute();
        
        echo json_encode(
            array(
                'success' => true,
            )
        );
    }
}