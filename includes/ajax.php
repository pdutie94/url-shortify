<?php

date_default_timezone_set('Asia/Ho_Chi_Minh'); // Chọn múi giờ tương ứng

if (session_status() == PHP_SESSION_NONE ) {
    session_start();
}
require '../connection.php';
require '../models/user.php';
require '../helper.php';

if (isset($_POST['action_name']) ) {
    $action = $_POST['action_name'];

    switch ( $action ) {
    case 'generate_short_url_id':
        generate_short_url_id();
        break;
    case 'save_short_url_id':
        save_short_url_id();
        break;
    default:
        break;
    }
}

function generate_short_url_id()
{
    $long_url = $_POST['long_url'];

    if (! empty($long_url) && filter_var($long_url, FILTER_VALIDATE_URL) ) {
        $ran_url = generateRandomString(6);
        echo json_encode(
            array(
            'success'  => true,
            'short_id' => $ran_url,
            'long_url' => $long_url,
            )
        );
    } else {
        echo json_encode(
            array(
            'success' => false,
            'message' => 'Link không hợp lệ',
            )
        );
    }
    exit();
}

function save_short_url_id()
{
    $db = DB::getInstance();

    $long_url         = $_POST['long_url'];
    $short_id         = $_POST['short_url_id'];
    $current_datetime = date('Y-m-d H:i:s');

    if (! empty($long_url) && ! empty($short_id) && filter_var($long_url, FILTER_VALIDATE_URL) ) {
        $sql_get_short_id = 'SELECT short_url FROM links WHERE short_url = :short_url';
        $query            = $db->prepare($sql_get_short_id);
        $query->execute(
            array(
            ':short_url' => $short_id,
            )
        );
        $count = $query->rowCount();
        if ($count > 0 ) {
            echo json_encode(
                array(
                'success' => false,
                'message' => 'Link rút gọn đã tồn tại!',
                )
            );
        } else {
            $curr_user  = User::get_current_user();
            $sql_insert = 'INSERT INTO links (long_url, short_url, user_id) VALUES (:long_url, :short_url, :user_id)';
            $query      = $db->prepare($sql_insert);
            $query->bindValue(':long_url', $long_url, PDO::PARAM_STR);
            $query->bindValue(':short_url', $short_id, PDO::PARAM_STR);
            $query->bindValue(':user_id', intval($curr_user['id']), PDO::PARAM_INT);
            $query->execute();

            echo json_encode(
                array(
                'success' => true,
                'data'    => array(
                'username'   => $curr_user['username'],
                'long_url'   => $long_url,
                'short_url'  => SITE_URL . '/' . $short_id,
                'created_at' => $current_datetime,
                ),
                )
            );
        }
    }
}
