#!/bin/sh
#
# entrypoint.sh - make sure upload dir is writable, initialise DB, upgrade DB
#

UPLOADIMAGES_DIR=/var/www/html/upload
PLUGIN_DIR=/var/www/html/admin/plugins
PLUGIN_IMAGE_DIR=/opt/phplist/plugins

# create upload & plugin dirs if needed
mkdir -p $UPLOADIMAGES_DIR $PLUGIN_DIR

# update pre-installed plugins from image
echo -n "Updating pre-installed plugins..."
rsync -au $PLUGIN_IMAGE_DIR/ $PLUGIN_DIR/
echo " done."

# make sure that upload & plugin dirs are writable for www-data
echo -n "Making upload and plugin directories writable..."
chown -R www-data.www-data $UPLOADIMAGES_DIR
chown www-data.www-data $PLUGIN_DIR
chmod 0775 $UPLOADIMAGES_DIR $PLUGIN_DIR
echo " done."

# common entrypoint will wait for DB to come up
. /bin/entrypoint.common.sh

# initialise new PHPlist instance
phplist -pinitialise

# upgrade database version
phplist -pupgrade

exec /usr/local/bin/docker-php-entrypoint "$@"

