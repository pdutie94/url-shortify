<?php
class User {
	public $id;
	public $username;
	public $email;

	function __construct( $id, $username, $email ) {
		$this->id       = $id;
		$this->username = $username;
		$this->email    = $email;
	}

	public static function all( $limit = -1, $offset = 0 ) {
		$page       = isset( $_GET['page'] ) ? $_GET['page'] : 1;
		$start_from = ( $page - 1 ) * $limit;

		$sql = 'SELECT * FROM users ORDER BY id DESC';
		if ( 0 < $limit ) {
			$sql = 'SELECT * FROM users ORDER BY id ASC LIMIT ' . $limit . ' OFFSET ' . $start_from;
		}
		$list = DB::fetchAll( $sql );

		return $list;
	}

	public static function get_user( $username ) {
		$sql  = 'SELECT * FROM users WHERE username=:username OR email=:username';
		$user = DB::fetch( $sql, array( ':username' => $username ) );

		return $user;
	}

	public static function get_user_by_id( $user_id ) {
		$sql  = 'SELECT * FROM users WHERE id=:id';
		$user = DB::fetch( $sql, array( ':id' => $user_id ) );

		return $user;
	}

	public static function get_current_user() {
		$sql  = 'SELECT * FROM users WHERE id=:id';
		$user = DB::fetch( $sql, array( ':id' => $_SESSION['id'] ) );

		return $user;
	}

	public static function get_user_avatar( $size = array(), $id = null ) {
		if ( empty( $size ) ) {
			$size = array( '40px', '40px' );
		}
		$user_id          = $id == null ? $_SESSION['id'] : $id;
		$sql              = 'SELECT avatar_path FROM users WHERE id=:id';
		$user_avatar_path = DB::fetchColumn( $sql, array( ':id' => $user_id ) );
		$avatar_url       = SITE_URL . '/uploads/avatars/default_avatar.png';
		if ( $user_avatar_path != null ) {
			$avatar_url = SITE_URL . '/' . $user_avatar_path;
		}

		return '<img src="' . htmlspecialchars( $avatar_url, ENT_QUOTES, 'UTF-8' ) . '" class="uk-object-cover uk-border-circle" style="width: ' . $size[0] . '; height: ' . $size[1] . ';">';
	}

	public static function get_view_in_day( $user_id ) {
		$db = DB::getInstance();
		// Ngày hiện tại
		$curr_day = date( 'Y-m-d' );

		// Truy vấn lấy tổng số lượt xem của các link của user trong ngày hiện tại
		$query = '
			SELECT SUM(link_views.views_count) AS total_views
			FROM link_views
			JOIN links ON link_views.short_url = links.short_url
			WHERE links.user_id = :user_id
			AND DATE(link_views.date) = :curr_day;
		';

		$stmt = $db->prepare( $query );
		$stmt->bindParam( ':user_id', $user_id, PDO::PARAM_INT );
		$stmt->bindParam( ':curr_day', $curr_day );
		$stmt->execute();

		$result = $stmt->fetch( PDO::FETCH_ASSOC );

		// Hiển thị tổng số lượt xem của user trong ngày hiện tại
		return $result['total_views'] == null ? 0 : $result['total_views'];
	}

	public static function get_total_view( $user_id ) {
		$db    = DB::getInstance();
		$query = '
			SELECT SUM(link_views.views_count) AS total_views
			FROM link_views
			JOIN links ON link_views.short_url = links.short_url
			WHERE links.user_id = :userId;
		';

		$stmt = $db->prepare( $query );
		$stmt->bindParam( ':userId', $user_id, PDO::PARAM_INT );
		$stmt->execute();

		$result = $stmt->fetch( PDO::FETCH_ASSOC );

		// Hiển thị tổng số lượt xem toàn thời gian của user
		return $result['total_views'] == null ? 0 : $result['total_views'];
	}

	public static function get_view_in_month( $user_id, $date = '' ) {
		$db    = DB::getInstance();
		$query = '
			SELECT SUM(link_views.views_count) AS total_views
			FROM link_views
			JOIN links ON link_views.short_url = links.short_url
			WHERE links.user_id = :userId
			AND YEAR(link_views.date) = YEAR(CURRENT_DATE)
			AND MONTH(link_views.date) = MONTH(CURRENT_DATE);
		';

		$stmt = $db->prepare( $query );
		$stmt->bindParam( ':userId', $user_id, PDO::PARAM_INT );
		$stmt->execute();

		$result = $stmt->fetch( PDO::FETCH_ASSOC );

		// Hiển thị tổng số lượt xem
		return $result['total_views'] == null ? 0 : $result['total_views'];
	}

	public static function pagination( $limit = 20 ) {
		$curr_page = isset( $_GET['page'] ) ? intval( $_GET['page'] ) : 1;
		$per_page  = $limit;

		$sql         = 'SELECT COUNT(*) as total_rows FROM users ORDER BY id DESC';
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
