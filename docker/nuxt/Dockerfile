# Image
FROM node:12.4-alpine

RUN apk add python make g++

# Set up work directory
WORKDIR /var/www/nuxt

# Configure host
ENV HOST 0.0.0.0
COPY ./docker/nuxt/startup.sh /usr/local/bin/startup.sh
COPY ./docker/nuxt/.env /usr/local/bin/.env
COPY ./nuxt /var/www/nuxt

RUN chmod 777 /usr/local/bin/startup.sh
RUN chmod 777 /usr/local/bin/.env
# Init command
CMD ["/usr/local/bin/startup.sh"]
