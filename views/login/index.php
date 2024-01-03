<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/models/user.php');

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $user = User::get_user($username);
    if( $user ) {
        if ( password_verify( $password, $user['password'] ) ) {
            // Login successful.
            $_SESSION["logged_in"] = true;
            $_SESSION["id"] = $user['id'];
            $_SESSION["username"] = $username; 

            echo '<script>UIkit.notification({
                message: \'Đã đăng nhập thành công!<br>Đang chuyển hướng...\',
                status: \'success\',
                pos: \'bottom-right\',
                timeout: 3000
            });
            </script>';

            header("location: " . SITE_URL);
        }
    } else {
        echo '<script>UIkit.notification({
            message: \'Tài khoản hoặc mật khẩu không chính xác!\',
            status: \'danger\',
            pos: \'bottom-right\',
            timeout: 3000
        });
        </script>';
    }
}
?>
<div class="uk-section uk-animation-fade uk-flex uk-flex-middle" uk-height-viewport>
	<div class="uk-width-1-1">
		<div class="uk-container">
			<div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
				<div class="uk-width-1-1@m">
					<div class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
						<h3 class="uk-card-title uk-text-center">Welcome back!</h3>
						<form class="form-login" action="" method="post">
							<div class="form-control uk-margin">
								<div class="uk-inline uk-width-1-1">
									<span class="uk-form-icon" uk-icon="icon: user"></span>
									<input name="username" class="uk-input uk-form-medium username" type="text" placeholder="Tên đăng nhập">
								</div>
							</div>
							<div class="form-control uk-margin">
								<div class="uk-inline uk-width-1-1">
									<span class="uk-form-icon" uk-icon="icon: lock"></span>
									<input name="password" class="uk-input uk-form-medium password" type="password" placeholder="Mật khẩu">	
								</div>
							</div>
							<div class="uk-margin">
								<button class="uk-button uk-button-primary uk-button-medium uk-width-1-1">Login</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>