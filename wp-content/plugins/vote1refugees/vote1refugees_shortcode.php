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

	if(count($states) > 1) {
		echo "<p>Your postcode crosses two states. Please <a href=\"http://www.aec.gov.au/About_AEC/Contact_the_AEC/index.htm\" target=\"_blank\">contact the AEC</a> to confirm your electorate.</p>";
	}

	echo "<div class=\"postcode_results\">";

	//House of Reps
	if(count($oa_php) > 1) {
		echo "<p>Your postcode, " . $postcode . ", crosses two or more electorates. Please <a href=\"http://www.aec.gov.au/About_AEC/Contact_the_AEC/index.htm\" target=\"_blank\">contact the AEC</a> to if you're unsure of your electorate.</p>";
		echo "<p>";

		foreach ($oa_php as $electorate) {
			echo "Check candidates in <a href=\"#" . $electorate['name'] . "\">" . $electorate['name'] . "</a><br />";
		}

		echo "</p>";
	}
	else {
		echo "<h3>" . $postcode . " means your electorate is " . $oa_php[0]['name'] . "</h3>";
	}

	foreach($oa_php as $electorate) {
		if(count($oa_php) > 1) {
			echo "<h3 id=\"" . $electorate['name'] . "\">" . $electorate['name'] . "</h3>";
		}

		foreach($politicians_array as $politician) {
			//If the candidate's flag has been individually set, use that
			//Else use the party flag
			$flag = $politician['flag'];

			if(($flag == NULL) || ($flag == 4)) {
				$flag = $politician['partyFlag'];
			}

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
				echo "<h4>" . $politician['name'] . "</h4>";
				echo "<h5>" . $politician['partyName'] . "</h5>";
				echo "<p class=\"contact\">";
				if(strlen($politician['phone']) > 0) {
					echo "<span class=\"bold\">ph: </span>" . $politician['phone'] . "<br />";
				}
				if(strlen($politician['email']) > 0) {
					echo "<span class=\"bold\">@: </span>" . $politician['email'];
				}
				echo "</p>";
				echo "</span>";
				echo "<div class=\"clearfix\"></div>";
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
				if(strlen($politician['phone']) > 0) {
					echo "<p><span class=\"bold\">Phone number:</span> " . $politician['phone'] . "<br />";
				}
				if(strlen($politician['email']) > 0) {
					echo "<p><span class=\"bold\">Email address:</span> " . $politician['email'] . "<br />";
				}
				echo "<hr />";
			}
		}
	}

	echo "</div>";
}