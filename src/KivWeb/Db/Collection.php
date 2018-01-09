<?php
namespace Esler\KivWeb\Db;

use Esler\KivWeb\Db;
use IteratorAggregate;

class Collection implements IteratorAggregate
{

    private $cache = [];
    private $db;
    private $model;
    private $filters = [];
    private $groups = [];
    private $selects = [];
    private $orders = [];

    /**
     * Constructor
     *
     * @param Db     $db    a DB
     * @param string $model classname of model
     */
    public function __construct(Db $db, string $model)
    {
        $this->db = $db;
        $this->model = $model;
    }

    /**
     * Returns an iterator of queried data
     *
     * @return Iterator
     */
    public function getIterator()
    {
        if ($this->cache) {
            foreach ($this->cache as $item) {
                yield $item;
            }
        } else {
            $table = $this->db->resolveTableName($this->model);
            list($where, $values) = $this->resolveWhere();
            $groupBy = $this->resolveGroupBy();
            $selects = $this->resolveSelects();
            $orderBy = $this->resolveOrderBy();

            $query = "SELECT $selects FROM `$table`$where$groupBy$orderBy";

            $stmt = $this->db->pdo()->prepare($query);
            $stmt->execute($values);

            while ($row = $stmt->fetch()) {
                $model = $this->db->arrayToModel($row, $this->model);
                $model->setDb($this->db);
                yield $this->cache[] = $model;
            }
        }
    }

    /**
     * Returns Where object for adding condition
     *
     * @param string $column name of column to filter
     *
     * @return Where
     */
    public function where(string $column): Where
    {
        $this->filters[] = ['AND', $where = new Where($this, $this->db->resolveColumnName($column))];

        return $where;
    }

    /**
     * Adds GROUP BY to query
     *
     * @param string $column name of column
     *
     * @return self
     */
    public function groupBy(string $column): Collection
    {
        $this->groups[] = $this->db->resolveColumnName($column);
        return $this;
    }

    /**
     * Adds ORDER BY to query
     *
     * @param string       $column name of column
     * @param bool|boolean $ascent ASC or DESC
     *
     * @return Collection
     */
    public function orderBy(string $column, bool $ascent = true): Collection
    {
        $this->orders[] = [$this->db->resolveColumnName($column), $ascent];
        return $this;
    }

    /**
     * Add column to SELECT statement
     *
     * @param string      $column name of column
     * @param string|null $alias  optional alias
     *
     * @return Collection
     */
    public function select(string $column, string $alias = null): Collection
    {
        if ($alias) {
            $this->selects[$alias] = $column;
        } else {
            $column = $this->db->resolveColumnName($column);
            $this->selects[$column] = $column;
        }
        return $this;
    }

    /**
     * Generates WHERE statement
     *
     * @return string
     */
    protected function resolveWhere(): array
    {
        $query  = '';
        $values = [];

        foreach ($this->filters as list($operator, $where)) {
            $query .= $query ? " $operator $where" : $where;
            $values[] = $where->value();
        }

        return [$query ? " WHERE $query" : '', $values];
    }

    /**
     * Generates GROUP BY statement
     *
     * @return string
     */
    protected function resolveGroupBy(): string
    {
        if ($this->groups) {
            return ' GROUP BY `' . implode('`, `', $this->groups) . '`';
        }

        return '';
    }

    /**
     * Generates SELECT statement
     *
     * @return string
     */
    protected function resolveSelects(): string
    {
        if ($this->selects) {
            $selects = [];
            foreach ($this->selects as $alias => $column) {
                $selects[] = "$column AS `$alias`";
            }

            return implode(', ', $selects);
        }

        return '*';
    }

    /**
     * Generates ORDER BY statement
     *
     * @return string
     */
    protected function resolveOrderBy(): string
    {
        if ($this->orders) {
            $orders = [];
            foreach ($this->orders as list($column, $ascent)) {
                $orders[] = "`$column` " . ($ascent ? 'ASC' : 'DESC');
            }

            return ' ORDER BY ' . implode(', ', $orders);
        }

        return '';
    }
}
