FROM php:7-fpm-alpine

# install required PHP extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
        imap \
        mysqli \
        zip \
        gd \
        xsl

COPY getenv-docker.php /usr/local/lib/php/

# PHPlist version & hash
ARG PHPLIST_VERSION=3.6.8
ARG PHPLIST_HASH=bb82f62a66f09f79542e6c81cc8ffc6271bef2a8f70c9cdc3ae9237a8455f100

# get PHPlist and move to /var/www/html, except plugins
# (plugins rest on a volume, they are updated from distribution on container start)
RUN apk add --no-cache wget rsync

RUN cd /tmp \
    && wget -O phplist.tar.gz "https://sourceforge.net/projects/phplist/files/phplist/$PHPLIST_VERSION/phplist-$PHPLIST_VERSION.tgz/download" \
    && echo "$PHPLIST_HASH  phplist.tar.gz" | sha256sum -c - \
    && tar --no-same-owner --no-same-permissions -xzf /tmp/phplist.tar.gz \
    && rm -f phplist.tar.gz \
    && mv phplist-$PHPLIST_VERSION phplist \
    && cd phplist \
    && rm -f public_html/lists/config/config.php \
    && mkdir -p /opt/phplist \
    && mv public_html/lists/admin/plugins /opt/phplist \
    && mkdir -p /var/www && rm -rf /var/www/html \
    && mv -v public_html/lists /var/www/html \
    && mkdir -p /var/www/html/admin/plugins /var/www/html/upload \
    && cd /tmp \
    && rm -rf phplist

# pre-install PHPlist plugins
RUN mkdir -p /tmp/plugin-install \
    && cd /tmp/plugin-install \
    && for url in \
	https://github.com/bramley/phplist-plugin-viewbrowser/archive/master.zip \
    ; do wget -O master.zip "$url" ; unzip master.zip ; rm master.zip ; done \
    && mv -v phplist-plugin-*/plugins/* /opt/phplist/plugins/ \
    && cd /tmp \
    && rm -rf plugin-install

# add adapted config, entrypoint & executable
COPY config.php /var/www/html/config/
COPY entrypoint.* /bin/
COPY phplist /usr/local/bin/
RUN chmod +x /usr/local/bin/phplist

# add crontab
COPY crontab /tmp/phplist-crontab
RUN crontab -u root -r \
    && crontab -u www-data /tmp/phplist-crontab \
    && rm /tmp/phplist-crontab

WORKDIR /var/www/html

ENTRYPOINT [ "/bin/entrypoint.sh" ]

CMD [ "php-fpm" ]

