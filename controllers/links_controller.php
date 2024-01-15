<?php
require_once 'controllers/base_controller.php';
require_once 'models/link.php';
require_once 'models/user.php';

class LinksController extends BaseController {
	function __construct() {
		$this->folder = 'links';

		parent::__construct();
	}

	public function index() {
		$links = Link::all( 20 );
		$data  = array( 'links' => $links );
		$this->render( 'index', $data );
	}

	public function list() {
		$links = Link::all( 20 );
		$data  = array( 'links' => $links );
		$this->render( 'list', $data );
	}
}
