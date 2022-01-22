# Sign In With Google
This plugin gives your users the ability to sign in with their G Suite account. If they don't have a user account on your site and they try to sign in, an account will be created for them (if their email address domain is listed in the restricted domains).
This is great for Agencies or sites that have lots of users and need a way to make signing in a quick and painless process.

## Features
* Show/Hide the "Log In with Google" button on the login form
* Restrict user logins to a specific domain
* If a user is already logged in to Google, they will be automatically redirected without much fuss
* Connect existing user accounts with a Google account
* A custom login parameter can be added to the URL of the site as a "hidden" login. For example adding `?mysitename_login` to your url (for example: `https://mysitename.com/?mysitename_login`) will log in the user, or redirect them to log in with Google.

## Warning

If you leave the "Log in with Google" button shown on the login form, make sure that you are comfortable with the new user role that is set. Since this plugin creates user accounts for those that do not already have an account on the site, use domain restriction or set a low level role to the users.

## Installation
Sign in with Google uses OAuth 2.0 and requires that an application be created in Google's developer console. Unfortunately, there is no way around this, but I'll try to make this process as simple as possible.

Navigate to [Google's Developer Console](https://console.developers.google.com) and click the dropdown to create a new project.

Once your project has been created, navigate to the OAuth consent screen section and choose a User Type that matches how you will be utilizing the plugin. (Gmail users are only able to select "External")
![image](https://user-images.githubusercontent.com/6947218/71093623-90a86000-2177-11ea-9d3e-8c982a319051.png)

Next, fill out the required information for the consent screen (the only required information here is the "Application Name")

Once you have added your application name, navigate to the Credentials section and create an OAuth client ID (This is where you get a client ID and secret to enter in the plugin settings page)
![image](https://user-images.githubusercontent.com/6947218/71094864-af0f5b00-2179-11ea-9ee3-ba6b245cb550.png)

Next, choose "Web application", Enter a name for the Credential (something like "[Company Name] Google Sign In" would be good) then in the "Authorized redirect URIs" enter in the following replacing "DOMAIN.TLD" with your site's domain and whether you're using SSL or not (https or http).
```
https://DOMAIN.TLD/?google_response
```

Once you have added the domain, click save and you should see a section at the top that lists the Client ID and Client secret.

![image](https://user-images.githubusercontent.com/6947218/71096813-fa773880-217c-11ea-8ff2-30bc83cd1c59.png)

Return to the plugin settings in your site's admin. Add the Client ID and Client Secret you just created.

![image](https://user-images.githubusercontent.com/6947218/71097139-85f0c980-217d-11ea-89da-e2a66fe49047.png)

Verify that you can log in with Google.

## Contribution

All active plugin development is handled here. Feel free to submit pull requests and issues.

To run tests:
1. Clone the repository
2. Install dependencies
```sh
composer install
```
1. Install and Run PHPUnit tests
```sh
bash bin/install-wp-tests.sh wordpress_test root '' localhost latest

./vendor/bin/phpunit
```
