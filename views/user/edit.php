<?php
$user_id = filter_var( $_GET['uid'], FILTER_VALIDATE_INT );

$errors  = array();
$notices = array();
// Xử lý dữ liệu từ biểu mẫu chỉnh sửa
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$db                  = DB::getInstance();
	$full_name           = $_POST['full_name'];
	$email               = $_POST['email'];
	$new_password        = $_POST['new_password'];
	$confirm_password    = $_POST['confirm_password'];
	$avatar_upload_check = false;

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

			// Nếu avatar được chọn thì cập nhật avatar.
			if ( isset( $_FILES['avatar'] ) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK ) {
				$uploadDir  = 'uploads/avatars/';
				$uploadPath = $uploadDir . basename( $_FILES['avatar']['name'] );

				// Kiểm tra và chuyển ảnh vào thư mục
				if ( $avatar_upload_check = move_uploaded_file( $_FILES['avatar']['tmp_name'], $uploadPath ) ) {
					// Lưu đường dẫn vào cơ sở dữ liệu
					$avatar_path = $uploadPath;
					$sql        .= ', avatar_path = :avatar_path';
				} else {
					$errors[] = 'Lỗi khi tải lên ảnh đại diện.';
				}
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
			if ( $avatar_upload_check ) {
				$stmt->bindParam( ':avatar_path', $avatar_path, PDO::PARAM_STR );
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

$user = User::get_user_by_id( $user_id );
?>

<?php if ( isset( $_GET['error'] ) && 'access_deny' === $_GET['error'] ) { ?>
	<div class="notices-wrapper uk-alert-danger uk-margin-top" uk-alert>
		<div class="uk-text-danger">Bạn không có quyền truy cập vào trang vừa rồi!</div>
	</div>
<?php } ?>
<?php if ( ! empty( $notices ) ) { ?>
	<div class="notices-wrapper uk-alert-success uk-margin-medium-top" uk-alert>
		<ul class="uk-list">
			<?php foreach ( $notices as $notice ) { ?>
				<li class="uk-text-success"><?php echo $notice; ?></li>
			<?php } ?>
		</ul>
	</div>
<?php } ?>
<?php if ( ! empty( $errors ) ) { ?>
	<div class="errors-wrapper uk-alert-danger uk-margin-medium-top" uk-alert>
		<ul class="uk-list">
			<?php foreach ( $errors as $error ) { ?>
				<li class="uk-text-danger"><?php echo $error; ?></li>
			<?php } ?>
		</ul>
	</div>
<?php } ?>
<div class="uk-section uk-margin-medium-top uk-card uk-card-default my-padding uk-card-body my-border-radius my-box-shadow-none">
	<div class="page-header my-margin-bottom">
		<h1 class="my-heading uk-margin-remove">Cập nhật thông tin</h1>
	</div>
	<div class="content-body">
		<form class="uk-form-stacked" method="post" enctype="multipart/form-data">
			<input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

			<div id="avatar_preview_wrapper">
				<img id="avatarPreview" src="<?php echo $user['avatar_path'] != null ? $user['avatar_path'] : 'uploads/avatars/default_avatar.png'; ?>" alt="Preview" class="uk-object-cover uk-border-circle" style="width: 150px; height: 150px;">
			</div>
			
			<div class="uk-margin">
				<label class="uk-form-label" for="avatar">Ảnh đại diện</label>
				<div class="uk-form-custom">
					<input type="file" name="avatar" id="avatarInput" accept="image/*" onchange="previewImage()">
					<button class="uk-button uk-button-default" type="button" tabindex="-1">Chọn ảnh</button>
				</div>
			</div>

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

			<div class="uk-margin uk-margin-remove-bottom">
				<button class="uk-button uk-button-primary" type="submit">Cập nhật</button>
			</div>
		</form>
	</div>
</div>
<script>
function previewImage() {
	var input = document.getElementById('avatarInput');
	var preview = document.getElementById('avatarPreview');

	// Kiểm tra xem có tệp tin nào được chọn không
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function(e) {
			preview.src = e.target.result;
		};

		reader.readAsDataURL(input.files[0]);
	} else {
		preview.src = 'uploads/avatars/default_avatar.png'; // Đặt lại ảnh xem trước nếu không có ảnh nào được chọn
	}
}
</script>