<?php

/*

* ==============================================================================================================
*
* Adapted from default config.php for Dockerized setup - most values taken from environment variables
*
* If you are interested in tweaking more options, check out the config_extended.php file
* or visit http://resources.phplist.com/system/config
* 
* ** NOTE: To use options from config_extended.php, you need to copy them to this file **
* 
==============================================================================================================

*/

include('getenv-docker.php');

# if test is true (not 0) it will not actually send ANY messages, but display what it would have sent
# this is here, to make sure you edited the config file and mails are not sent "accidentally"
# on unmanaged systems
define('TEST', getenv_docker('PHPLIST_TEST_MODE', false) ? 1 : 0);
define('VERBOSE', getenv_docker('PHPLIST_TEST_MODE', false) ? 1 : 0);

// turn off tracking
define('ALWAYS_ADD_USERTRACK', 0);
define('CLICKTRACK', 0);

// enable referrer check
if (getenv_docker('ALLOWED_REFERRERS')) {
    define('CHECK_REFERRER', true);
    $allowed_referrers = explode(',', getenv_docker('ALLOWED_REFERRERS'));
} else {
    define('CHECK_REFERRER', false);
    $allowed_referrers = [];
}

// for Ajax-based sign-up forms
if (getenv_docker('ALLOWED_ORIGINGS')) {
    define('ACCESS_CONTROL_ALLOW_ORIGINS', getenv_docker('ALLOWED_ORIGINGS'));
}

// don't jump off but ask
define('UNSUBSCRIBE_JUMPOFF', 0);

// If you want to upload images in the editor, you need to specify the location
// of the directory where the images go. This needs to be writable by the webserver,
// and it needs to be in your public document (website) area
// the directory is relative to the webserver root directory
define('UPLOADIMAGES_DIR', '/upload');
define('ADMIN_PROTOCOL', 'https');

// spam block settings
define('USE_SPAM_BLOCK', 1);
define('NOTIFY_SPAM', 1);

// queue should be processed by cron container
define('MANUALLY_PROCESS_QUEUE', 0);

// throttling
define('MAILQUEUE_THROTTLE', getenv_docker('MAILQUEUE_THROTTLE', 0.5));

# PHPlist default is /lists, but this config is for running PHPlist from the webserver root
$pageroot = "";

# what is your Mysql database server hostname
$database_host = getenv_docker('DB_HOST', 'db');

# database port - if not specified, the default (3306) will be used
$database_port = getenv_docker('DB_PORT', null);

# what is the name of the database we are using
$database_name = getenv_docker('DB_NAME', 'phplist');

# what user has access to this database
$database_user = getenv_docker('DB_USER', 'phplist');

# and what is the password to login to control the database
$database_password = getenv_docker('DB_PASSWORD');

# if you have an SMTP server, set it here. Otherwise it will use the normal php mail() function
define('PHPMAILERHOST', getenv_docker('MAIL_RELAY', 'localhost'));
define('PHPMAILERPORT', getenv_docker('MAIL_RELAY_PORT', 25));

/*
==============================================================================================================
* 
* Settings for handling bounces
* 
* This section is OPTIONAL, and not necessary to send out mailings, but it is highly recommended to correctly 
* set up bounce processing. Without processing of bounces your system will end up sending large amounts of
* unnecessary messages, which overloads your own server, the receiving servers and internet traffic as a whole
* 
==============================================================================================================
*/

# Message envelope. 
# 
# This is the address that most bounces will be delivered to
# Your should make this an address that no PERSON reads
# but a mailbox that phpList can empty every so often, to process the bounces

$message_envelope = getenv_docker('BOUNCE_MAILBOX_ADDRESS');

# Handling bounces. Check README.bounces for more info
# This can be 'pop' or 'mbox'
$bounce_protocol = 'pop';

# set this to 0, if you set up a cron to download bounces regularly by using the
# commandline option. If this is 0, users cannot run the page from the web
# frontend. Read README.commandline to find out how to set it up on the
# commandline
define('MANUALLY_PROCESS_BOUNCES', 0);

# when the protocol is pop, specify these three
$bounce_mailbox_host = getenv_docker('BOUNCE_MAILBOX_HOST');
$bounce_mailbox_user = getenv_docker('BOUNCE_MAILBOX_USER');
$bounce_mailbox_password = getenv_docker('BOUNCE_MAILBOX_PASSWORD');

# the "port" is the remote port of the connection to retrieve the emails
# the default should be fine but if it doesn't work, you can try the second
# one. To do that, add a # before the first line and take off the one before the
# second line
$bounce_mailbox_port = getenv_docker('BOUNCE_MAILBOX_PORT', '995/pop3/ssl');

# in test mode, this keeps messages in the mailbox. this is a problem in production,
# because bounces will be counted multiple times, hence only for test mode.
$bounce_mailbox_purge = getenv_docker('PHPLIST_TEST_MODE', false) ? 0 : 1;

# in test mode, this keeps unprocessed messages in the mailbox. In production, these
# are deleted, but they are still downloaded into phpList and can be viewed there
$bounce_mailbox_purge_unprocessed = getenv_docker('PHPLIST_TEST_MODE', false) ? 0 : 1;

# how many bounces in a row need to have occurred for a user to be marked unconfirmed
$bounce_unsubscribe_threshold = 5;

# choose the encryption method for password
# check the extended config for more info
# in most cases, it is fine to leave this as it is
define('ENCRYPTION_ALGO', 'sha256');

