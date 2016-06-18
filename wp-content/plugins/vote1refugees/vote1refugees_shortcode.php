<?php  ?>

<form action="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" method="post" id="search_politicians">
<input type="number" id="postcode" name="postcode" />
<input type="submit" /><input type="reset" />
</form>

<?php if(isset($_POST['postcode'])) {
	$politicians_array = vote1refugees_fetch_politicians();
	$postcode = $_POST['postcode'];
	$oa_js = file_get_contents('http://www.openaustralia.org.au/api/getDivisions?key=GUzCcACu8dKPCpZnRkHFogcU&postcode=' . $postcode . '&output=php');
	$oa_php = unserialize($oa_js);

	$flags = array();
	$flags[1] = esc_attr(get_option('flag_description_green'));
	$flags[2] = esc_attr(get_option('flag_description_orange'));
	$flags[3] = esc_attr(get_option('flag_description_red'));
	$flags[4] = esc_attr(get_option('flag_description_unknown'));

	echo "<p>Representatives in " . $postcode . "</p>";

	foreach($politicians_array as $politician) {
		foreach($oa_php as $electorate) {
			if($electorate['name'] == $politician['electorate']) {
				$politician_flag;

				foreach ($flags as $key => $value) {
					if($key == $politician['flag']) {
						$politician_flag = $value;
					}
				}
				echo "<p>" . $politician['name'] . " (" . $politician['party'] . " - " . $politician['house'] . ") in " . $politician['electorate'] . " " . $politician_flag . "</p>";
			}
		}
	}
}