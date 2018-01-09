<?php
namespace Esler\KivWeb\Db;

class Where
{
    private $column;
    private $operator;
    private $value;
    private $collection;

    /**
     * Constructor
     *
     * @param Collection $collection collection to decorate
     * @param string     $column     name of column
     */
    public function __construct(Collection $collection, string $column)
    {
        $this->collection = $collection;
        $this->column = $column;
    }

    /**
     * Generates equal sign
     *
     * @param mixed $value a value
     *
     * @return Collection
     */
    public function is($value): Collection
    {
        $this->operator = '=';
        $this->value = $value;

        return $this->collection;
    }

    /**
     * Generates string of this WHERE
     *
     * @return string
     */
    public function __toString(): string
    {
        return "`$this->column` $this->operator ?";
    }

    /**
     * Inner value
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }
}
