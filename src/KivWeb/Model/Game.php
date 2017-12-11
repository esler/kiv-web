<?php
namespace Esler\KivWeb\Model;

use DateTime;

class Game extends AbstractModel
{
    const TABLE_NAME = 'games';

    protected $id;
    protected $owner;
    protected $stamp;

    public function __construct()
    {
        $this->stamp = new DateTime;
    }
}
