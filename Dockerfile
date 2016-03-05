################################################################################
# Dockerfile for appserver.io example application
################################################################################

# base image
FROM wagner83104/servlet-engine:master

################################################################################

# author
MAINTAINER Tim Wagner <tw@appserver.io>

################################################################################

COPY composer.json /tmp/example/
COPY src/ /tmp/example/src/

################################################################################

# install composer dependencies
RUN cd /tmp/example && \
    composer install --prefer-dist --no-dev --no-interaction --optimize-autoloader

################################################################################

RUN mkdir /opt/appserver/webapps
RUN mv /tmp/example/src /opt/appserver/webapps/example

################################################################################

# define default command
ENTRYPOINT ["/usr/bin/supervisord"]