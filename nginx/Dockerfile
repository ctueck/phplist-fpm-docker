FROM nginx:alpine

# arguments
ARG PHPLIST_VERSION=3.6.12
ARG PHPLIST_HASH=9e9e510cc3283cc05aa28ad0bb8c4808c6c0c538cdb3a47aa33586f79fadc8ba

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

