<?php
/**
 * This file includes the HTML markup for the domain restriction field.
 *
 * @link       http://www.northstarmarketing.com
 * @since      1.0.0
 *
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/admin/partials
 */

// Get the TLD and domain.
$siwg_urlparts    = parse_url( site_url() );
$siwg_domain      = $siwg_urlparts['host'];
$siwg_domainparts = explode( '.', $siwg_domain );
$siwg_domain      = $siwg_domainparts[ count( $siwg_domainparts ) - 2 ] . '.' . $siwg_domainparts[ count( $siwg_domainparts ) - 1 ];

?>
<input name="siwg_google_domain_restriction" id="siwg_google_domain_restriction" type="text" size="50" value="<?php echo get_option( 'siwg_google_domain_restriction' ); ?>" placeholder="<?php echo $siwg_domain; ?>">
<p class="description">Enter the domain you would like to restrict new users to or leave blank to allow anyone with a google account. (Separate multiple domains with commas)</p>
<p class="description">Entering "<?php echo $siwg_domain; ?>" will only allow Google users with an @<?php echo $siwg_domain; ?> email address to sign up.</p>
