cd /var/www/nuxt

cp -f .env.example .env

sed -i \
    -e "/^API_URL=.*/c API_URL=${API_URL}"\
    -e "/^BASE_URL=.*/c BASE_URL=${BASE_URL}"\
    /var/www/nuxt/.env


yarn install && yarn dev

exec $@