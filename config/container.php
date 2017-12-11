<?php
use Esler\KivWeb\Container;
use Esler\KivWeb\Db;

return new Container(
    [
        'db' => function () {
            $pdo = new PDO('sqlite:'.__DIR__.'/../data/db.sq3');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return new Db($pdo);
        },
    ]
);
