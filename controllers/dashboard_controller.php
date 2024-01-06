<?php
require_once 'controllers/base_controller.php';

class DashboardController extends BaseController {
	function __construct() {
		$this->folder = 'dashboard';

		parent::__construct();
	}

	public function index() {
		$data = array();
		$this->render( 'index', $data );
	}

	public function error() {
		$this->render( 'error' );
	}
}
