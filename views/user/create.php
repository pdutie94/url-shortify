<?php
$notices = array();
$errors  = array();
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
					<input class="uk-input" type="text" name="username" value="" minlength="6" required>
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
					<input class="uk-input" type="text" name="full_name" value="">
				</div>
			</div>
			
			<div class="uk-margin">
				<label class="uk-form-label" for="email">Email</label>
				<div class="uk-form-controls">
					<input class="uk-input" type="email" name="email" value="">
				</div>
			</div>

			<div class="uk-margin">
				<button class="uk-button uk-button-primary" type="submit">Tạo tài khoản</button>
			</div>
		</form>
	</div>
</div>
