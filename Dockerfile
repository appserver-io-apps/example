################################################################################
# Dockerfile for appserver.io example application
################################################################################

# base image
FROM wagner83104/dist:1.1.1-alpha7

################################################################################

# author
MAINTAINER Tim Wagner <tw@appserver.io>

################################################################################

COPY composer.json /tmp/example/
COPY src/ /tmp/example/src/

################################################################################

# install composer dependencies
RUN cd /tmp/example \
    && composer install --prefer-dist --no-dev --no-interaction --optimize-autoloader \
    && mkdir /opt/appserver/webapps \
    && cp -r /tmp/example/src/ /opt/appserver/webapps/example/

################################################################################

# define default command
ENTRYPOINT ["/usr/bin/supervisord"]