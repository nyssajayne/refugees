<?php
/*
Plugin Name: Vote 1 Refugees
Plugin URI: http://www.shoesandblues.com
Description: Is your representative for or against mandatory detention this election?
Version: 0.1
Author: Nyssa & Brendan
Author URI: http://www.shoesandblues.com
License: This Vote 1 Refugees Wordpress Plugin is made available under the Open Database License: http://opendatacommons.org/licenses/odbl/1.0/. Any rights in individual contents of the database are licensed under the Database Contents License: http://opendatacommons.org/licenses/dbcl/1.0/
License URI: http://opendatacommons.org/licenses/odbl/1.0/
*/

function vote1refugees_add_menu_page() {
	add_menu_page(
		'Vote 1 Refugees',
		'Vote 1 Refugees',
		'manage_options',
		'vote1refugees_settings',
		'vote1refugees_add_settings_page'
	);

	add_submenu_page(
		'vote1refugees_settings',
		'Vote 1 Refugee Settings',
		'Settings',
		'manage_options',
		'vote1refugees_settings',
		'vote1refugees_add_settings_page'
	);

	add_submenu_page(
		'vote1refugees_settings',
		'Edit Sitting Politicians',
		'Edit Sitting Politicians',
		'read',
		'edit_politicians_via_tvfy',
		'vote1refugees_add_edit_politicians_via_tvfy'
	);

	add_submenu_page(
		'vote1refugees_settings',
		'Edit Candidates',
		'Edit Candidates',
		'read',
		'edit_candidates',
		'vote1refugees_add_edit_candidates_page'
	);
}

add_action( 'admin_menu', 'vote1refugees_add_menu_page' );

function vote1refugees_settings_init() {
	add_settings_section(
		'flag_descriptions',
		'Description of Flags',
		'vote1refugees_flag_descriptions',
		'vote1refugees_settings'
	);

	add_settings_section(
		'sharing_options',
		'Social Media Sharing Options',
		'vote1refugees_sharing_options',
		'vote1refugees_settings'
	);

	add_settings_field(
		'flag_description_red',
		'Description of Red Flag',
		'vote1refugees_flag_description_red',
		'vote1refugees_settings',
		'flag_descriptions'
	);

	add_settings_field(
		'flag_description_orange',
		'Description of Orange Flag',
		'vote1refugees_flag_description_orange',
		'vote1refugees_settings',
		'flag_descriptions'
	);

	add_settings_field(
		'flag_description_green',
		'Description of Green Flag',
		'vote1refugees_flag_description_green',
		'vote1refugees_settings',
		'flag_descriptions'
	);

	add_settings_field(
		'flag_description_unknown',
		'Description of Unknown Flag',
		'vote1refugees_flag_description_unknown',
		'vote1refugees_settings',
		'flag_descriptions'
	);

	add_settings_field(
		'sharing_options_twitter',
		'Twitter',
		'vote1refugees_sharing_options_twitter',
		'vote1refugees_settings',
		'sharing_options'
	);

	add_settings_field(
		'sharing_options_email',
		'Email',
		'vote1refugees_sharing_options_email',
		'vote1refugees_settings',
		'sharing_options'
	);

	register_setting( 'flag_descriptions', 'flag_description_red' );
	register_setting( 'flag_descriptions', 'flag_description_orange' );
	register_setting( 'flag_descriptions', 'flag_description_green' );
	register_setting( 'flag_descriptions', 'flag_description_unknown' );

	register_setting( 'sharing_options', 'sharing_options_twitter' );
	register_setting( 'sharing_options', 'sharing_options_email' );
}

add_action( 'admin_init', 'vote1refugees_settings_init' );

function vote1refugees_add_edit_politicians_page_via_tvfy() {
    ob_start();
	include('vote1refugees_edit_politicians_via_tvfy.php');
	$content = ob_get_clean();
	echo $content;
}

function vote1refugees_add_edit_candidates_page() {
    ob_start();
	include('vote1refugees_edit_candidates.php');
	$content = ob_get_clean();
	echo $content;
}

function vote1refugees_add_settings_page() {
	ob_start();
	include('vote1refugees_settings.php');
	$content = ob_get_clean();
	echo $content;
}

function vote1refugees_flag_descriptions() {

}

function vote1refugees_sharing_options() {

}

function vote1refugees_flag_description_red() {
	$setting = esc_attr(get_option('flag_description_red'));
	echo '<p><input name="flag_description_red" id="flag_description_red" type="text" value="' . $setting . '"></p>';
}

function vote1refugees_flag_description_orange() {
	$setting = esc_attr(get_option('flag_description_orange'));
	echo '<p><input name="flag_description_orange" id="flag_description_orange" type="text" value="' . get_option('flag_description_orange') . '"></p>';
}

function vote1refugees_flag_description_green() {
	$setting = esc_attr(get_option('flag_description_green'));
	echo '<p><input name="flag_description_green" id="flag_description_green" type="text" value="' . get_option('flag_description_green') . '"></p>';
}

function vote1refugees_flag_description_unknown() {
	$setting = esc_attr(get_option('flag_description_unknown'));
	echo '<p><input name="flag_description_unknown" id="flag_description_unknown" type="text" value="' . get_option('flag_description_unknown') . '"></p>';
}

function vote1refugees_sharing_options_twitter() {
	$setting = esc_attr(get_option('sharing_options_twitter'));
	echo '<p><input name="sharing_options_twitter" id="sharing_options_twitter" type="text" value="' . get_option('sharing_options_twitter') . '"></p>';
}

function vote1refugees_sharing_options_email() {
	$setting = esc_attr(get_option('sharing_options_email'));
	echo '<p><input name="sharing_options_email" id="sharing_options_email" type="text" value="' . get_option('sharing_options_email') . '"></p>';
}

function vote1refugees_shortcode_check_pollies(){
	ob_start();
	include('vote1refugees_shortcode.php');
	$content = ob_get_clean();
	return $content;
}

add_shortcode( 'check_pollies', 'vote1refugees_shortcode_check_pollies' );

/* Sitting Politicians */
function vote1refugees_add_politicians_via_tvfy() {
	global $wpdb;
	include('vote1refugees_variables.php');

	$politicians_json = file_get_contents('https://theyvoteforyou.org.au/api/v1/people.json?key=' . $THEYVOTEFORYOU);
	$politicians_array = json_decode($politicians_json, true);

	$table_name = $wpdb->prefix . 'refugees';

	foreach($politicians_array as $key => $value) {
		$wpdb->insert(
			$table_name,
			array(
				'id' => $value['id'],
			)
		);
	}
}

function vote1refugees_fetch_politicians_via_tvfy() {
	global $wpdb;
	include('vote1refugees_variables.php');

	$table_name = $wpdb->prefix . 'refugees';

    $wpdb_politicians = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A );

    $tvfy_json = file_get_contents('https://theyvoteforyou.org.au/api/v1/people.json?key=' . $THEYVOTEFORYOU);
    $tvfy_array = json_decode($tvfy_json, true);

    $politicians_array = array();

    foreach($tvfy_array as $tvfy_politician){
        foreach($wpdb_politicians as $wpdb_politician) {
            if($wpdb_politician['id'] == $tvfy_politician['id']) {

            	$politicians_array[] = array('id' => $wpdb_politician['id'],
            		'name' => $tvfy_politician['latest_member']['name']['first'] . " " . $tvfy_politician['latest_member']['name']['last'],
            		'party' => $tvfy_politician['latest_member']['party'],
            		'electorate' => $tvfy_politician['latest_member']['electorate'],
            		'house' => $tvfy_politician['latest_member']['house'],
            		'flag' => $wpdb_politician['flag'],
            		'comment' => $wpdb_politician['comment'],
            		'contact' => $wpdb_politician['contact']);
            }
        }
    }

    return $politicians_array;
}
 
function vote1refugees_install() {
	global $wpdb;

	global $vote1refugees_db_version;
	$vote1refugees_db_version = '0.1';
	$vote1refugees_installed_version = get_option( 'vote1refugees_db_version' );

	$table_name = $wpdb->prefix . "refugees";

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(6) NOT NULL,
		flag tinyint(1),
		comment varchar(260),
		contact varchar(120),
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
	dbDelta( $sql );

	add_option( 'vote1refugees_db_version', $vote1refugees_db_version );

	if ( $vote1refugees_installed_version != $vote1refugees_db_version ) {

		$table_name_refugees = $wpdb->prefix . "refugees";
		$table_name_party = $wpdb->prefix . "party";

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name_refugees (
			id mediumint(6) NOT NULL,
			firstName varchar(120),
			lastName varchar(120),
			electorate varchar(120),
			ballotPos tinyint(1),
			partyID tinyint(1),
			contactNo varchar(120),
			contactEmail varchar(120)
			flag tinyint(1),
			comment varchar(260),
			UNIQUE KEY id (id)
		) $charset_collate;
		CREATE TABLE $table_name_party (
			id mediumint(6) NOT NULL,
			partyName varchar(120),
			UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		dbDelta( $sql );

		update_option( 'vote1refugees_db_version', $vote1refugees_db_version );
	}
}

function vote1refugees_update_db_check() {
	global $vote1refugees_db_version;

	if(get_site_option('vote1refugees_db_version') != $vote1refugees_db_version) {
		vote1refugees_install();
	}
}

add_action( 'plugins_loaded', 'vote1refugees_update_db_check' );

register_activation_hook( __FILE__, 'vote1refugees_install' );