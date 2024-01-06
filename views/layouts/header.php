<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/user.php';
?>

<div class="site-header">
	<nav class="uk-navbar-container">
		<div class="uk-container">
			<div uk-navbar>

				<div class="uk-navbar-left">
					<!-- <a class="uk-navbar-item uk-logo" href="#" aria-label="Back to Home">Logo</a> -->

					<ul class="uk-navbar-nav uk-visible@s">
						<li class="menu-item uk-active">
							<a href="<?php echo SITE_URL; ?>"><span class="uk-margin-small-right" uk-icon="home"></span>Dashboard</a>
						</li>
						<li class="menu-item">
							<a href="<?php echo SITE_URL . '/index.php?controller=links'; ?>"><span class="uk-margin-small-right" uk-icon="link"></span>Tạo Link <span uk-navbar-parent-icon></span></a>
							<div class="uk-navbar-dropdown">
								<ul class="uk-nav uk-navbar-dropdown-nav">
									<li><a href="<?php echo SITE_URL . '/index.php?controller=links&action=list'; ?>"><span class="uk-margin-small-right" uk-icon="list"></span>Danh sách Link</a></li>
								</ul>
							</div>
						</li>
						<li class="menu-item">
							<a href="<?php echo SITE_URL . '/index.php?controller=user'; ?>"><span class="uk-margin-small-right" uk-icon="users"></span>Thành viên</a>
						</li>
					</ul>
					<a href="#" class="uk-navbar-toggle uk-hidden@s" uk-navbar-toggle-icon uk-toggle="target: #sidenav"></a>

				</div>

				<div class="uk-navbar-right">
					<?php
					if ( is_user_logged_in() ) {
						$user = User::get_current_user();
						?>
						<div class="uk-navbar-item">
							<div class="uk-flex-inline uk-flex-middle" style="gap: 4px;">
								<span uk-icon="user"></span><span><?php echo $user['username']; ?>,</span> <a href="<?php echo SITE_URL; ?>/index.php?controller=login&action=logout">Thoát</a>
							</div>
						</div>
					<?php } else { ?>
						<a class="uk-button uk-button-primary uk-button-small uk-flex uk-flex-middle" href="<?php echo SITE_URL . '/index.php?controller=login'; ?>">
							<span class="uk-margin-small-right" uk-icon="user"></span>
							<span>Đăng nhập</span>
						</a>
					<?php } ?>
				</div>

			</div>
		</div>
	</nav>
</div>
<div id="sidenav" uk-offcanvas="flip: false">
	<div class="uk-offcanvas-bar">
		<ul class="uk-nav-default" uk-nav>
			<li class="menu-item uk-active">
				<a href="<?php echo SITE_URL; ?>"><span class="uk-margin-small-right" uk-icon="home"></span>Dashboard</a>
			</li>
			<li class="menu-item uk-parent">
                <a href="#"><span class="uk-margin-small-right" uk-icon="link"></span>Link rút gọn<span uk-nav-parent-icon></a>
				<ul class="uk-nav-sub">
					<li class="menu-item">
                        <a href="<?php echo SITE_URL . '/index.php?controller=links'; ?>"><span class="uk-margin-small-right" uk-icon="plus"></span>Tạo Link</a>
					</li>
					<li class="menu-item">
						<a href="<?php echo SITE_URL . '/index.php?controller=links&action=list'; ?>"><span class="uk-margin-small-right" uk-icon="list"></span>Danh sách Link</a>
					</li>
				</ul>
			</li>
			<li class="menu-item">
				<a href="<?php echo SITE_URL . '/index.php?controller=user'; ?>"><span class="uk-margin-small-right" uk-icon="users"></span>Thành viên</a>
			</li>
		</ul>
	</div>
</div>
