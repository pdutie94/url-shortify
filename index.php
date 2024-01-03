<?php
// Initialize the session
session_start();

require_once('connection.php');
require_once('helper.php');

if ( isset( $_GET['u'] ) ) {
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
    if ( $long_url ) {
        header('location: ' . $long_url);
    }
    exit();
}

if (isset($_GET['controller'])) {
    $controller = $_GET['controller'];
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    } else {
        $action = 'index';
    }
} else {
    $controller = 'dashboard';
    $action = 'index';
}
require_once('routes.php');
