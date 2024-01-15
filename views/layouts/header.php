<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/user.php';
?>

<div class="site-header" uk-sticky>
	<nav class="uk-navbar-container">
		<div class="uk-container">
			<div uk-navbar>

				<div class="uk-navbar-left">
					<ul class="uk-navbar-nav uk-visible@m">
						<li class="menu-item">
							<a href="<?php echo SITE_URL; ?>"><span class="uk-margin-small-right" uk-icon="home"></span>Dashboard</a>
						</li>
						<li class="menu-item<?php echo is_active_menu_item( 'links' ) ? ' uk-active' : ''; ?>">
							<a href="<?php echo SITE_URL . '/index.php?controller=links'; ?>"><span class="uk-margin-small-right" uk-icon="link"></span>Danh sách Link</a>
						</li>
						<!-- <li class="menu-item<?php echo is_active_menu_item( 'links', 'list' ) ? ' uk-active' : ''; ?>"><a href="<?php echo SITE_URL . '/index.php?controller=links&action=list'; ?>"><span class="uk-margin-small-right" uk-icon="list"></span>Danh sách Link</a></li> -->
						<?php if ( is_admin_user() ) { ?>
							<li class="menu-item<?php echo is_active_menu_item( 'user' ) ? ' uk-active' : ''; ?>">
								<a href="<?php echo SITE_URL . '/index.php?controller=user'; ?>"><span class="uk-margin-small-right" uk-icon="users"></span>Thành viên</a>
							</li>
						<?php } else { ?>
							<li class="menu-item">
								<a href="<?php echo SITE_URL . '/index.php?controller=user&action=edit&uid=' . User::get_current_user()['id']; ?>"><span class="uk-margin-small-right" uk-icon="user"></span>Hồ sơ</a>
							</li>
						<?php } ?>
					</ul>
					<a href="#" class="uk-navbar-toggle uk-hidden@m" uk-navbar-toggle-icon uk-toggle="target: #sidenav"></a>

				</div>

				<div class="uk-navbar-right">
					<?php
					if ( is_user_logged_in() ) {
						$user = User::get_current_user();
						?>
						<div class="uk-navbar-item">
							<div class="uk-flex-inline uk-flex-middle" style="gap: 20px;">
								<a class="uk-flex-inline uk-flex-middle" href="<?php echo SITE_URL . '/index.php?controller=user&action=edit&uid=' . $user['id']; ?>"> <?php echo User::get_user_avatar(); ?><span class="uk-margin-small-left"><?php echo $user['username']; ?></a></span> <a href="<?php echo SITE_URL; ?>/index.php?controller=login&action=logout"><span uk-icon="sign-out"></span></a>
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
			<li class="menu-item uk-margin-small-bottom uk-active">
				<a href="<?php echo SITE_URL; ?>"><span class="uk-margin-small-right" uk-icon="home"></span>Dashboard</a>
			</li>
			<li class="menu-item uk-margin-small-bottom uk-margin-bottom">
				<a href="<?php echo SITE_URL . '/index.php?controller=links'; ?>"><span class="uk-margin-small-right" uk-icon="plus"></span>Danh sách Link</a>
			</li>
			<!-- <li class="menu-item uk-margin-small-bottom uk-margin-bottom">
				<a href="<?php echo SITE_URL . '/index.php?controller=links&action=list'; ?>"><span class="uk-margin-small-right" uk-icon="list"></span>Danh sách Link</a>
			</li> -->
			<li class="menu-item uk-margin-bottom">
				<a href="<?php echo SITE_URL . '/index.php?controller=user'; ?>"><span class="uk-margin-small-right" uk-icon="users"></span>Thành viên</a>
			</li>
		</ul>
	</div>
</div>
