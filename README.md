phplist-fpm-docker
==================

A Dockerized [phpList](https://www.phplist.org/), based on nginx, MariaDB and PHP-FPM (i.e. different from the Apache2-based [official phpList image](https://github.com/phpList/phplist-docker)).

Configuration via environment variables, e.g. from a `.env` file:

- `DB_PASSWORD`: Database password to use - the only mandatory parameter
- `ADMIN_PASSWORD`: Password of the admin user - only used when new phpList database is initialised
- `ADMIN_EMAIL`: Email address of the admin user - only used when new phpList database is initialised
- `ALLOWED_REFERRERS`: Enable referrer check on subscribe pages, comma-separated list of allowed domains (default: off)
- `ALLOWED_ORIGINS`: Enable CORS Allowed-Origin header for Ajax-base subscribe forms - comma-separated list of origins (default: allow any)
- `MAIL_RELAY`: SMTP server to relay outgoing email
- `MAIL_RELAY_PORT`: SMTP server port (default: `25`)
- `BOUNCE_MAILBOX_ADDRESS`: Bounce address, used as envelope-from
- `BOUNCE_MAILBOX_HOST`: POP3 server for bounce address
- `BOUNCE_MAILBOX_USER`: POP3 user name
- `BOUNCE_MAILBOX_PASSWORD`: POP3 password
- `BOUNCE_MAILBOX_PORT`: Connection/port details, see `config_extended.php` for details (default: `995/pop3/ssl`)
- `PHPLIST_TEST_MODE`: enable phpList's test mode, in which no emails are sent and processed bounces are not deleted (default: off)
- `WEB_PORT`: port published on the Docker host (default: `8080`)

Database name and user name can be customised, but defaults are used by the MariaDB container and passed to the PHP container otherwise. These are available as `DB_NAME` and `DB_USER`. `DB_HOST` and `DB_PORT` should not be changed, it points to the MariaDB container.

SQL dumps to initialise the database can be placed in `initdb.d/`, see [Initializing a fresh instance](https://hub.docker.com/_/mariadb/) for details. MariaDB data files will reside in a Docker-managed volume.

The upload and plugin directories reside in volumes.

