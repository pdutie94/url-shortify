<?php
require_once 'controllers/base_controller.php';
require_once 'models/user.php';
require_once 'models/link.php';

class DashboardController extends BaseController {
	function __construct() {
		$this->folder = 'dashboard';

		parent::__construct();
	}

	public function index() {
		$all_daily_views     = Link::get_all_daily_views();
		$all_weekly_views    = Link::get_all_weekly_views();
		$all_monthly_views   = Link::get_all_monthly_views();
		$all_countries_views = Link::get_all_countries_views();
		$data                = array(
			'all_daily_views'     => $all_daily_views,
			'all_weekly_views'    => $all_weekly_views,
			'all_monthly_views'   => $all_monthly_views,
			'all_countries_views' => $all_countries_views,
		);
		$this->render( 'index', $data );
	}

	public function error() {
		$this->render( 'error' );
	}
}
