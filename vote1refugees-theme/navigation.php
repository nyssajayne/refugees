<?php
	$defaults = array(
		'theme_location'  => '',
		'menu'            => '',
		'container'       => 'nav',
		'container_class' => 'nav-desktop',
		'container_id'    => '',
		'menu_class'      => 'vertical-text',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
	);

	$defaults_mob = array(
		'theme_location'  => '',
		'menu'            => '',
		'container'       => 'nav',
		'container_class' => 'nav-mob',
		'container_id'    => '',
		'menu_class'      => 'vertical-text',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul id="%1$s" class="%2$s"><li id="nav-menu-mob"><i class="fa fa-bars"></i><span class="menu-word">Menu</span></i></li>%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
	);

	wp_nav_menu( $defaults );
	wp_nav_menu( $defaults_mob );
?>