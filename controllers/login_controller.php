<?php
require_once('controllers/base_controller.php');

class LoginController extends BaseController {
    function __construct() {
        $this->folder = 'pages';
    }

    public function index() {
        $data = array();
        $this->render('login', $data);
    }
}