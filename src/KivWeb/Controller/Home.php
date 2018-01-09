<?php
namespace Esler\KivWeb\Controller;

class Home extends AbstractController
{

    /**
     * Entrypoint home/list
     *
     * @return void
     */
    public function actionList()
    {
        return $this->redirect('/?control=users&action=rankings');
    }
}
