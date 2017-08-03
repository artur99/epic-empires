<?php

namespace Controllers;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use Models\UserModel;
use Models\GameModel;

class GameDataAjaxController implements ControllerProviderInterface{
    public function connect(Application $app){
        $indexController = $app['controllers_factory'];
        $indexController->post('/cities', [$this, 'cities']);


        $this->userModel = new UserModel($app['db'], $app['session']);
        $this->gameModel = new GameModel($app['db'], $app['session']);
        $this->userInfo = $this->userModel->info();
        return $indexController;
    }
    public function index(Application $app){
        return true;
    }

    public function cities(Application $app){
        $x = $app['request']->request->get('x');
        $y = $app['request']->request->get('y');

        $q = $this->gameModel->getCitiesAround($x, $y);

        return new JsonResponse($q);
    }

}
