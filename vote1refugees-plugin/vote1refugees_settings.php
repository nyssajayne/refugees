<div class="wrap">
<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

<form method="post" action="options.php">
	<?php 
		settings_fields( 'flag_descriptions' );
		settings_fields( 'sharing_options' );
		do_settings_sections( 'vote1refugees_settings' );
		submit_button(); 
	?>
</form>

</div>