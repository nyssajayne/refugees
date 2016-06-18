<?php
	require_once('../../../wp-config.php');
	global $wpdb;

	$table_name = $wpdb ->prefix . "refugees";

	if(isset($_POST['flag'])) {
		foreach($_POST['flag'] as $id=>$flag) {
			$comment = $_POST['comment_' . $id];
			$contact = $_POST['contact_' . $id];

			$wpdb->update(
				$table_name,
				array(
					'id' 		=> $id,
					'flag'		=> $flag,
					'comment' 	=> $comment,
					'contact' 	=> $contact
				),
				array(
					'id' 		=> $id
				),
				array('%d', '%s', '%s', '%s'),
				array('%d')
			);
		}
	}

	header("Location: {$_SERVER['HTTP_REFERER']}");
	exit;