#!/usr/bin/env bash

# ===============================================================================
# Script to install PHPUnit in the Local by Flywheel Mac app
# These packages are installed
# 
#     PHPUnit, git, subversion, composer, curl and wget
# 
# The $WP_CORE_DIR and $WP_TESTS_DIR environment variables are added to the ~/.bashrc file 
# 
# These directories are used by plugins that have their unit tests scaffolded by WP-CLI. 
# VVV also adds these as enviroment variables by default.
# 
# WordPress and the WP_UnitTestCase are installed in these directories for use by PHPUnit.
# A database is created for WordPress with these credentials:
#    db_name: wordpress_test
#    db_user: root
#    db_pass: root
# 
# You only have to run this script once. PHPUnit (and the other packages) 
# are still available next time you ssh into your site.
# 
# To update packages, WordPress and the WP_UnitTestCase re-run this script.
# 
# Note: This script doesn't install the packages globally in the Local by Flywheel app
# Packages are only installed for the site where you've run this script.
# ===============================================================================


# ===============================================================================
# Instructions
# 
# 1 - Download this file (setup-phpunit.sh) inside your site's /app folder
# curl -o setup-phpunit.sh https://gist.githubusercontent.com/keesiemeijer/a888f3d9609478b310c2d952644891ba/raw/
# 
# 2 - Right click your site in the Local App and click Open Site SSH
# A new terminal window will open
# 
# 3 - Go to your site's /app folder:
# cd /app
# 
# 4 - Run this script
# bash setup-phpunit.sh
# 
# 5 - Reload the .bashrc file
# source ~/.bashrc
# ===============================================================================

if ! ping -c 3 --linger=5 8.8.8.8 >> /dev/null 2>&1; then
	# Bail if there is no internet connection
	printf "Could not install packages\n"
	printf "No network connection detected\n\n"
	exit 1
fi

# Re-synchronize the package index files from their sources
apt-get update -y

# Install packages.
apt-get install -y wget subversion curl php5-cli git

# Install composer
if [[ "/usr/local/bin/composer" != $(which composer) ]]; then
	curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
	if [[ -f "$HOME/.bashrc" ]]; then
		printf "Adding .composer/vendor/bin to the PATH\n"
		echo 'export PATH="$PATH:$HOME/.composer/vendor/bin"' >> "$HOME/.bashrc"
	fi
else
	printf "Updating composer\n"
	composer self-update
fi

# Install PHPUnit 5.7 instead of version 6 and up
# See ticket https://core.trac.wordpress.org/ticket/39822
if [[ "/usr/local/bin/phpunit" != $(which phpunit) ]]; then
	wget https://phar.phpunit.de/phpunit-5.7.19.phar
	chmod +x phpunit-5.7.19.phar
	mv phpunit-5.7.19.phar /usr/local/bin/phpunit
else
	printf "phpunit is already installed\n" 
fi

if [[ -f "$HOME/.bashrc" ]]; then
	if [[ -z "${WP_TESTS_DIR}" ]]; then
		printf "Setting WP_TESTS_DIR environment variable\n" 
		echo 'export WP_TESTS_DIR=/tmp/wordpress-tests-lib' >> "$HOME/.bashrc"
	fi
	if [[ -z "${WP_CORE_DIR}" ]]; then
		printf "Setting WP_CORE_DIR environment variable\n" 
		echo 'export WP_CORE_DIR=/tmp/wordpress' >> "$HOME/.bashrc"
	fi

	# Get the variables.
	source "$HOME/.bashrc"
fi

if [[ -z "$WP_CORE_DIR" || -z "$WP_TESTS_DIR" ]]; then
	printf "Could not find the WordPress directories used by PHPUnit\n"
	exit 1
fi

if [[ -d "$WP_CORE_DIR" && -d "$WP_TESTS_DIR" ]]; then

	# Get the latest WordPress version
	latest=$(wget -q -O - "http://api.wordpress.org/core/version-check/1.5/" | head -n 4 | tail -n 1);

	if [[ -z "$latest" ]]; then
		printf "Could not get WordPress version from api.wordpress.org\n"
		exit 1
	fi

	# Update WordPress and WP_UnitTestCase to the latest version.

	if wget --spider "https://wordpress.org/wordpress-$latest.tar.gz" >/dev/null 2>&1; then
		printf "Updating WordPress to %s \n" "$latest"
		wget -nv -O "/tmp/wordpress.tar.gz" "https://wordpress.org/wordpress-$latest.tar.gz"
		tar --strip-components=1 -zxmf "/tmp/wordpress.tar.gz" -C "$WP_CORE_DIR"
	else
		printf "Could not download %s\n" "https://wordpress.org/wordpress-$latest.tar.gz\n"
	fi

	if [[ -f "$WP_TESTS_DIR/wp-tests-config.php" ]]; then
		# Move the config outside the $WP_TESTS_DIR dir to Update WP_UnitTestCase.
		mv "$WP_TESTS_DIR/wp-tests-config.php" "/tmp/wp-tests-config.php"
	fi

	if wget --spider  "https://develop.svn.wordpress.org/tags/$latest/tests/phpunit/includes/" >/dev/null 2>&1; then
		printf "Updating WP_UnitTestCase %s\n" "$latest"
		svn export --quiet --force "https://develop.svn.wordpress.org/tags/$latest/tests/phpunit/includes/" "$WP_TESTS_DIR/includes"
		svn export --quiet --force "https://develop.svn.wordpress.org/tags/$latest/tests/phpunit/data/" "$WP_TESTS_DIR/data"
		if [[ -f "/tmp/wp-tests-config.php" ]]; then
			# Move the config back.
			cp "/tmp/wp-tests-config.php" "$WP_TESTS_DIR/wp-tests-config.php"
		fi
	else
		printf "Could not download %s\n" "https://develop.svn.wordpress.org/tags/$latest/tests/phpunit/includes/\n"
	fi

else
	# Install WordPress and WP_UnitTestCase.
	printf "Installing WordPress and WP_UnitTestCase\n"
	wget -O "/tmp/install-wp-tests.sh" "https://raw.githubusercontent.com/wp-cli/scaffold-command/master/templates/install-wp-tests.sh"

	if [[ -f "/tmp/install-wp-tests.sh" ]]; then
		bash "/tmp/install-wp-tests.sh" "wordpress_test" "root" "root" "localhost" "latest" "true"
	else
		printf "Could not install WordPress and WP_UnitTestCase\n"
	fi
fi

# Install database if it doesn't exist
printf "Checking if database wordpress_test exists\n"

database=$(mysqlshow --user=root --password=root wordpress_test| grep -v Wildcard | grep -o wordpress_test)
if ! [[ "wordpress_test" = "$database" ]]; then
	printf "Creating database wordpress_test\n"
	mysqladmin create wordpress_test --user=root --password=root --host=localhost
else
	printf "Database wordpress_test already exists\n"
fi

if [[ -f "$WP_TESTS_DIR/wp-tests-config.php" ]]; then
	# VVV has the tests config outside the $WP_TESTS_DIR dir.
	cp "$WP_TESTS_DIR/wp-tests-config.php" "/tmp/wp-tests-config.php"
fi

printf "\nFinished setting up packages\n\n"