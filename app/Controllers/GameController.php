<?php

namespace Controllers;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

use Models\UserModel;

class GameController implements ControllerProviderInterface{
    public function connect(Application $app){
        $indexController = $app['controllers_factory'];
        $indexController->get('/', [$this, 'index']);

        $this->userModel = new UserModel($app['db'], $app['session']);
        $this->userInfo = $this->userModel->info();

        return $indexController;
    }
    public function index(Application $app){
        if(!$this->userModel->in()){
            return $app->redirect('/');
        }
        return $app['twig']->render('game.twig', [
            'user' => $this->userInfo
        ]);
    }
}
