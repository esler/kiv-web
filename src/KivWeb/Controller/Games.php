<?php
namespace Esler\KivWeb\Controller;

use Esler\KivWeb\Db;
use Esler\KivWeb\Model\Game;
use Esler\KivWeb\Model\User;

class Games extends AbstractController
{
    const ENTER_PHASES = [
        'teammate' => ['caption' => 'Teammate'],
        'first_opponent' => ['caption' => 'First opponent'],
        'second_opponent',
        'team_score',
        'opponent_score',
    ];

    public function actionEnter()
    {
        $db = $this->get('db');

        $game = $db->save(new Game);



        $users = $db->find(User::class);
        $phase =


        // $players = [
        //     'home_1' => $this->popUser($users, $this->getRequest()->getParam('home_1')),
        //     'home_2' => $this->popUser($users, $this->getRequest()->getParam('home_2')),
        //     'guest_1' => $this->popUser($users, $this->getRequest()->getParam('guest_1')),
        //     'guest_2' => $this->popUser($users, $this->getRequest()->getParam('guest_2')),
        // ];

        $this->render(['users' => $users, 'phase' => $players]);
    }

    public function actionEnterTeammate() {
        $db = new Db;
        $users = $db->find(User::class);
    }

    private function popUser(array &$users, int $id = null): ?User
    {
        foreach ($users as $i => $user) {
            if ($user->id == $id) {
                unset($users[$i]);
                return $user;
            }
        }

        return null;
    }
}
