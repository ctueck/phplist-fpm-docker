FROM nginx:alpine

# arguments
ARG PHPLIST_VERSION=3.6.8
ARG PHPLIST_HASH=bb82f62a66f09f79542e6c81cc8ffc6271bef2a8f70c9cdc3ae9237a8455f100

# get PHPlist, move to /var/html/www
# (except plugins, they rest on a volume populated by the php container)
RUN apk add --no-cache wget

RUN cd /tmp \
    && wget -O phplist.tar.gz "https://sourceforge.net/projects/phplist/files/phplist/$PHPLIST_VERSION/phplist-$PHPLIST_VERSION.tgz/download" \
    && echo "$PHPLIST_HASH  phplist.tar.gz" | sha256sum -c - \
    && tar --no-same-owner --no-same-permissions -xzf /tmp/phplist.tar.gz \
    && rm -f phplist.tar.gz \
    && mv phplist-$PHPLIST_VERSION phplist \
    && cd phplist \
    && rm -f public_html/lists/config/config.php \
    && rm -rf public_html/lists/admin/plugins \
    && mkdir -p /var/www && rm -rf /var/www/html \
    && mv public_html/lists /var/www/html \
    && mkdir -p /var/www/html/admin/plugins /var/www/html/upload \
    && cd /tmp \
    && rm -rf phplist

# add config file
COPY nginx.conf /etc/nginx

