<?php
	include('vote1refugees_variables.php');

	if(isset($_POST['postcode'])) {
		$postcode = $_POST['postcode'];
		$oa_js = file_get_contents('http://www.openaustralia.org.au/api/getDivisions?key=' . $OA_KEY . '&postcode=' . $postcode . '&output=php');
		$oa_php = unserialize($oa_js);

		unset($_POST['postcode']);
	}

	header("Location: {$_SERVER['HTTP_REFERER']}");
	exit;