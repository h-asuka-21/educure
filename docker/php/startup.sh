#!/bin/bash -e

# mysqlコンテナの起動まで待機する
until mysqladmin ping -h db --silent; do
  echo 'waiting for mysqld to be connectable...'
  sleep 2
done

cd /var/www/app

# .env.exampleから.envを作成
cp -f .env.example .env

if [ ! -e vendor ]; then
  # vendorディレクトリがない場合composer install
  composer install
fi

if [ ! -e node_modules ]; then
  # node_modulesディレクトリがない場合npm install
  npm install
fi

# 環境変数で.envを置換
sed -i \
    -e "/^APP_URL=.*/c APP_URL=${APP_URL}" \
    -e "/^ASSET_URL=.*/c ASSET_URL=${APP_URL}" \
    -e "/^DB_HOST=.*/c DB_HOST=${DB_HOST}" \
    -e "/^DB_PORT=.*/c DB_PORT=${DB_PORT}" \
    -e "/^DB_DATABASE=.*/c DB_DATABASE=${DB_DATABASE_NAME}" \
    -e "/^DB_USERNAME=.*/c DB_USERNAME=${DB_USERNAME}" \
    -e "/^DB_PASSWORD=.*/c DB_PASSWORD=${DB_PASSWORD}" \
    -e "/^MAIL_DRIVER=.*/c MAIL_DRIVER=${MAIL_DRIVER}" \
    -e "/^MAIL_HOST=.*/c MAIL_HOST=${MAIL_HOST}" \
    -e "/^MAIL_PORT=.*/c MAIL_PORT=${MAIL_PORT}" \
    -e "/^MAIL_USERNAME=.*/c MAIL_USERNAME=${MAIL_USERNAME}" \
    -e "/^MAIL_PASSWORD=.*/c MAIL_PASSWORD=${MAIL_PASSWORD}" \
    -e "/^MAIL_FROM_ADDRESS=.*/c MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS}" \
    -e "/^MAIL_FROM_NAME=.*/c MAIL_FROM_NAME=${MAIL_FROM_NAME}" \
    -e "/^JWT_SECRET=.*/c JWT_SECRET=${JWT_SECRET}" \
    /var/www/app/.env
# envにtokenのブラックリスト化後の保持期間を追記。以下を参照
# https://qiita.com/yh1224/items/bd00e85d5c53350e93ca
sed -e '$a JWT_BLACKLIST_GRACE_PERIOD=15' /var/www/app/.env

cp -f .env .env.test

sed -i \
    -e "/^DB_DATABASE=.*/c DB_DATABASE=${TEST_DB_DATABASE}" \
    -e "/^DB_USERNAME=.*/c DB_USERNAME=${TEST_DB_USERNAME}" \
    -e "/^DB_PASSWORD=.*/c DB_PASSWORD=${TEST_DB_PASSWORD}" \
    /var/www/app/.env.test

# appキーの生成
php artisan key:generate

#シンボリックリンクの作成
php artisan storage:link

# storageディレクトリの権限変更
chmod -R 777 storage/

# migration実行
php artisan migrate
# キャッシュクリア
php artisan cache:clear
php artisan config:clear

touch /tmp/xdebug.log
chmod 777 /tmp/xdebug.log

exec $@
