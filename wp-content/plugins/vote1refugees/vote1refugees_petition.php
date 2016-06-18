<?php
	$result_code;

	if(!empty($_POST['submit'])) {
		$result_code = vote1refugees_add_signature();
		
		if(strcmp($result_code['result'], 'failure') == 0) { ?>
			<form action="<?php echo esc_attr($_SERVER['REQUEST_URI']) . '#sign_petition'; ?>" method="post" id="sign_petition">
				<p><?php if(isset($result_code['result'])) { echo "There was an error: " . $result_code['messages'][0]; } ?></p>

				<p>Email Address<span class="required">*</span><br />
				<input type="email" id="email_address" name="email_address" value="<?php echo filter_var($_POST['email_address'], FILTER_SANITIZE_EMAIL); ?>" /></p>

				<p>First Name<span class="required">*</span><br />
				<input type="text" id="first_name" name="first_name" value="<?php echo filter_var($_POST['first_name'], FILTER_SANITIZE_STRING); ?>" /></p>
				
				<p>Last Name<span class="required">*</span><br />
				<input type="text" id="last_name" name="last_name" value="<?php echo filter_var($_POST['last_name'], FILTER_SANITIZE_STRING); ?>" /></p>

				<p>Address<br />
				<input type="text" id="address" name="address" value="<?php echo filter_var($_POST['address'], FILTER_SANITIZE_STRING); ?>" /></p>

				<p>City<span class="required">*</span><br />
				<input type="text" id="city" name="city" value="<?php echo filter_var($_POST['city'], FILTER_SANITIZE_STRING); ?>" /></p>

				<p>State<span class="required">*</span><br />
				<input type="text" id="state" name="state" value="<?php echo filter_var($_POST['state'], FILTER_SANITIZE_STRING); ?>" /></p>

				<p>Postcode<span class="required">*</span><br />
				<input type="number" id="postcode" name="postcode" value="<?php echo filter_var($_POST['postcode'], FILTER_SANITIZE_NUMBER_INT); ?>" /></p>

				<p>By signing, you accept <a href="http://www.change.org" target="_blank">Change.org</a>’s <a href="https://www.change.org/policies/terms-of-service" target="_blank">terms of service</a> and <a href="https://www.change.org/policies/privacy" target="_blank">privacy policy</a>, and agree to receive occasional emails about campaigns on Change.org. You can unsubscribe at any time.</p>

				<p><input type="submit" id="submit" name="submit" value="submit" /></p>
			</form>
		<?php }
		else { ?>
			<script type="text/javascript">
				window.location = "<?php bloginfo( 'url' ); ?>/success/";
			</script> 
		<?php }
	}
	else { ?>
<form action="<?php echo esc_attr($_SERVER['REQUEST_URI']) . '#sign_petition'; ?>" method="post" id="sign_petition">
	<p>Email Address<span class="required">*</span><br />
	<input type="text" id="email_address" name="email_address" /></p>
	
	<p>First Name<span class="required">*</span><br />
	<input type="text" id="first_name" name="first_name" /></p>
	
	<p>Last Name<span class="required">*</span><br />
	<input type="text" id="last_name" name="last_name" /></p>
	
	<p>Address<br />
	<input type="text" id="address" name="address" /></p>
	
	<p>City<span class="required">*</span><br />
	<input type="text" id="city" name="city" /></p>
	
	<p>State<span class="required">*</span><br />
	<input type="text" id="state" name="state" /></p>
	
	<p>Postcode<span class="required">*</span><br />
	<input type="text" id="postcode" name="postcode" /></p>

	<p>By signing, you accept <a href="http://www.change.org" target="_blank">Change.org</a>’s <a href="https://www.change.org/policies/terms-of-service" target="_blank">terms of service</a> and <a href="https://www.change.org/policies/privacy" target="_blank">privacy policy</a>, and agree to receive occasional emails about campaigns on Change.org. You can unsubscribe at any time.</p>
	
	<p><input type="submit" id="submit" name="submit" value="submit" /></p>
</form>
	<?php }