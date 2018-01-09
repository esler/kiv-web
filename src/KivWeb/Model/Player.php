<?php
namespace Esler\KivWeb\Model;

class Player extends AbstractModel
{

    public function user(): User
    {
        // TODO [Ondrej Esler, B] syntactic sugar, eg. return $this->lookUp(User::class);
        return $this->db()->get(User::class, $this->userId);
    }
}
