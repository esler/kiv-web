<?php
namespace Esler\KivWeb\Controller;

use Esler\KivWeb\Db;
use Esler\KivWeb\Db\Collection;
use Esler\KivWeb\Model\Game;
use Esler\KivWeb\Model\Player;
use Esler\KivWeb\Model\User;

class Games extends AbstractController
{

    const PLAYERS = [
        ['label' => 'Me', 'side' => 'home'],
        ['label' => 'My teammate', 'side' => 'home'],
        ['label' => '1st opponent', 'side' => 'guest'],
        ['label' => '2nd opponent', 'side' => 'guest'],
    ];

    /**
     * Entrypoint games/enter
     *
     * @return void
     */
    public function actionEnter()
    {
        $db = $this->get('db');

        if ($this->getRequest()->getMethod() == 'POST') {
            $game = new Game(['ownerId' => $this->get('me')->id]);
            $db->save($game);

            foreach (self::PLAYERS as $id => $player) {
                $db->save(new Player([
                    'post'   => $this->getRequest()->getParam('post')[$id],
                    'side'   => $player['side'],
                    'userId' => $this->getRequest()->getParam('userId')[$id],
                    'gameId' => $game->id,
                    'scored' => $this->getRequest()->getParam('score')[$id],
                ]));
            }

            return $this->redirect('/?control=games&action=history');
        } else {
            $this->render([
                'players' => self::PLAYERS,
                'users' => $db->find(User::class),
            ]);
        }
    }

    /**
     * Entrypoint games/history
     *
     * @return void
     */
    public function actionHistory()
    {
        $games = $this->get('db')->find(Game::class)
            ->orderBy('stamp', false);

        $this->render([
            'games'  => $games,
        ]);
    }
}
