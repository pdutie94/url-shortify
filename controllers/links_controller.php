<?php
require_once('controllers/base_controller.php');

class LinksController extends BaseController {
    function __construct() {
        $this->folder = 'links';
    }

    public function index() {
        if (!is_user_logged_in()) {
            header("location: " . SITE_URL . "/index.php?controller=login");
            exit();
        }
        $data = array();
        $this->render('index', $data);
    }
}