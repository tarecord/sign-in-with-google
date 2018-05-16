<?php
/**
 * This file includes the HTML markup for the default role select.
 *
 * @link       http://www.northstarmarketing.com
 * @since      1.0.0
 *
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/admin/partials
 */

?>

<select name="siwg_google_user_default_role" id="siwg_google_user_default_role">
	<?php
	$siwg_roles = get_editable_roles();
	foreach ( $siwg_roles as $key => $value ) : // phpcs:ignore
		$siwg_selected = '';
		if ( get_option( 'siwg_google_user_default_role', 'subscriber' ) == $key ) {
			$siwg_selected = 'selected';
		}
	?>

		<option value="<?php echo $key; ?>" <?php echo $siwg_selected; ?>><?php echo $value['name']; ?></option>

	<?php endforeach; ?>

</select>
