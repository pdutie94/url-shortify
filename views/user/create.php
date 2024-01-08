<?php
$notices = array();
$errors  = array();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$db = DB::getInstance();
    // Validate and sanitize user input
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $confirm_password = $_POST['confirm_password'];
    $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    // Additional validation checks can be added here

    // Check if the username is not empty
    if (empty($username)) {
        $errors[] = "Tài khoản là bắt buộc";
    }

    // Check if the username contains only letters, numbers, and underscores
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = "Tài khoản không hợp lệ. Chỉ chấp nhận chữ cái, số, và dấu gạch dưới (_).";
    }

    // Check if the username already exists in the database
    $check_username_query = "SELECT COUNT(*) FROM users WHERE username = :username";
    $check_username_stmt = $db->prepare($check_username_query);
    $check_username_stmt->execute(['username' => $username]);
    $username_exists = $check_username_stmt->fetchColumn();

    if ($username_exists) {
        $errors[] = "Tài khoản đã tồn tại. Vui lòng chọn tên khác.";
    }

    // Check if passwords match
    if (!password_verify($confirm_password, $password)) {
        $errors[] = "Mật khẩu không khớp";
    }

    // Insert user into the database
    if (empty($errors)) {
        $insert_user_query = "INSERT INTO users (username, password, full_name, email) VALUES (:username, :password, :full_name, :email)";
        $insert_user_stmt = $db->prepare($insert_user_query);

        if ($insert_user_stmt->execute([
            'username' => $username,
            'password' => $password,
            'full_name' => $full_name,
            'email' => $email,
        ])) {
            $notices[] = "Tạo tài khoản thành công";
        } else {
            $errors[] = "Có lỗi khi tạo tài khoản, thử lại sau!";
        }
    }
}
?>

<div class="uk-section uk-animation-fade">
	<div class="page-header">
		<h3 class="uk-margin-small-bottom">Thêm mới thành viên</h3>
	</div>
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
			<div class="uk-margin">
				<label class="uk-form-label" for="username">Tài khoản *</label>
				<div class="uk-form-controls">
					<input class="uk-input" type="text" name="username" value="<?php echo htmlspecialchars($username ?? '', ENT_QUOTES, 'UTF-8'); ?>" minlength="6" required>
				</div>
			</div>
			
			<div class="uk-margin">
				<label class="uk-form-label" for="password">Mật khẩu *</label>
				<div class="uk-form-controls">
					<input class="uk-input" type="password" name="password" minlength="8" required>
				</div>
			</div>

			<div class="uk-margin">
				<label class="uk-form-label" for="confirm_password">Xác nhận mật khẩu *</label>
				<div class="uk-form-controls">
					<input class="uk-input" type="password" name="confirm_password" minlength="8" required>
				</div>
			</div>
			
			<div class="uk-margin">
				<label class="uk-form-label" for="full_name">Họ và tên</label>
				<div class="uk-form-controls">
					<input class="uk-input" type="text" name="full_name" value="<?php echo htmlspecialchars($full_name ?? '', ENT_QUOTES, 'UTF-8'); ?>">
				</div>
			</div>
			
			<div class="uk-margin">
				<label class="uk-form-label" for="email">Email</label>
				<div class="uk-form-controls">
					<input class="uk-input" type="email" name="email" value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8'); ?>">
				</div>
			</div>

			<div class="uk-margin">
				<button class="uk-button uk-button-primary" type="submit">Tạo tài khoản</button>
			</div>
		</form>
	</div>
</div>
