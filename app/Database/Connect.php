<?php 

namespace Tuna\Database;

class Connect
{
    protected static $pdo;

    public static function instance($test = false)
    {
        if( !static::$pdo ) {
            $username = getenv('DB_USER');
            $password = getenv('DB_PASSWORD');
            $host = getenv('DB_HOST');
            $db = $test ? getenv('DB_NAME_TEST') : getenv('DB_NAME');

            static::$pdo = new \PDO("mysql:dbname=$db;host=$host", $username, $password);
        }

        return static::$pdo;
    }
}
