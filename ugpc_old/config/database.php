<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'mainportal2' => [
            'driver' => 'mysql',
            'url' => env('PORTAL_DATABASE_URL'),
            'host' => env('PORTAL_DB_HOST', '127.0.0.1'),
            'port' => env('PORTAL_DB_PORT', '3306'),
            'database' => env('PORTAL_DB_DATABASE', 'forge'),
            'username' => env('PORTAL_DB_USERNAME', 'forge'),
            'password' => env('PORTAL_DB_PASSWORD', ''),
            'unix_socket' => env('PORTAL_DB_SOCKET', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'danaflex-ugpc' => [ // Старая база
            'driver' => 'pgsql',
            'host' => env('DB_UGPC_HOST', '127.0.0.1'),
            'database' => env('DB_UGPC_DATABASE', 'ugpc'),
            'username' => env('DB_UGPC_USERNAME', 'root'),
            'password' => env('DB_UGPC_PASSWORD', ''),
            'port' => env('DB_UGPC_PORT', '3306'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        'global' => [
            'driver' => 'mysql',
            'url' => env('GLOBAL_DATABASE_URL'),
            'host' => env('GLOBAL_DB_HOST', '127.0.0.1'),
            'port' => env('GLOBAL_DB_PORT', '3306'),
            'database' => env('GLOBAL_DB_DATABASE', 'forge'),
            'username' => env('GLOBAL_DB_USERNAME', 'forge'),
            'password' => env('GLOBAL_DB_PASSWORD', ''),
            'unix_socket' => env('GLOBAL_DB_SOCKET', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        
        'global' => [
            'driver' => 'mysql',
            'url' => env('GLOBAL_DATABASE_URL'),
            'host' => env('GLOBAL_DB_HOST', '127.0.0.1'),
            'port' => env('GLOBAL_DB_PORT', '3306'),
            'database' => env('GLOBAL_DB_DATABASE', 'forge'),
            'username' => env('GLOBAL_DB_USERNAME', 'forge'),
            'password' => env('GLOBAL_DB_PASSWORD', ''),
            'unix_socket' => env('GLOBAL_DB_SOCKET', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],


        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

        'access' => [
            'driver' => 'pdo_access',
            'connection_string' => 'dsn={Access}',
            'database' => 'C:\База ОКВиД-6-0_ядро.mdb',            
            'username' => '',
            'password' => '',
            'table_prefix' => '',
        ],

        

        'danaflexNano' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => '10.10.40.78',
            'port' => '1433',
            'database' => 'DANAFLEXNANO',
            'username' => 'datacollector',
            'password' => 'Rjkktrnjh123',
            'charset' => 'utf8',
            'prefix' => 'ООО _ДАНАФЛЕКС-НАНО_$',
            'prefix_indexes' => true,
        ],

        'danaflexZAO' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => '10.10.40.107',
            'port' => '1433',
            'database' => 'DANAFLEX11',
            'username' => 'datacollector',
            'password' => 'Rjkktrnjh123',
            'charset' => 'utf8',
            'prefix' => 'ЗАО _ДАНАФЛЕКС_$',
            'prefix_indexes' => true,
        ],

        'danaflexAlabuga' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => '10.5.252.10',
            'port' => '1433',
            'database' => 'danaflex-alabuga',
            'username' => 'datacollector',
            'password' => 'Rjkktrnjh123',
            'charset' => 'utf8',
            'prefix' => 'ООО _ДАНАФЛЕКС-АЛАБУГА_$',
            'prefix_indexes' => true,
        ],

        'dgpack' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => '10.15.56.1',
            'port' => '1433',
            'database' => 'dgpack',
            'username' => 'datacollector',
            'password' => 'Rjkktrnjh123',
            'charset' => 'utf8',
            'prefix' => 'DGPack s_r_o_$',
            'prefix_indexes' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
