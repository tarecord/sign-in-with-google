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

<div class="metabox-holder">
	<div class="postbox">
		<h3><span><?php _e( 'Export Settings', 'siwg'  ); ?></span></h3>
		<div class="inside">
			<p><?php _e( 'Export the plugin settings for this site as a .json file.', 'siwg' ); ?></p>
			<form method="post">
				<p><input type="hidden" name="siwg_action" value="export_settings" /></p>
				<p>
					<?php wp_nonce_field( 'siwg_export_nonce', 'siwg_export_nonce' ); ?>
					<?php submit_button( __( 'Export', 'siwg' ), 'secondary', 'submit', false ); ?>
				</p>
			</form>
		</div><!-- .inside -->
	</div><!-- .postbox -->
</div><!-- .metabox-holder -->
