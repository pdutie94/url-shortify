<?php
$user_id = filter_var( $_GET['uid'], FILTER_VALIDATE_INT );
$user    = User::get_user_by_id( $user_id );

$errors  = array();
$notices = array();
// Xử lý dữ liệu từ biểu mẫu chỉnh sửa
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$db               = DB::getInstance();
	$full_name        = $_POST['full_name'];
	$email            = $_POST['email'];
	$new_password     = $_POST['new_password'];
	$confirm_password = $_POST['confirm_password'];

	// Kiểm tra mật khẩu mới và mật khẩu xác nhận khớp nhau
	if ( $new_password !== $confirm_password ) {
		$errors[] = 'Mật khẩu mới và mật khẩu xác nhận không khớp nhau.';
	} else {
		// Cập nhật thông tin người dùng trong cơ sở dữ liệu
		try {
			$sql = 'UPDATE users SET full_name = :full_name, email = :email';

			// Nếu mật khẩu mới được nhập, thì cập nhật mật khẩu
			if ( ! empty( $new_password ) ) {
				$sql .= ', password = :password';
			}

			$sql .= ' WHERE id = :id';

			$stmt = $db->prepare( $sql );

			$stmt->bindParam( ':full_name', $full_name, PDO::PARAM_STR );
			$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
			$stmt->bindParam( ':id', $user_id, PDO::PARAM_INT );

			// Nếu mật khẩu mới được nhập, thì bind thêm vào statement
			if ( ! empty( $new_password ) ) {
				$hashed_password = password_hash( $new_password, PASSWORD_DEFAULT );
				$stmt->bindParam( ':password', $hashed_password, PDO::PARAM_STR );
			}

			if ( $stmt->execute() ) {
				$notices[] = 'Cập nhật thông tin người dùng thành công.';
			} else {
				$errors[] = 'Lỗi khi cập nhật thông tin người dùng.';
			}
		} catch ( PDOException $e ) {
			$errors[] = 'Error executing query: ' . $e->getMessage();
		}
	}
}
?>

<div class="uk-section uk-animation-fade">
	<h3 class="uk-margin-small-bottom">Chỉnh sửa thông tin</h3>
	<div class="content-body">
		<?php if ( ! empty( $notices ) ) { ?>
			<div class="notices-wrapper uk-alert-success" uk-alert>
				<ul class="uk-list">
					<?php foreach ( $notices as $notice ) { ?>
						<li class="uk-text-success"><?php echo $notice; ?></li>
					<?php } ?>
				</ul>
			</div>
		<?php } ?>
		<?php if ( ! empty( $errors ) ) { ?>
			<div class="errors-wrapper uk-alert-danger" uk-alert>
				<ul class="uk-list">
					<?php foreach ( $errors as $error ) { ?>
						<li class="uk-text-danger"><?php echo $error; ?></li>
					<?php } ?>
				</ul>
			</div>
		<?php } ?>
		<form class="uk-form-stacked" method="post">
			<input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
			
			<div class="uk-margin">
				<label class="uk-form-label" for="username">Username</label>
				<div class="uk-form-controls">
					<input class="uk-input" type="text" name="username" value="<?php echo htmlspecialchars( $user['username'] ); ?>" disabled>
				</div>
			</div>
			
			<div class="uk-margin">
				<label class="uk-form-label" for="full_name">Họ và tên</label>
				<div class="uk-form-controls">
					<input class="uk-input" type="text" name="full_name" value="<?php echo $user['full_name'] != null ? htmlspecialchars( $user['full_name'] ) : ''; ?>">
				</div>
			</div>
			
			<div class="uk-margin">
				<label class="uk-form-label" for="email">Email</label>
				<div class="uk-form-controls">
					<input class="uk-input" type="email" name="email" value="<?php echo $user['email'] != null ? htmlspecialchars( $user['email'] ) : ''; ?>">
				</div>
			</div>
			
			<div class="uk-margin">
				<label class="uk-form-label" for="new_password">Mật khẩu mới</label>
				<div class="uk-form-controls">
					<input class="uk-input" type="password" name="new_password" placeholder="Bỏ trống nếu không muốn thay đổi mật khẩu">
				</div>
			</div>

			<div class="uk-margin">
				<label class="uk-form-label" for="confirm_password">Xác nhận mật khẩu mới</label>
				<div class="uk-form-controls">
					<input class="uk-input" type="password" name="confirm_password" placeholder="Nhập lại mật khẩu mới">
				</div>
			</div>

			<div class="uk-margin">
				<button class="uk-button uk-button-primary" type="submit">Cập nhật</button>
			</div>
		</form>
	</div>
</div>
