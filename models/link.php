<?php
class Link {
    public $id;
    public $long_url;
    public $short_url;
    public $user_id;
    public $created_at;
    function __construct($id, $long_url, $short_url, $user_id, $created_at)
    {
        $this->id = $id;
        $this->long_url = $long_url;
        $this->short_url = $short_url;
        $this->user_id = $user_id;
        $this->created_at = $created_at;
    }

    public static function all($limit = -1, $offset = 0) {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start_from = ($page - 1) * $limit;

        $sql  = 'SELECT * FROM links ORDER BY id DESC';
        if ( 0 < $limit ) {
            $sql  = 'SELECT * FROM links ORDER BY id DESC LIMIT '.$limit.' OFFSET '.$start_from;
        }
		$list = DB::fetchAll($sql);

        return $list;
    }

    public static function get_links_in_day() {
        $db = DB::getInstance();
        // Lấy ngày hiện tại
        $currentDate = date('Y-m-d');

        // Truy vấn SQL để lấy những liên kết đã tạo trong ngày hiện tại
        $sql = "SELECT * FROM links WHERE DATE(created_at) = :current_date ORDER BY id DESC";
        $query = $db->prepare($sql);
        $query->bindParam(':current_date', $currentDate);
        $query->execute();
        $links = $query->fetchAll();

        return $links;
    }

    public static function pagination($limit = 20) {
        $curr_page = isset( $_GET['page'] ) ? intval( $_GET['page'] ) : 1;
        $per_page = $limit;

        $sql  = 'SELECT COUNT(*) as total_rows FROM links ORDER BY id DESC';
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
