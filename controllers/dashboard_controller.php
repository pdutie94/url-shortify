<?php
require_once('controllers/base_controller.php');

class DashboardController extends BaseController {
    function __construct() {
        $this->folder = 'dashboard';
    }

    public function index() {
        if (!is_user_logged_in()) {
            header("location: " . SITE_URL . "/index.php?controller=login");
            exit();
        }
        $data = array();
        $this->render('index', $data);
    }

    public function error() {
        $this->render('error');
    }
}