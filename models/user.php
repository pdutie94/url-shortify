<?php
class User {
    public $id;
    public $username;
    public $email;

    function __construct($id, $username, $email)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
    }

    public static function all($limit = -1, $offset = 0) {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start_from = ($page - 1) * $limit;

        $sql  = 'SELECT * FROM users ORDER BY id DESC';
        if ( 0 < $limit ) {
            $sql  = 'SELECT * FROM users ORDER BY id ASC LIMIT '.$limit.' OFFSET '.$start_from;
        }
		$list = DB::fetchAll($sql);

        return $list;
    }

    public static function get_user( $username ) {
        $sql  = 'SELECT * FROM users WHERE username=:username OR email=:username';
		$user = DB::fetch($sql, array( ':username' => $username));

        return $user;
    }

    public static function get_user_by_id( $user_id ) {
        $sql  = 'SELECT * FROM users WHERE id=:id';
		$user = DB::fetch($sql, array( ':id' => $user_id));

        return $user;
    }

    public static function get_current_user() {
        $sql  = 'SELECT * FROM users WHERE id=:id';
		$user = DB::fetch($sql, array( ':id' => $_SESSION['id']));

        return $user;
    }

    public static function pagination($limit = 20) {
        $curr_page = isset( $_GET['page'] ) ? intval( $_GET['page'] ) : 1;
        $per_page = $limit;

        $sql  = 'SELECT COUNT(*) as total_rows FROM users ORDER BY id DESC';
        $row = DB::fetch($sql);
        $total_pages = ceil($row['total_rows'] / $per_page);

        $max_pages_displayed = 5;
        $half_max = floor($max_pages_displayed / 2);
        $start_page = max(1, min($curr_page - $half_max, $total_pages - $max_pages_displayed + 1));
        $end_page = min($start_page + $max_pages_displayed - 1, $total_pages);
        ob_start();
        ?>
        <ul class="uk-pagination uk-margin-small-top" uk-margin>

            <li class="<?php echo 1 === $curr_page ? 'uk-disabled' : ''; ?>"><a href="<?= SITE_URL; ?>/index.php?controller=links&action=list&page=<?= $curr_page - 1; ?>"><span uk-pagination-previous></span></a></li>
            
            <?php for ($i = $start_page; $i <= $end_page; $i++) {
                $is_active = $curr_page == $i;
                ?>
                <?php if ( $is_active ) { ?>
                    <li class="uk-active"><span><?= $i; ?></span></li>
                <?php } else { ?>
                    <li><a href="<?= SITE_URL; ?>/index.php?controller=links&action=list&page=<?= $i; ?>"><?= $i; ?></a></li>
                <?php } ?>
            <?php } ?>
            
            <li class="<?php echo $total_pages == $curr_page ? 'uk-disabled' : ''; ?>"><a href="<?= SITE_URL; ?>/index.php?controller=links&action=list&page=<?= $curr_page + 1; ?>"><span uk-pagination-next></span></a></li>
        </ul>
        <?php
        $html = ob_get_clean();
        return $html;
    }
}
