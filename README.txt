=== Sign In With Google ===
Contributors: tarecord, chrismkindred
Tags: Google, sign in, users, registration, register, Google Apps, G Suite, OAuth
Requires at least: 4.8.1
Tested up to: 6.4.3
Stable tag: 1.8.0
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a "Log in with Google" option to the login form so users can sign in with their G Suite account.

== Description ==

This plugin gives your users the ability to sign in with their G Suite account. If they don't have a user account on your site and they try to sign in, an account will be created for them (if their email address domain is listed in the restricted domains).
This is great for Agencies or sites that have lots of users and need a way to make signing in a quick and painless process.

= Features =
* Show/Hide the "Log In with Google" button on the login form
* Restrict user logins to a specific domain
* If a user is already logged in to Google, they will be automatically redirected without much fuss
* Connect existing user accounts with a Google account
* A custom login parameter can be added to the URL of the site as a "hidden" login. For example adding `?mysitename_login` to your url (for example: `https://mysitename.com/?mysitename_login`) will log in the user, or redirect them to log in with Google.

= WARNING =

If you leave the "Log in with Google" button shown on the login form, make sure that you are comfortable with the new user role that is set. Since this plugin creates user accounts for those that do not already have an account on the site, use domain restriction or set a low level role to the users.

== Development ==

Active plugin development is handled on [Github](https://www.github.com/tarecord/sign-in-with-google). Bugs and issues will be tracked and handled there.

== Installation ==

1. Download and extract plugin
2. Upload `sign-in-with-google` folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Where can I get a Client ID and Client Secret? =

Due to the nature of Google's OAuth 2.0 security protocols, you will need to register an application with them to access the required APIs. (Don't worry if you do not understand, the process is fairly straight forward)

You will need to sign in to the [Google Developer Console](https://console.developers.google.com)

1. Go to the API Console.
2. From the projects list, select a project or create a new one.
3. If the APIs & services page isn't already open, open the console left side menu and select APIs & services.
4. On the left, click Credentials.
5. Click New Credentials, then select OAuth client ID.
6. Add the following in the "Authorized redirect URIs" section: `https://YOURDOMAIN.TLD/?google_response`
7. Click save and you may now use "Sign in with Google".

== Screenshots ==

1. The login form with the "Log in with Google" button added.
2. This is the second screen shot

== Changelog ==

= 1.8.0 =
* fixed an incorrect use of the 'login_redirect' core filter.
* Added siwg_password_length filter to allow adjusting the new user generated password length.
* Added a global function to get the authentication url in template files.

= 1.7.0 =
* Add setting that allows users, who have an approved domain, to bypass the "Anyone can register" checkbox on the General Settings page.
* Update to use login_redirect filter after user authenticates.
* Update changelog to reflect new ownership.

= 1.6.0 =
* Fixed overflow issue with import/export meta boxes.
* Fixed issue with users being registered when user registration was disabled in site settings.
* Added template functions so the log in button can be used in themes and plugins (and custom login forms).

= 1.5.2 =
* Added more useful error messages when users aren't logged in properly.
* New user accounts now use email address as user_login to prevent unique username issues.
* Fixed bug with domain restriction not evaluating correctly.
* Fixed bug where user was redirected if only signed into a single google account with a domain not in the restricted domains list.
* Added Internationalization (I18n).

= 1.5.1 =
* Fixed an issue where leaving the "Restrict To Domain" field blank would cause issues logging some issues in.
* Fixed improper implementation of `apply_filters`
* Replaced deprecated `get_usermeta` function with `get_user_meta`

= 1.5.0 =
* Fixed failures with plugins that rely on the 'wp_login' action

= 1.4.0 =
* Updated verbiage of button to "Log In With Google".
* Added ability to link/unlink Google account in user profile.

= 1.3.0 =
* Added WP-CLI functionality

= 1.2.2 =
* Fixed bug where user was not redirected properly.

= 1.2.1 =
* Removed Google+ API Dependency
* Removed call to css and js files that don't exist.
* Added settings import and export
* Updated class and file naming
* Fixed bug where the user was not redirected properly after login.

= 1.0.3 =
* Fixed bug causing "Settings" link not to show on plugins page
* Cleaned up error logs

= 1.0.2 =
* Renamed plugin file to match directory

= 1.0.1 =
* Fixed bug causing a redirect loop if using the button on the login page

= 1.0 =
* Initial Release
