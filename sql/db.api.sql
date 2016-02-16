 /*
  * mysqlを起動すること
  * mysql.server start
  * <Leader> se で実行
  */

-- profile for local database;
let g:dbext_default_profile_api = 'type=mysql:user=api:passwd=api012:host=0.0.0.0:port=3306:dbname=api:extra=-vvv'
DBSetOption profile=api

-- profile for local testing database;
let g:dbext_default_profile_api_test = 'type=mysql:user=api:passwd=api012:host=0.0.0.0:port=3306:dbname=api_test:extra=-vvv'
DBSetOption profile=api_test

SHOW databases;
SHOW tables;

-- users テーブル確認 /*{{{*/
SELECT * FROM `users`;
/*}}}*/
