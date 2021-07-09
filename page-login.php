<?php get_header(); ?>

<session>
	<div class="container">
		<div class="row">
			<div class="col introductory-text">
				<p class="mt-5 login-minititle">タスク管理アプリ</p>
				<h1 class="login-title  mb-5">My taskとは</h1>
				<p>期限が決まっていて、状態が変わっていく<span>”タスク”</span></p>
				<p>近日中に単発的に行う<span>”ToDo”</span></p>
				<p>バラバラに管理する形が多いこの２つを１つにまとめたい！</p>
				<p>という気持ちから生まれました。</p>
				<img class="home-image mt-4" src="<?php echo get_template_directory_uri(); ?>/img/home-image.jpg" />

			</div>
			<div class="col mt-5 pt-5">
				<?php if ( have_posts() ) {
					  while ( have_posts() ) { the_post(); ?>
						<div><?php the_content(); ?></div>
					<?php }
				}?>
			</div>
		</div>
	</div>
</session>


<?php get_footer(); ?>