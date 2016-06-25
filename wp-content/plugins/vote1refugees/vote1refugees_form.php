<?php
	require_once('../../../wp-config.php');
	global $wpdb;

	$table_name = $wpdb ->prefix . "candidates";


	if(isset($_POST['flag'])) {
		foreach($_POST['flag'] as $id=>$flag) {
			$phone = $_POST['phone_' . $id];
			$email = $_POST['email_' . $id];
			$comment = $_POST['comment_' . $id];

			$wpdb->update(
				$table_name,
				array(
					'id' 			=> $id,
					'flag'			=> $flag,
					'contactNo' 	=> $phone,
					'contactEmail' 	=> $email,
					'comment'		=> $comment
				),
				array(
					'id' 			=> $id
				),
				array('%d', '%d', '%s', '%s', '%s'),
				array('%d')
			);
		}
	}

	header("Location: {$_SERVER['HTTP_REFERER']}");
	exit;