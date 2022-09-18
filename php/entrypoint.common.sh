#
# entrypoint.common.sh - wait for database
#

# database timeout - configured in .env or defaults
[ -n "$DB_WAIT_STEP" ] || DB_WAIT_STEP=5 # seconds
[ -n "$DB_WAIT_MAX" ]  || DB_WAIT_MAX=60 # x WAIT_STEP seconds
[ -n "$DB_PORT" ]      || DB_PORT=3306   # MySQL/MariaDB default

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

