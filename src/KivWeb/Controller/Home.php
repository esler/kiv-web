<?php
namespace Esler\KivWeb\Controller;

class Home extends AbstractController
{

    public function actionList()
    {
        $this->render(['who' => 'World']);
    }
}
