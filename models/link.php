<?php
class Link {
	public $id;
	public $long_url;
	public $short_url;
	public $user_id;
	public $created_at;
	function __construct( $id, $long_url, $short_url, $user_id, $created_at ) {
		$this->id         = $id;
		$this->long_url   = $long_url;
		$this->short_url  = $short_url;
		$this->user_id    = $user_id;
		$this->created_at = $created_at;
	}

	public static function all( $limit = -1, $offset = 0 ) {
		$user       = User::get_current_user();
		$page       = isset( $_GET['page'] ) ? $_GET['page'] : 1;
		$start_from = ( $page - 1 ) * $limit;

		$sql = 'SELECT * FROM links WHERE user_id = ' . (int) $user['id'] . ' ORDER BY id DESC';
		if ( 0 < $limit ) {
			$sql = 'SELECT * FROM links WHERE user_id = ' . (int) $user['id'] . ' ORDER BY id DESC LIMIT ' . $limit . ' OFFSET ' . $start_from;
		}
		$list = DB::fetchAll( $sql );

		return $list;
	}

	public static function get_links_in_day() {
		$db = DB::getInstance();
		// Lấy ngày hiện tại
		$currentDate = date( 'Y-m-d' );

		// Truy vấn SQL để lấy những liên kết đã tạo trong ngày hiện tại
		$sql   = 'SELECT * FROM links WHERE DATE(created_at) = :current_date ORDER BY id DESC';
		$query = $db->prepare( $sql );
		$query->bindParam( ':current_date', $currentDate );
		$query->execute();
		$links = $query->fetchAll();

		return $links;
	}

	public static function get_all_daily_views() {
		$db           = DB::getInstance();
		$current_date = date( 'Y-m-d' );

		$stmt_all_daily_views = $db->prepare( 'SELECT SUM(views_count) AS all_daily_views FROM link_view_count WHERE date = :current_date' );
		$stmt_all_daily_views->bindParam( ':current_date', $current_date, PDO::PARAM_STR );
		$stmt_all_daily_views->execute();
		$result_all_daily_views = $stmt_all_daily_views->fetchColumn();

		return $result_all_daily_views == null ? 0 : $result_all_daily_views;
	}

	public static function get_all_monthly_views() {
		$db            = DB::getInstance();
		$current_month = date( 'Y-m' );

		$stmt_all_monthly_views = $db->prepare( 'SELECT SUM(views_count) AS all_monthly_views FROM link_view_count WHERE DATE_FORMAT(date, "%Y-%m") = :current_month' );
		$stmt_all_monthly_views->bindParam( ':current_month', $current_month, PDO::PARAM_STR );
		$stmt_all_monthly_views->execute();
		$result_all_monthly_views = $stmt_all_monthly_views->fetchColumn();

		return $result_all_monthly_views == null ? 0 : $result_all_monthly_views;
	}

	public static function get_all_weekly_views( $user_id = 0 ) {
		$db = DB::getInstance();
		// Lấy ngày đầu tiên của tuần hiện tại
		$first_day_of_week = date( 'Y-m-d', strtotime( 'monday this week' ) );

		// Truy vấn SQL để lấy tổng số lượt xem trong tuần hiện tại cho tất cả liên kết
		$stmt_all_weekly_views = $db->prepare( 'SELECT SUM(views_count) AS all_weekly_views FROM link_view_count WHERE date >= :first_day_of_week' );
		if ( $user_id !== 0 ) {
			$stmt_all_weekly_views = $db->prepare( 'SELECT SUM(views_count) AS all_weekly_views FROM link_view_count WHERE date >= :first_day_of_week AND short_url IN (SELECT short_url FROM links WHERE user_id = :user_id)' );
			$stmt_all_weekly_views->bindParam( ':user_id', $user_id, PDO::PARAM_INT );
		}
		$stmt_all_weekly_views->bindParam( ':first_day_of_week', $first_day_of_week, PDO::PARAM_STR );
		$stmt_all_weekly_views->execute();
		$result_all_weekly_views = $stmt_all_weekly_views->fetchColumn();

		return $result_all_weekly_views == null ? 0 : $result_all_weekly_views;
	}

	public static function get_all_countries_views() {
		$db            = DB::getInstance();
		$current_month = date( 'Y-m' );

		// Truy vấn SQL để lấy tất cả quốc gia và tổng số lượng view của từng quốc gia của tất cả liên kết trong một tháng
		$stmt_all_countries_views = $db->prepare(
			'
			SELECT country, country_code, SUM(view) AS total_views
			FROM link_view_country
			WHERE DATE_FORMAT(created_at, "%Y-%m") = :current_month
			GROUP BY country, country_code
			ORDER BY total_views DESC
		'
		);

		$stmt_all_countries_views->bindParam( ':current_month', $current_month, PDO::PARAM_STR );
		$stmt_all_countries_views->execute();
		$all_countries_views = $stmt_all_countries_views->fetchAll( PDO::FETCH_ASSOC );
		return $all_countries_views;
	}

	public static function get_total_daily_views() {
		$db    = DB::getInstance();
		$query = '
			SELECT
				DATE(link_view_count.date) AS x,
				SUM(link_view_count.views_count) AS y
			FROM
				link_view_count
			WHERE
				MONTH(link_view_count.date) = MONTH(NOW())
			GROUP BY
				x
			ORDER BY
				x;
		';

		$stmt = $db->prepare( $query );
		$stmt->execute();

		$data = $stmt->fetchAll( PDO::FETCH_ASSOC );

		return $data;
	}

	public static function pagination( $limit = 20 ) {
		$curr_page = isset( $_GET['page'] ) ? intval( $_GET['page'] ) : 1;
		$per_page  = $limit;

		$sql         = 'SELECT COUNT(*) as total_rows FROM links ORDER BY id DESC';
		$row         = DB::fetch( $sql );
		$total_pages = ceil( $row['total_rows'] / $per_page );

		$max_pages_displayed = 5;
		$half_max            = floor( $max_pages_displayed / 2 );
		$start_page          = max( 1, min( $curr_page - $half_max, $total_pages - $max_pages_displayed + 1 ) );
		$end_page            = min( $start_page + $max_pages_displayed - 1, $total_pages );
		ob_start();
		?>
		<ul class="uk-pagination uk-margin-small-top" uk-margin>

			<li class="<?php echo 1 === $curr_page ? 'uk-disabled' : ''; ?>"><a href="<?php echo SITE_URL; ?>/index.php?controller=links&action=list&page=<?php echo $curr_page - 1; ?>"><span uk-pagination-previous></span></a></li>
			
			<?php
			for ( $i = $start_page; $i <= $end_page; $i++ ) {
				$is_active = $curr_page == $i;
				?>
				<?php if ( $is_active ) { ?>
					<li class="uk-active"><span><?php echo $i; ?></span></li>
				<?php } else { ?>
					<li><a href="<?php echo SITE_URL; ?>/index.php?controller=links&action=list&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
				<?php } ?>
			<?php } ?>
			
			<li class="<?php echo $total_pages == $curr_page ? 'uk-disabled' : ''; ?>"><a href="<?php echo SITE_URL; ?>/index.php?controller=links&action=list&page=<?php echo $curr_page + 1; ?>"><span uk-pagination-next></span></a></li>
		</ul>
		<?php
		$html = ob_get_clean();
		return $html;
	}
}
