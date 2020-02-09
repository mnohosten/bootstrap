<?php
declare(strict_types=1);

return [
    'app.debug' => (bool)getenv('APP_DEBUG'),
    'app.key' => getenv('APP_KEY'),
    'app.host' => getenv('APP_HOST'),

    'mysql.host' => getenv('MYSQL_HOST'),
    'mysql.dbname' => getenv('MYSQL_DATABASE'),
    'mysql.username' => getenv('MYSQL_USER'),
    'mysql.password' => getenv('MYSQL_PASSWORD'),
    'mysql.charset' => getenv('MYSQL_CHARSET'),

    'es.hosts' => getenv('ES_HOSTS'),

];
