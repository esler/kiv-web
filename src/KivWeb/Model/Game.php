<?php
namespace Esler\KivWeb\Model;

use DateTime;

class Game extends AbstractModel
{

    protected function init(): void
    {
        $this->stamp = new DateTime;
    }

    public function players()
    {
        // TODO [Ondrej Esler, B] syntactic sugar, eg. return $this->lookUps(Player::class);
        return $this->db()->find(Player::class)->where('gameId')->is($this->id);
    }

    public function score()
    {
        $score = [0, 0];

        foreach ($this->players() as $player) {
            $score[(int)($player->side == 'guest')] += $player->scored;
        }

        return $score;
    }

    public function homes()
    {
        foreach ($this->players() as $player) {
            if ($player->side == 'home') {
                yield $player->user();
            }
        }
    }

    public function guests()
    {
        foreach ($this->players() as $player) {
            if ($player->side == 'guest') {
                yield $player->user();
            }
        }
    }
}
