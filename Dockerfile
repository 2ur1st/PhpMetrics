FROM php:7.2-alpine

ENV PHP_METRICS_VERSION=1.10.0

RUN echo "memory_limit=2048M" >> /usr/local/etc/php/conf.d/docker-php-base.ini \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

COPY . /PhpMetrics-${PHP_METRICS_VERSION}

WORKDIR /PhpMetrics-${PHP_METRICS_VERSION}
RUN composer install \
	&& ln -s /PhpMetrics-${PHP_METRICS_VERSION}/bin/phpmetrics /usr/local/bin/phpmetrics \
	&& rm -rf /var/cache/apk/* /var/tmp/* /tmp/*

VOLUME ["/project"]
WORKDIR /project

ENTRYPOINT ["phpmetrics"]
CMD ["--version"]
