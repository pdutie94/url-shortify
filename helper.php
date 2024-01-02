<?php
function site_url() {
    $protocol = '';
    if (isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $protocol = 'https://';
    }
    else {
        $protocol = 'http://';
    }
    $domain_name = $_SERVER['HTTP_HOST'];
    return $protocol.$domain_name;
}
define('SITE_URL', site_url());

function is_user_logged_in() {
    // Check if the user is already logged in, if yes then redirect him to welcome page
    return isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true;
}