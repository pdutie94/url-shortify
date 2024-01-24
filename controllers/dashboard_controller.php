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
		$users                = User::get_all_users();
		$all_daily_views      = Link::get_all_daily_views();
		$all_weekly_views     = Link::get_all_weekly_views();
		$all_monthly_views    = Link::get_all_monthly_views();
		$all_countries_views  = Link::get_all_countries_views();
		$all_daily_views_list = Link::get_total_daily_views();
		$data                 = array(
			'users'                => $users,
			'all_daily_views'      => $all_daily_views,
			'all_weekly_views'     => $all_weekly_views,
			'all_monthly_views'    => $all_monthly_views,
			'all_countries_views'  => $all_countries_views,
			'all_daily_views_list' => $all_daily_views_list,
		);
		$this->render( 'index', $data );
	}

	public function error() {
		$this->render( 'error' );
	}
}
