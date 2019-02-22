FROM wilson1000/wordpress-base:upgrade

ADD . /bedrock

WORKDIR /bedrock

ARG COMPOSER_USER
ARG COMPOSER_PASS

# Add custom nginx config - REDIRECT WHILE COURT CASE IS ONGOING
RUN sed -i 's/fastcgi_intercept_errors off;/fastcgi_intercept_errors on;/' /etc/nginx/php-fpm.conf && \
    mv docker/conf/nginx/server.conf /etc/nginx/sites-available/

RUN chmod +x bin/* && sleep 1 && \
    make clean && \
    bin/composer-auth.sh && \
    make build && \
    rm -f auth.json
