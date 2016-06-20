<?php
	require_once('../../../wp-config.php');
	global $wpdb;

	$table_name = $wpdb ->prefix . "party";

	if(isset($_POST['flag'])) {
		foreach($_POST['flag'] as $id=>$flag) {
			$comment = filter_var($_POST['comment_' . $id], FILTER_SANITIZE_STRING);
			$quote = filter_var($_POST['quote_' . $id], FILTER_SANITIZE_STRING);
			$reference = filter_var($_POST['reference_' . $id], FILTER_SANITIZE_STRING);

			$wpdb->update(
				$table_name,
				array(
					'id' 			=> $id,
					'flag'			=> $flag,
					'comment'		=> $comment,
					'quote'			=> $quote,
					'reference'		=> $reference
				),
				array(
					'id' 			=> $id
				),
				array('%d', '%s', '%s', '%s', '%s', '%s'),
				array('%d')
			);
		}
	}

	header("Location: {$_SERVER['HTTP_REFERER']}");
	exit;