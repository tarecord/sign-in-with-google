/**
 * All of the code for your public-facing JavaScript source
 * should reside in this file.
 *
 * @package Sign In With Google
 */

window.addEventListener( 'load', function () {
	const form = document.querySelector( '#loginform' );
	const button = document.querySelector( '#sign-in-with-google-container' );
	form.parentNode.insertBefore(button, form.nextSibling);
});
