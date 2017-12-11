<?php
namespace Esler\KivWeb;

use Esler\KivWeb\Model\AbstractModel as Model;
use PDO;

class Db
{
    private $pdo;


    private $games = [
    ];

    private $users = [
        ['id' => 1, 'name' => 'Ondra'],
        ['id' => 2, 'name' => 'Vojta'],
        ['id' => 3, 'name' => 'Tomáš'],
        ['id' => 4, 'name' => 'Hofi'],
        ['id' => 5, 'name' => 'Matěj'],
        ['id' => 6, 'name' => 'Honza P.'],
        ['id' => 7, 'name' => 'Petr'],
        ['id' => 8, 'name' => 'David'],
        ['id' => 9, 'name' => 'Honza Z.'],
        ['id' => 10, 'name' => 'Bohouš'],
        ['id' => 11, 'name' => 'Olda'],
        ['id' => 12, 'name' => 'Pepa'],
        ['id' => 12, 'name' => 'Lukáš'],
        ['id' => 12, 'name' => 'Flajš'],
        ['id' => 12, 'name' => 'Tonda'],
        ['id' => 12, 'name' => 'Kuba'],
        ['id' => 12, 'name' => 'Honza Pal.'],
        ['id' => 12, 'name' => 'Marek'],
    ];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function find(string $model): array
    {
        return array_map([$model, 'fromArray'], $this->users);
    }

    public function save(Model $model): void
    {
        $array = $model->toArray();
        $table = $model::TABLE_NAME;
        $cols  = implode('`,`', array_keys($array));
        $holds = implode(',', array_fill(0, count($array), '?'));

        $query = "INSERT INTO `$table` (`$cols`) VALUES ($holds)";

        $stmt  = $this->pdo->prepare($query);
        $stmt->execute($array);
        var_dump($query);
    }
}
