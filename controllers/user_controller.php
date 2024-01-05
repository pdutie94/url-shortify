<?php
require_once('controllers/base_controller.php');
require_once('models/user.php');

class UserController extends BaseController {
    function __construct() {
        $this->folder = 'user';

        parent::__construct();
    }

    public function index() {
        $users = User::all(20);
        $data = array('users' => $users);
        $this->render('index', $data);
    }
}