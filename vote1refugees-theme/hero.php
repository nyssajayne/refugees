

<header>
	<a href="<?php bloginfo( 'url' ); ?>">
		<?php if(is_page(53)): ?>
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/success.png" alt="<?php bloginfo( 'title' ); ?>" />
		<?php else: ?>
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/header.png" alt="<?php bloginfo( 'title' ); ?>" />
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/title.png" alt="<?php bloginfo( 'title' ); ?>" id="title" />
		<?php endif;?>
	</a>
</header>

<?php include('navigation.php'); ?>