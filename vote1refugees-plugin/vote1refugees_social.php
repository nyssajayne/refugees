<?php $uploads = wp_upload_dir(); ?>

<a class="share_link" href="http://<?php echo do_shortcode('[share_twitter]'); ?>" target="_blank">
	<img class="alignnone size-full wp-image-69" src="<?php echo $uploads['url']; ?>/TW_button.png" alt="Share on Twitter" width="126" height="129" />
</a> 
<a class="share_link" href="http://<?php echo do_shortcode('[share_facebook]'); ?>" target="_blank">
	<img class="alignnone size-full wp-image-67" src="<?php echo $uploads['url']; ?>/FB_button.png" alt="FB_button" width="110" height="122" />
</a> 
<a class="share_link" href="<?php echo do_shortcode('[share_email]'); ?>" target="_blank">
	<img class="alignnone size-full wp-image-68" src="<?php echo $uploads['url']; ?>/GM_button.png" alt="GM_button" width="122" height="125" />
</a>