#!/bin/sh
#
# entrypoint.cron.sh - for cron container
#

# cron service only needs to wait for DB
. /bin/entrypoint.common.sh

exec "$@"

