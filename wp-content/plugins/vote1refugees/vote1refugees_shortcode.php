<?php  ?>

<form action="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" method="post" id="search_politicians">
<input type="number" id="postcode" name="postcode" />
<input type="submit" /><input type="reset" />
</form>

<?php if(isset($_POST['postcode'])) {
	$politicians_array = vote1refugees_fetch_candidates();
	$postcode = $_POST['postcode'];

	//Find the electorates
	$oa_js = file_get_contents('http://www.openaustralia.org.au/api/getDivisions?key=GUzCcACu8dKPCpZnRkHFogcU&postcode=' . $postcode . '&output=php');
	$oa_php = unserialize($oa_js);

	$state_js = file_get_contents('http://v0.postcodeapi.com.au/suburbs/' . $postcode . '.json');
	$state_php = json_decode($state_js, true);

	//Find the state and check if there's more than one per postcode
	$states = array();
	$states[] = $state_php[0]['state']['abbreviation'];
	$first_state = $state_php[0]['state']['abbreviation'];

	foreach ($state_php as $state) {
		if(strcmp($first_state, $state['state']['abbreviation']) != 0) {
			$states[] = $state['state']['abbreviation'];
			$first_state = $state['state']['abbreviation'];
		} 
	}

	$flags = array();
	$flags[1] = esc_attr(get_option('flag_description_green'));
	$flags[2] = esc_attr(get_option('flag_description_orange'));
	$flags[3] = esc_attr(get_option('flag_description_red'));
	$flags[4] = esc_attr(get_option('flag_description_unknown'));

	echo "<p>Representatives in " . $postcode . "</p>";

	if(count($oa_php) > 1) {
		echo "<p>Your postcode crosses two or more electorates. Please <a href=\"http://www.aec.gov.au/About_AEC/Contact_the_AEC/index.htm\" target=\"_blank\">contact the AEC</a> to confirm your electorate.</p>";
	}

	if(count($states) > 1) {
		echo "<p>Your postcode crosses two states. Please <a href=\"http://www.aec.gov.au/About_AEC/Contact_the_AEC/index.htm\" target=\"_blank\">contact the AEC</a> to confirm your electorate.</p>";
	}

	echo "<div class=\"postcode_results\">";

	foreach($politicians_array as $politician) {
		//If the candidate's flag has been individually set, use that
		//Else use the party flag
		$flag = $politician['flag'];

		if(($flag == NULL) || ($flag == 4)) {
			$flag = $politician['partyFlag'];
		}
		//House of reps
		foreach($oa_php as $electorate) {
			if($electorate['name'] == $politician['electorate']) {
				$image_link;

				switch ($flag) {
	                case '1':
	                    $image_link = 'green_candidates';
	                    break;
	                case '2':
	                    $image_link = 'red_candidates';
	                    break;
	                case '3':
	                    $image_link = 'amber_candidates';
	                    break;
	                case '4':
	                    $image_link = 'amber_candidates';
	                    break;
	            }

				echo "<span class=\"badge\"><img src=\"" . get_stylesheet_directory_uri() . '/images/' . $image_link . ".png\"></span>";
				echo "<span class=\"name\">";
				echo "<h3>" . $politician['name'] . "</h3>";
				echo "<h4>" . $politician['partyName'] . " in " . $politician['electorate'] . "</h4>";
				echo "</span>";
				if(strlen($politician['partyPos']) > 0) {
					echo "<p>" . stripcslashes($politician['partyPos']) . "</p>";
				}
				if(strlen($politician['partyQuote']) > 0) {
					echo "<p class=\"quote\">&ldquo;" . $politician['partyQuote'] .  "&rdquo; (<a href=\"" . $politician['partyRef'] . "\" target\"_blank\">reference</a>)</p>";
				}
				echo "<hr />";
			}
		}
	}

	//Senate
	foreach($politicians_array as $politician) {
		$flag = $politician['flag'];

		if(($flag == NULL) || ($flag == 4)) {
			$flag = $politician['partyFlag'];
		}

		foreach ($states as $state) {
			if(strcmp($politician['electorate'],$state) == 0) {
				$image_link;

				switch ($flag) {
	                case '1':
	                    $image_link = 'green_candidates';
	                    break;
	                case '2':
	                    $image_link = 'red_candidates';
	                    break;
	                case '3':
	                    $image_link = 'amber_candidates';
	                    break;
	                case '4':
	                    $image_link = 'amber_candidates';
	                    break;
	            }

				echo "<span class=\"badge\"><img src=\"" . get_stylesheet_directory_uri() . '/images/' . $image_link . ".png\"></span>";
				echo "<span class=\"name\">";
				echo "<h3>" . $politician['name'] . "</h3>";
				echo "<h4>" . $politician['partyName'] . " in " . $politician['electorate'] . "</h4>";
				echo "</span>";
				if(strlen($politician['partyPos']) > 0) {
					echo "<p>" . stripcslashes($politician['partyPos']) . "</p>";
				}
				if(strlen($politician['partyQuote']) > 0) {
					echo "<p class=\"quote\">&ldquo;" . $politician['partyQuote'] .  "&rdquo; (<a href=\"" . $politician['partyRef'] . "\" target\"_blank\">reference</a>)</p>";
				}
				echo "<hr />";
			}
		}
	}

	echo "</div>";
}