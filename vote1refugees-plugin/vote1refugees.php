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

function vote1refugees_add_politicians() {
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

function vote1refugees_fetch_politicians() {
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
		'Edit Politicians',
		'Edit Politicians',
		'read',
		'edit_politicians',
		'vote1refugees_add_edit_politicians_page'
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

function vote1refugees_add_edit_politicians_page() {
    ob_start();
	include('vote1refugees_edit_politicians.php');
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

function vote1refugees_shortcode_petition() {
	ob_start();
	include('vote1refugees_petition.php');
	$content = ob_get_clean();
	return $content;
}

add_shortcode( 'petition', 'vote1refugees_shortcode_petition' );

function vote1refugees_shortcode_social() {
	ob_start();
	include('vote1refugees_social.php');
	$content = ob_get_clean();
	return $content;
}

add_shortcode( 'social', 'vote1refugees_shortcode_social' );

function vote1refugees_fetch_petition_id() {
	include('vote1refugees_variables.php');

	$API_KEY = $CHANGE_KEY;
	$REQUEST_URL = 'https://api.change.org/v1/petitions/get_id';
	$PETITION_URL = 'https://www.change.org/p/the-australia-parliament-vote-1-refugees-at-the-2016-australian-federal-election';

	$parameters = array(
		'api_key' => $API_KEY,
		'petition_url' => $PETITION_URL
	);

	$query_string = http_build_query($parameters);
	$final_request_url = "$REQUEST_URL?$query_string";

	$response = file_get_contents($final_request_url);

	$json_response = json_decode($response, true);
	$petition_id = $json_response['petition_id'];
	
	return $petition_id;
}

function vote1refugees_fetch_petition_auth_key() {
	include('vote1refugees_variables.php');

	$api_key = $CHANGE_KEY;
	$secret = $CHANGE_SECRET;
	$petition_id = vote1refugees_fetch_petition_id();

	$host = 'https://api.change.org';
	$endpoint = '/v1/petitions/' . $petition_id . '/auth_keys';
	$request_url = $host . $endpoint;

	$params = array();
	$params['api_key'] = $api_key;
	$params['source_description'] = 'Vote 1 Refugees.'; // Something human readable.
	$params['source'] = 'vote1refugees.com'; // Eventually included in every signature submitted with the auth key obtained with this request.
	$params['requester_email'] = 'nyssajayne@gmail.com'; // The email associated with your API key and Change.org account.
	$params['timestamp'] = gmdate("Y-m-d\TH:i:s\Z", strtotime( '-2 min' )); // ISO-8601-formtted timestamp at UTC
	$params['endpoint'] = $endpoint;

	$query_string_with_secret_and_auth_key = http_build_query($params) . $secret;
	$params['rsig'] = hash('sha256', $query_string_with_secret_and_auth_key);

	$query = http_build_query($params);

	$curl_session = curl_init();
	curl_setopt_array($curl_session, array(
		CURLOPT_POST => 1,
		CURLOPT_URL => $request_url,
		CURLOPT_POSTFIELDS => $query,
		CURLOPT_RETURNTRANSFER => true
  ));
  
  $result = curl_exec($curl_session);
  $result = curl_exec($curl_session);
  $json_response = json_decode($result, true);

  return $json_response['auth_key'];
}

function vote1refugees_fetch_signatures() {
	include('vote1refugees_variables.php');
	
	$api_key = $CHANGE_KEY;
	$secret = $CHANGE_SECRET;
	$petition_id = vote1refugees_fetch_petition_id();

	$host = 'https://api.change.org';
	$endpoint = '/v1/petitions/' . $petition_id;
	$request_url = $host . $endpoint;

	$parameters = array(
		'api_key' => $api_key,
		'petition_url' => $petition_id
	);

	$query_string = http_build_query($parameters);
	$final_request_url = "$request_url?$query_string";
	$response = file_get_contents($final_request_url);
	$json_response = json_decode($response, true);

	return $json_response['signature_count'];
}

function the_action_function() {
	echo vote1refugees_fetch_signatures();
	die();
}

function vote1refugees_display_signature_count() {
	return "<span id=\"signatures\" style=\"display:inline-block\"></span>";
}

add_shortcode( 'fetch_signatures', 'vote1refugees_display_signature_count' );
add_shortcode( 'display_signatures', 'vote1refugees_fetch_signatures' );

function vote1refugees_add_signature() {
	//https://github.com/change/api_docs/blob/master/v1/tutorials/add-signatures-to-a-petition.md
	include('vote1refugees_variables.php');
	
	$api_key = $CHANGE_KEY;
	$secret = $CHANGE_SECRET;

	// Set my authorization key for petition
	$petition_auth_key = vote1refugees_fetch_petition_auth_key();
	$petition_id = vote1refugees_fetch_petition_id();

	// Set up the endpoint and URL.
	$base_url = "https://api.change.org";
	$endpoint = "/v1/petitions/$petition_id/signatures";
	$url = $base_url . $endpoint;

	$email_address;
	$first_name;
	$last_name;
	$address;
	$city;
	$state;
	$postcode;

	if(isset($_POST['email_address'])) {
		$email_address = filter_var($_POST['email_address'], FILTER_SANITIZE_EMAIL);
	}

	if(isset($_POST['first_name'])) {
		$first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);	
	}

	if(isset($_POST['last_name'])) {
		$last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
	}

	if(isset($_POST['address'])) {
		$address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
	}

	if(isset($_POST['city'])) {
		$city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
	}

	if(isset($_POST['state'])) {
		$state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
	}

	if(isset($_POST['postcode'])) {
		$postcode = filter_var($_POST['postcode'], FILTER_SANITIZE_NUMBER_INT);
	}
	
	// Set up the signature parameters.
	$parameters = array();
	$parameters['api_key'] = $api_key;
	$parameters['timestamp'] = gmdate("Y-m-d\TH:i:s\Z", strtotime( '-2 min' )); // ISO-8601-formtted timestamp at UTC
	$parameters['endpoint'] = $endpoint;
	$parameters['source'] = 'vote1refugees.com';
	$parameters['email'] = $email_address;
	$parameters['first_name'] = $first_name;
	$parameters['last_name'] = $last_name;
	$parameters['address'] = $address;
	$parameters['city'] = $city;
	$parameters['state_province'] = $state;
	$parameters['postal_code'] = $postcode;
	$parameters['country_code'] = 'AU';
  
	// Build request signature.
	$query_string_with_secret_and_auth_key = http_build_query($parameters) . $secret . $petition_auth_key;
  
	// Add the request signature to the parameters array.
	$parameters['rsig'] = hash('sha256', $query_string_with_secret_and_auth_key);
  
	// Create the request body.
	$data = http_build_query($parameters);
  
	// POST the parameters to the petition's signatures endpoint.
	$curl_session = curl_init();
	curl_setopt_array($curl_session, array(
		CURLOPT_POST => 1,
		CURLOPT_URL => $url,
		CURLOPT_POSTFIELDS => $data,
		CURLOPT_RETURNTRANSFER => true
	));
  
	$result = curl_exec($curl_session);
	$json_response = json_decode($result, true);

	return $json_response;
}

function vote1refugees_share_twitter() {
	$sharing_text = esc_attr(get_option('sharing_options_twitter'));

	$twitter_link = 'twitter.com/intent/tweet?text=' . $sharing_text . ' ' . get_bloginfo( 'url' );

	//return "<a href=\"" . $twitter_link . "\" target=\"_blank\">Twitter</a>";
	return $twitter_link;
}

add_shortcode( 'share_twitter', 'vote1refugees_share_twitter' );

function vote1refugees_share_facebook() {
	$fb_link = 'www.facebook.com/sharer/sharer.php?u=' . get_bloginfo( 'url' ) . '&title=' . get_bloginfo( 'title' );

	//return "<a href=\"" . $fb_link . "\" target=\"_blank\">Facebook</a>";
	return $fb_link;
}

add_shortcode( 'share_facebook', 'vote1refugees_share_facebook' );

function vote1refugees_share_email(){
	$email_link = 'mailto:%20?subject=' . get_bloginfo( 'title' ) . '&body=' . esc_attr(get_option('sharing_options_email')) . ' ' . get_bloginfo( 'url' );

	return $email_link;
}

add_shortcode( 'share_email', 'vote1refugees_share_email' );

function vote1refugees_register_scripts() {
	wp_enqueue_script( 'check_signatures', plugin_dir_url( __FILE__ ) . 'scripts/ajax.js', array( 'jquery' ) );
	wp_localize_script( 'check_signatures', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

add_action( 'wp_enqueue_scripts', 'vote1refugees_register_scripts' );
add_action( 'wp_ajax_the_ajax_hook', 'the_action_function' );
add_action( 'wp_ajax_nopriv_the_ajax_hook', 'the_action_function' );
 
function vote1refugees_install() {
	global $wpdb;

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

	vote1refugees_add_politicians();
}

register_activation_hook( __FILE__, 'vote1refugees_install' );