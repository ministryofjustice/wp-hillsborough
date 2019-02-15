FROM wilson1000/wordpress-base:upgrade

ADD . /bedrock

WORKDIR /bedrock

ARG COMPOSER_USER
ARG COMPOSER_PASS

RUN make clean && \
    bin/composer-auth.sh && \
    make build && \
    rm -f auth.json
