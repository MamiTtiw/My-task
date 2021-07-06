<!doctype html>
<html lang="ja">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php wp_head(); ?>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-light">
		<a class="navbar-brand text-white" href="#"><?php bloginfo("name"); ?></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<?php if ( is_user_logged_in()) : ?>
					<li class="nav-item active">
						<a class="nav-link" href="<?php echo home_url(); ?>">Home <span class="sr-only">(current)</span></a>
					</li>
				<?php endif; ?>
				<?php if(current_user_can('editor')) : ?>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo home_url(); ?>/member">members</a>
					</li>
				<?php endif; ?>
				<?php if ( is_user_logged_in()) : ?>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo home_url(); ?>/account">account</a>
					</li>
				<?php endif; ?>
				<?php if ( is_user_logged_in()) : ?>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo home_url(); ?>/user">user</a>
					</li>
				<?php endif; ?>
				<?php if (! is_user_logged_in()) : ?>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo home_url(); ?>/login">login</a>
					</li>
				<?php endif; ?>
				<?php if ( is_user_logged_in()) : ?>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo home_url(); ?>/logout">logout</a>
					</li>
				<?php endif; ?>
			</ul>
			<!-- <form class="form-inline my-2 my-lg-0">
				<?php if ( is_user_logged_in()) : ?>
					<input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
					<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
				<?php endif; ?>
			</form> -->
		</div>
	</nav>