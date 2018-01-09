<?php
namespace Esler\KivWeb;

use Esler\KivWeb\Db\Collection;
use Esler\KivWeb\Model\AbstractModel as Model;
use PDO;

class Db
{
    private $pdo;

    /**
     * Constructor
     *
     * @param PDO $pdo instance of PDO
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Gets one model by its ID
     *
     * @param string $model classname of model
     * @param int    $id    ID of model
     *
     * @return Model
     */
    public function get(string $model, int $id): Model
    {
        $table = $this->resolveTableName($model);
        $query = "SELECT * FROM `$table` WHERE id = ? AND deleted = ? LIMIT 1";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id, false]);

        return $this->arrayToModel($stmt->fetch(), $model);
    }

    /**
     * Returns a collection of models which can be filtered
     *
     * @param string $model classname of model
     *
     * @return Collection
     */
    public function find(string $model): Collection
    {
        return new Collection($this, $model);
    }

    /**
     * Instance of internal PDO
     *
     * @return PDO
     */
    public function pdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * Saves given model
     *
     * @param Model $model instance of model
     *
     * @return void
     */
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

    /**
     * Converts array (from DB) to a model
     *
     * @param array  $array array to convert
     * @param string $model classname of model
     *
     * @return Model
     */
    public function arrayToModel(array $array, string $model): Model
    {
        return new $model($array);
    }

    /**
     * Returns name of table by given classname or instance of model
     *
     * @param mixed $modelOrClass model or class
     *
     * @return string
     */
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
