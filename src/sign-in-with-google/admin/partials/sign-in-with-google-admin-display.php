<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.northstarmarketing.com
 * @since      1.0.0
 *
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/admin/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
	<h2>Sign In With Google Settings</h2>
	<form method="post" action="options.php">
		<?php settings_fields( 'siwg_settings' ); ?>
		<?php do_settings_sections( 'siwg_settings' ); ?>
		<p class="submit">
			<input name="submit" type="submit" id="submit" class="button-primary" value="Save Changes" />
		</p>
	</form>
</div>
