<?php
require_once 'controllers/base_controller.php';
require_once 'models/user.php';

class UserController extends BaseController {
	function __construct() {
		$this->folder = 'user';

		parent::__construct();
	}

	public function index() {
		if ( ! is_admin_user() ) {
			$user = User::get_current_user();
			header( 'location: ' . SITE_URL . '/index.php?controller=user&action=edit&uid=' . intval( $user['id'] ) . '&error=access_deny' );
			exit();
		}
		$users = User::all( 20 );
		$data  = array( 'users' => $users );
		$this->render( 'index', $data );
	}

	public function stats() {
		if ( ! isset( $_GET['uid'] ) ) {
			header( 'location:' . SITE_URL );
		}
		$user_id            = intval( $_GET['uid'] );
		$view_in_day        = User::get_view_in_month( $user_id );
		$view_in_curr_month = User::get_view_in_month( $user_id );
		$total_view         = User::get_total_view( $user_id );
		$data               = array(
			'total_view'    => $total_view,
			'view_in_month' => $view_in_curr_month,
			'view_in_day'   => $view_in_day,
		);
		$this->render( 'stats', $data );
	}

	public function edit() {
		$user_id   = filter_var( $_GET['uid'], FILTER_VALIDATE_INT );
		$curr_user = User::get_current_user();
		if ( ! is_admin_user() && $curr_user['id'] !== $user_id ) {
			header( 'Location: ' . SITE_URL );
		}
		$data = array();
		$this->render( 'edit', $data );
	}

	public function create() {
		if ( ! is_admin_user() ) {
			header( 'Location: ' . SITE_URL );
		}
		$data = array();
		$this->render( 'create', $data );
	}
}
