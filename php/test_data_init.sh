php artisan cache:clear
php artisan config:cache
# seedを実行するとテスト実行時間が落ちるので要検討
php artisan migrate:fresh --seed
