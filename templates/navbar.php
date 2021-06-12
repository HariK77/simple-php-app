<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
	<div class="container">
		<a class="navbar-brand" href="<?= baseUrl(); ?>">Simple PHP App</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a class="nav-link" aria-current="page" href="<?= baseUrl(); ?>">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= baseUrl('about.php'); ?>">About</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= baseUrl('gallery.php'); ?>">Gallery</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= baseUrl('contact.php'); ?>">Contact</a>
				</li>
				<?php if (isset($_SESSION['is_logged']) && $_SESSION['is_logged']) : ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<?= ucwords($_SESSION['name']) ?>
						</a>
						<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
							<li><a class="dropdown-item" href="<?= baseUrl('profile/profile.php'); ?>">Profile</a></li>
							<li><a class="dropdown-item" href="<?= baseUrl('auth/change_password.php'); ?>">Change Password</a></li>
							<li>
								<hr class="dropdown-divider">
							</li>
							<li><a class="dropdown-item" href="<?= baseUrl('auth/logout.php'); ?>">Log Out</a></li>
						</ul>
					</li>
				<?php else : ?>
					<li class="nav-item">
						<a class="nav-link" href="<?= baseUrl('auth/login.php'); ?>">Login</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="<?= baseUrl('auth/register.php'); ?>">Register</a>
					</li>
				<?php endif ?>
			</ul>
		</div>
	</div>
</nav>
<div class="container">
	<?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])) : ?>
		<div class="alert alert-success" role="alert">
			<?php
			echo $_SESSION['success'];
			unset($_SESSION['success']);
			?>
		</div>
	<?php endif ?>
	<?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])) : ?>
		<div class="alert alert-danger" role="alert">
			<?php
			echo $_SESSION['error'];
			unset($_SESSION['error']);
			?>
		</div>
	<?php endif ?>
</div>