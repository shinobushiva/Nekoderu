## 環境
- PHP 5.6以上
- MySQL 5.6以上

## データベースの設定
app/config/app.phpの下記の部分を設定（ホスト名・ユーザー名・パスワード名・データベース名）
```
'Datasources' => [
        'default' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => 'localhost',
            /**
             * CakePHP will use the default DB port based on the driver selected
             * MySQL on MAMP uses port 8889, MAMP users will want to uncomment
             * the following line and set the port accordingly
             */
            //'port' => 'non_standard_port_number',
            'username' => 'root',
            'password' => 'root',
            'database' => 'nekoderu',
            'encoding' => 'utf8',
            'timezone' => 'UTC',
            'flags' => [],
            'cacheMetadata' => true,
            'log' => false,
```

## ライブラリ
```
$ chmod 755 composer.phar
$ cd app && ../composer.phar install
```

## データベース
```
$mysql -u root -p
> CREATE DATABASE `nekoderu` DEFAULT CHARACTER SET = `utf8`;
> exit
```

## マイグレーションファイルの実行
```
$ app/bin/cake migrations migrate
```

## ビルトインサーバーの起動
```
$ app/bin/cake server
```

### URLs
- [トップ](http://localhost:8765/)
- [投稿](http://localhost:8765/add_neko)
- [リスト](http://localhost:8765/cats)
