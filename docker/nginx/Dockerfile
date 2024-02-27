FROM nginx:1.17.1

# timezone
ENV TZ='Asia/Tokyo'

# conf
COPY  ./docker/nginx/conf/default.conf /etc/nginx/conf.d/default.conf
COPY  ./php/public /var/www/app/public
