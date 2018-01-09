<?php
use Esler\KivWeb\Container;
use Esler\KivWeb\Db;
use Esler\KivWeb\Exception;
use Esler\KivWeb\Model\User;

return new Container(
    [
        'db' => function () {
            $host = getenv('MYSQL_HOST');
            $user = getenv('MYSQL_USER');
            $password = getenv('MYSQL_PASSWORD');
            $dbname = getenv('MYSQL_DATABASE');

            $pdo = new PDO("mysql:dbname=$dbname;host=$host;charset=utf8mb4", $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return new Db($pdo);
        },
        'me' => function () {
            if ($id = $_SESSION['auth_user_id'] ?? null) {
                return $this->get('db')->get(User::class, $id);
            }

            return null;
        }
    ]
);
