<?php
date_default_timezone_set('Asia/Ho_Chi_Minh'); // Chọn múi giờ tương ứng.
// Initialize the session
session_start();

require_once('connection.php');
require_once('helper.php');

if ( isset( $_GET['u'] ) ) {
    $db = DB::getInstance();
    $u = filter_var($_GET['u']);
    $sql = 'SELECT long_url FROM links WHERE short_url = :short_url';
    $query = $db->prepare($sql);
    $query->execute(
        array(
            ':short_url' => $u
        )
    );
    $long_url = $query->fetchColumn();
    if ( $long_url ) {
        // Ghi log mỗi khi có một lượt xem mới
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $currentDate = date('Y-m-d');
        $linkId = filter_var($_GET['u']);

        // Lấy danh sách các IP đã xem từ cơ sở dữ liệu
        $stmt = $db->prepare("SELECT viewed_ips, date FROM link_views WHERE short_url = :short_url ORDER BY viewed_at DESC LIMIT 1");
        $stmt->bindParam(':short_url', $linkId, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $viewedIPs = json_decode($result['viewed_ips'], true);
        
            // Kiểm tra xem địa chỉ IP đã xem link hay chưa
            if (!in_array($ipAddress, $viewedIPs)) {
                // Chưa có lượt xem từ địa chỉ IP này, thêm vào mảng và cập nhật cơ sở dữ liệu
                $viewedIPs[] = $ipAddress;
        
                // Lấy ngày từ bản ghi trước đó
                $lastDate = $result['date'];
        
                // Kiểm tra xem ngày mới có phải là ngày mới không
                if ($lastDate != $currentDate) {
                    // Ngày mới, cập nhật trường date
                    $stmtUpdate = $db->prepare("UPDATE link_views SET viewed_ips = :viewed_ips, date = :current_date WHERE short_url = :short_url");
                    $stmtUpdate->bindParam(':viewed_ips', json_encode($viewedIPs), PDO::PARAM_STR);
                    $stmtUpdate->bindParam(':current_date', $currentDate, PDO::PARAM_STR);
                    $stmtUpdate->bindParam(':short_url', $linkId, PDO::PARAM_STR);
                    $stmtUpdate->execute();
        
                    // Thực hiện các hành động khác nếu cần
                } else {
                    // Ngày vẫn cũ, chỉ cập nhật viewed_ips
                    $stmtUpdate = $db->prepare("UPDATE link_views SET viewed_ips = :viewed_ips WHERE short_url = :short_url");
                    $stmtUpdate->bindParam(':viewed_ips', json_encode($viewedIPs), PDO::PARAM_STR);
                    $stmtUpdate->bindParam(':short_url', $linkId, PDO::PARAM_STR);
                    $stmtUpdate->execute();
        
                    // Thực hiện các hành động khác nếu cần
                }
            }
        } else {
            // Bản ghi không tồn tại, tạo mới bản ghi và thêm lượt xem đầu tiên
            $viewedIPs = [$ipAddress];
            $json_viewed_ips = json_encode($viewedIPs);
        
            $stmtInsert = $db->prepare("INSERT INTO link_views (short_url, viewed_ips, date) VALUES (:short_url, :viewed_ips, :current_date)");
            $stmtInsert->bindParam(':short_url', $linkId, PDO::PARAM_STR);
            $stmtInsert->bindParam(':viewed_ips', $json_viewed_ips, PDO::PARAM_STR);
            $stmtInsert->bindParam(':current_date', $currentDate, PDO::PARAM_STR);
            $stmtInsert->execute();
        
            // Thực hiện các hành động khác nếu cần
        }
        header('location: ' . $long_url);
    }
    exit();
}

if (isset($_GET['controller'])) {
    $controller = $_GET['controller'];
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    } else {
        $action = 'index';
    }
} else {
    $controller = 'dashboard';
    $action = 'index';
}
require_once('routes.php');
// require_once('includes/ajax.php');
