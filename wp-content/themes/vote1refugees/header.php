<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# 
                  website: http://ogp.me/ns/website#">
		<meta charset="<?php bloginfo( 'charset' ); ?>">

		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;"/>

		<meta property="og:title" content="<?php echo bloginfo( 'title' ); ?>">
		<meta property="og:type" content="website" />
		<meta property="og:url" content="<?php echo bloginfo( 'url' ); ?>" />
		<meta property="og:description" content="<?php echo bloginfo( 'description' ); ?>" />
		<meta property="og:image" content="<?php echo get_stylesheet_directory_uri(); ?>/images/header.png" />
		<meta property="og:image:width" content="2400" />
		<meta property="og:image:height" content="1350" />
		<meta property="og:image:type" content="image/png" />
		
		<!-- GOOGLE ANALYTICS -->
		<!--<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-73097456-1', 'auto');
			ga('send', 'pageview');
		</script>-->
		
		<title><?php bloginfo( 'title' ); if(!is_home()) { echo ' - ' . get_the_title(); } ?></title>

		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>

		<div class="wrapper">