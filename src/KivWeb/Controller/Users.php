<?php
namespace Esler\KivWeb\Controller;

use Esler\KivWeb\Model\Player;
use Esler\KivWeb\Model\User;

class Users extends AbstractController
{

    /**
     * Entrypoint users/login
     *
     * @return void
     */
    public function actionLogin()
    {
        $context = [];

        if ($this->getRequest()->getMethod() == 'POST') {
            if ($this->isValidCredentials()) {
                return $this->redirect('/');
            } else {
                $context['message'] = 'Invalid credentials';
            }
        }

        $this->render($context);
    }

    /**
     * Entrypoint Users/register
     *
     * @return void
     */
    public function actionRegister()
    {
        $context = [];

        if ($this->getRequest()->getMethod() == 'POST') {
            $name = $this->getRequest()->getParam('name');
            $username = $this->getRequest()->getParam('username');
            $password = $this->getRequest()->getParam('password');

            if ($name && $username && $password) {
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $user = new User([
                    'name'     => $name,
                    'username' => $username,
                    'password' => $hash,
                    'role'     => 'member',
                ]);

                $this->get('db')->save($user);

                return $this->actionLogin();
            } else {
                $context['message'] = 'Please provide all informations';
            }
        }

        $this->render($context);
    }

    /**
     * Entrypoint users/setting
     *
     * @return void
     */
    public function actionSettings()
    {
        if ($this->getRequest()->getParam('logout') !== null) {
            unset($_SESSION['auth_user_id']);
            return $this->redirect('/');
        }

        $this->render();
    }

    /**
     * Entrypoint users/rankings
     *
     * @return void
     */
    public function actionRankings()
    {
        $players = $this->get('db')->find(Player::class)
            ->select('userId')
            ->select('SUM(scored)', 'scored')
            ->groupBy('userId')
            ->orderBy('scored', false);

        $this->render([
            'players' => $players,
        ]);
    }

    /**
     * Checks a request and decides that given credentials are valid
     *
     * @return boolean
     */
    private function isValidCredentials(): bool
    {
        $username = $this->getRequest()->getParam('username');
        $password = $this->getRequest()->getParam('password');

        $users = $this->get('db')->find(User::class)
            ->where('username')->is($username);

        foreach ($users as $user) {
            if (password_verify($password, $user->password)) {
                $_SESSION['auth_user_id'] = $user->id;
                return true;
            }
        }

        return false;
    }
}
