<?php get_header(); ?>

	<session>
		<div class="row top">
			<div class="col top-page">
				<p class="login-minititle mt-4">タスク管理アプリ</p>
				<h1 class="login-title  mb-5">My taskとは</h1>
				<div class="introductory-text1">
					<p>1．期限が決まっていて、状態が変わっていく”タスク”</p>
					<p>近日中に単発で行う”ToDo”</p>
					<p>「バラバラに管理する形が多いこの２つを、まとめて管理する」</p>
				</div>
				<div class="introductory-text2">
					<p>2．リーダーは「他スタッフの進捗状況を確認できる」</p>
					<p class="mt-5">この２点を実現させるために「My task」は生まれました。</p>
					<a href="../profile/index.html" target="_blank" rel="noopener noreferrer">
          				作者プロフィールはこちら
        			</a>
				</div>
			</div>
			<div class="col">
				<img class="home-image" src="<?php echo get_template_directory_uri(); ?>/img/home-image.jpg" />
				<img class="member-image" src="<?php echo get_template_directory_uri(); ?>/img/member-image.jpg" />
			</div>
		</div>
	</session>

	<div class="col login-form">
				<?php if ( have_posts() ) {
					  while ( have_posts() ) { the_post(); ?>
						<div><?php the_content(); ?></div>
					<?php }
				}?>
	</div>

<?php get_footer(); ?>