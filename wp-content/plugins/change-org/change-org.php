<?php /*
Plugin Name: change.org for Wordpress
Plugin URI: http://www.shoesandblues.com
Description: Let users sign your change.org petition straight from your Wordpress site
Version: 0.1
Author: Nyssa Jayne
Author URI: http://www.shoesandblues.com
*/

function change_org_fetch_petition_id() {
	include('change-org_variables.php');

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

function change_org_fetch_petition_auth_key() {
	include('change-org_variables.php');

	$api_key = $CHANGE_KEY;
	$secret = $CHANGE_SECRET;
	$petition_id = change_org_fetch_petition_id();

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

function change_org_fetch_signatures() {
	include('change-org_variables.php');

	$api_key = $CHANGE_KEY;
	$secret = $CHANGE_SECRET;
	$petition_id = change_org_fetch_petition_id();

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

function change_org_the_action_function() {
	echo change_org_fetch_signatures();
	die();
}

function change_org_display_signature_count() {
	return "<span id=\"signatures\" style=\"display:inline-block\"></span>";
}

add_shortcode( 'fetch_signatures', 'change_org_display_signature_count' );
add_shortcode( 'display_signatures', 'change_org_fetch_signatures' );

function change_org_add_signature() {
	//https://github.com/change/api_docs/blob/master/v1/tutorials/add-signatures-to-a-petition.md
	include('change-org_variables.php');

	$api_key = $CHANGE_KEY;
	$secret = $CHANGE_SECRET;

	// Set my authorization key for petition
	$petition_auth_key = change_org_fetch_petition_auth_key();
	$petition_id = change_org_fetch_petition_id();

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

function change_org_register_scripts() {
	wp_enqueue_script( 'check_signatures', plugin_dir_url( __FILE__ ) . 'scripts/ajax.js', array( 'jquery' ) );
	wp_localize_script( 'check_signatures', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

add_action( 'wp_enqueue_scripts', 'change_org_register_scripts' );
add_action( 'wp_ajax_the_ajax_hook', 'change_org_the_action_function' );
add_action( 'wp_ajax_nopriv_the_ajax_hook', 'change_org_the_action_function' );

function change_org_shortcode_petition() {
	ob_start();
	include('change-org_petition.php');
	$content = ob_get_clean();
	return $content;
}

add_shortcode( 'petition', 'change_org_shortcode_petition' );