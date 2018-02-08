=== Sign In With Google ===
Contributors: tarecord, chrismkindred
Tags: Google, sign in, users, registration, register, Google Apps, G Suite, OAuth
Requires at least: 4.0
Tested up to: 4.9.2
Stable tag: 1.0.2
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a "Sign in with Google" option to the login form so users can sign in with their G Suite account.

== Description ==

This plugin gives your users the ability to sign in with their G Suite account. If they don't have a user account on your site and they try to sign in, an account will be created for them (if their email address domain is listed in the restricted domains).
This is great for Agencies or sites that have lots of users and need a way to make signing in a quick and painless process.

= Features =
* Show/Hide the "Sign In with Google" button on the login form
* Restrict user logins to a specific domain
* If a user is already logged in to Google, they will be automatically redirected without much fuss
* A custom login parameter can be added to the URL of the site as a "hidden" login. For example adding `?mysitename_login` to your url (for example: `https://mysitename.com/?mysitename_login`) will log in the user, or redirect them to log in with Google.

= WARNING =

If you leave the "Sign in with Google" button shown on the login form, make sure that you are comfortable with the new user role that is set. Since this plugin creates user accounts for those that do not already have an account on the site, use domain restriction or set a low level role to the users.

== Development ==

Active plugin development is handled on [Github](https://www.github.com/NorthStarMarketing/google-sign-in). Bugs and issues will be tracked and handled there.

== Installation ==

1. Download and extract plugin
2. Upload `google-sign-in` folder to the `/wp-content/plugins/` directory
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

1. The login form with the "Sign in with Google" button added.
2. This is the second screen shot

== Changelog ==

= 1.0.2 =
* Rename plugin file to match directory

= 1.0.1 =
* Fixed bug causing a redirect loop if using the button on the login page

= 1.0 =
* Initial Release
