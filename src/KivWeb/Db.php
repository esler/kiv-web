<?php
namespace Esler\KivWeb;

use Esler\KivWeb\Db\Collection;
use Esler\KivWeb\Model\AbstractModel as Model;
use PDO;

class Db
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get(string $model, int $id): Model
    {
        $table = $this->resolveTableName($model);
        $query = "SELECT * FROM `$table` WHERE id = ? AND deleted = ? LIMIT 1";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id, false]);

        return $this->arrayToModel($stmt->fetch(), $model);
    }

    public function find(string $model): Collection
    {
        return new Collection($this, $model);
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }

    public function save(Model $model): void
    {
        $table = $this->resolveTableName($model);
        $array = $this->modelToArray($model);
        $cols  = implode('`,`', array_map([$this, 'resolveColumnName'], array_keys($array)));
        $holds = implode(',', array_fill(0, count($array), '?'));

        $query = "INSERT INTO `$table` (`$cols`) VALUES ($holds)";

        $stmt  = $this->pdo->prepare($query);
        $stmt->execute(array_values($array));

        $model->id = (int) $this->pdo->lastInsertId();
    }

    public function arrayToModel(array $array, string $model): Model
    {
        return new $model($array);
    }

    public function resolveTableName($modelOrClass): string
    {
        $class = is_object($modelOrClass) ? get_class($modelOrClass) : $modelOrClass;
        return Utils::camelCaseToSnakeCase(str_replace('Esler\KivWeb\Model\\', '', $class))  . 's';
    }

    public function resolveColumnName(string $property): string
    {
        return Utils::camelCaseToSnakeCase($property);
    }

    public function modelToArray(Model $model): array
    {
        return array_map(function ($value) {
            switch (Utils::getTypeOrClass($value)) {
                case 'DateTime':
                    return $value->format('Y-m-d\TH:i:s');
                default:
                    return $value;
            }
        }, $model->toArray());
    }
}
