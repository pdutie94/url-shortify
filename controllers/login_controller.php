<?php
require_once('controllers/base_controller.php');


class LoginController extends BaseController {
    function __construct() {
        $this->folder = 'pages';
    }

    public function index() {
        if (is_user_logged_in()) {
            header("location: " . SITE_URL);
        }
        $data = array();
        $this->render('login', $data);
    }

    public function logout() {
        $_SESSION["logged_in"] = false;
        unset( $_SESSION["id"] );
        unset( $_SESSION["username"] );
        header("location: " . SITE_URL);
    }
}