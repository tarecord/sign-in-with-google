# Sign In With Google
[![Build Status](https://travis-ci.org/NorthStarMarketing/sign-in-with-google.svg?branch=master)](https://travis-ci.org/NorthStarMarketing/sign-in-with-google)

This plugin gives your users the ability to sign in with their G Suite account. If they don't have a user account on your site and they try to sign in, an account will be created for them (if their email address domain is listed in the restricted domains).
This is great for Agencies or sites that have lots of users and need a way to make signing in a quick and painless process.

## Features
* Show/Hide the "Sign In with Google" button on the login form
* Restrict user logins to a specific domain
* If a user is already logged in to Google, they will be automatically redirected without much fuss
* A custom login parameter can be added to the URL of the site as a "hidden" login. For example adding `?mysitename_login` to your url (for example: `https://mysitename.com/?mysitename_login`) will log in the user, or redirect them to log in with Google.

## Warning

If you leave the "Sign in with Google" button shown on the login form, make sure that you are comfortable with the new user role that is set. Since this plugin creates user accounts for those that do not already have an account on the site, use domain restriction or set a low level role to the users.

## Contribution

All active plugin development is handled here. Feel free to submit pull requests and issues.
