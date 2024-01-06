<?php

date_default_timezone_set('Asia/Ho_Chi_Minh'); // Chọn múi giờ tương ứng

// Initialize the session
session_start();

require_once('connection.php');
require_once('helper.php');

if (isset($_GET['u'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="canonical" href="<?= SITE_URL; ?>">
        <meta property="og:url" content="<?= SITE_URL; ?>">
        <meta property="og:title" content="Flatsomix">
        <title>Redirecting...</title>
    </head>
    <body>
    </body>
    </html>
    <?php
    $db = DB::getInstance();
    $u = filter_var($_GET['u']);

    $sql = 'SELECT long_url FROM links WHERE short_url = :short_url';
    $query = $db->prepare($sql);

    $query->execute(
        array(
            ':short_url' => $u
        )
    );

    $long_url = $query->fetchColumn();

    if ($long_url) {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $link_id = filter_var($_GET['u']);
        $check = update_link_views($link_id, $ip_address);

        // if ($check) {
            echo '<script>window.location.href = "'.$long_url.'";</script>';
            // die();
            // header("Location: includes/redirect.php?url=" . urlencode($long_url));
            // exit();
        // }
    }

    die();
}

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

require_once('routes.php');
