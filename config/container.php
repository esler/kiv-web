<?php
use Esler\KivWeb\Container;
use Esler\KivWeb\Db;
use Esler\KivWeb\Exception;
use Esler\KivWeb\Model\User;

return new Container(
    [
        'db' => function () {
            $pdo = new PDO('mysql:dbname=kiv_web;host=127.0.0.1;charset=utf8mb4', 'root', '');
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
