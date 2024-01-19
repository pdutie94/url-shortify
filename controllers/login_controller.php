<?php
require_once 'controllers/base_controller.php';
require_once 'models/user.php';

class LoginController extends BaseController {
	function __construct() {
		$this->folder = 'login';
	}

	public function index() {
		if ( is_user_logged_in() ) {
			header( 'location: ' . SITE_URL );
		}
		$data = array();
		$this->render( 'index', $data );
	}

	public function logout() {
		// Hủy cookie
		setcookie( 'user_token', '', time() - 3600, '/' );

		// Hủy session
		session_unset();
		session_destroy();
		header( 'location: ' . SITE_URL );
	}
}
