**1.dockerインストール**  
mac: https://matsuand.github.io/docs.docker.jp.onthefly/desktop/mac/install/ <br>
windows: https://matsuand.github.io/docs.docker.jp.onthefly/desktop/windows/install/

 **2.dockerネットワーク作成** 

```
docker network create education
```

 **3.docker起動** 

```
docker compose up -d
```

 **4.初期データ投入**  
別ウィンドウ

```
docker exec -it php bash
```
```
php artisan db:seed
```

 **5.stlaunデータベースにdumpデータ投入**  
educure_plus_2021-12-24.sql

## 生徒予約データはバッチで挿入

```
docker exec -it php bash
```

```
php artisan batch:createscheduleandreservation
```

 **6.127.0.0.1/masterでLiNew管理へログイン**  
test@example.co.jp / password


LiNew管理以外にも他の権限があるので下記を参考にしてください。

| ユーザー    | 役割                     | 操作                                     | URL                | ユーザーテーブル |
| ----------- | ------------------------ | ---------------------------------------- | ------------------ | --------------- |
| LiNew管理  | システム全体の管理者  | 企業、受講者、コンテンツ（コース、カリキュラム）の編集、削除など | 127.0.0.1/master  | admins          |
| 企業管理    | 企業担当者                     | 受講者の編集、削除 カリキュラムの閲覧 管理者の編集、削除など | 127.0.0.1/admin | users           |
| 生徒        | カリキュラムの受講 | カリキュラムの閲覧 自身の情報変更など       | 127.0.0.1 | students        |


## 以下任意で設定
・xdebug  
【VScode】  
①下記サイトを参考にプラグイン(PHPデバッグ)をインストール  
②launch.jsonの設定(ポート番号合わせる)  
③ “pathMappings”を設定する  
<参考サイト>  
https://medium-company.com/vscode-php-%E3%83%87%E3%83%90%E3%83%83%E3%82%B0/
https://old-pine.net/Visual-Studio-Code/vscodePHP%E3%83%87%E3%83%90%E3%83%83%E3%82%[…]E3%83%88%E3%81%AB%E6%AD%A2%E3%81%BE%E3%82%89%E3%81%AA%E3%81%84

[launch.json]

```
{
    // Use IntelliSense to learn about possible attributes.
    // Hover to view descriptions of existing attributes.
    // For more information, visit: https://go.microsoft.com/fwlink/?linkid=830387
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9012,
            "pathMappings": {
                "/var/www/app": "${workspaceRoot}/php"
            }
        }
    ]
}
```

## トラブルシューティング
・node-modules類の変更後、nuxtが起動しない  
以下コマンド実行後再度`docker compose up`を実行
```
docker-compose run nuxt yarn install --force
```
