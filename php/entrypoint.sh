#!/bin/sh
#
# entrypoint.sh - make sure upload dir is writable, initialise DB, upgrade DB
#

UPLOADIMAGES_DIR=/var/www/html/upload
PLUGIN_DIR=/var/www/html/admin/plugins
PLUGIN_IMAGE_DIR=/opt/phplist/plugins

# database timeout - configured in .env or defaults
[ -n "$DB_WAIT_STEP" ] || DB_WAIT_STEP=5 # seconds
[ -n "$DB_WAIT_MAX" ]  || DB_WAIT_MAX=60 # x WAIT_STEP seconds
[ -n "$DB_PORT" ]      || DB_PORT=3306   # MySQL/MariaDB default

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

# wait for DB to come up
echo -n "Waiting for database..."
DB_WAIT_COUNT=0
while ! nc -z $DB_HOST $DB_PORT ; do
    DB_WAIT_COUNT=$((DB_WAIT_COUNT+1))
    if [ $DB_WAIT_COUNT -gt $DB_WAIT_MAX ]; then
        echo " timeout."
        exit 1
    fi
    echo -n .
    sleep $DB_WAIT_STEP
done
echo " up."

# initialise new PHPlist instance
phplist -pinitialise

# upgrade database version
phplist -pupgrade

exec /usr/local/bin/docker-php-entrypoint "$@"

