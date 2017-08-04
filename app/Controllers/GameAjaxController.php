<?php

namespace Controllers;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use Models\UserModel;
use Models\GameModel;

class GameAjaxController implements ControllerProviderInterface{
    public function connect(Application $app){
        $indexController = $app['controllers_factory'];
        $indexController->post('/cities', [$this, 'cities']);

        $indexController->post('/add_task', [$this, 'addTask']);
        $indexController->post('/get_tasks', [$this, 'getTasks']);
        $indexController->post('/del_task', [$this, 'delTask']);

        $indexController->post('/start_war', [$this, 'startWar']);


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

    public function addTask(Application $app){
        if(!$this->userModel->in()) return new JsonResponse($this->gameModel->err());
        $task = $app['request']->request->get('task');
        $param = $app['request']->request->get('param');
        $cid = $app['request']->request->get('city_id');
        $uid = $this->userModel->info()['id'];

        $q = $this->gameModel->addTask($cid, $uid, $task, $param);

        return new JsonResponse($q);
    }

    public function delTask(Application $app){
        if(!$this->userModel->in()) return new JsonResponse($this->gameModel->err());
        $task_id = $app['request']->request->get('task_id');
        $uid = $this->userModel->info()['id'];

        $q = $this->gameModel->delTask($task_id, $uid);

        return new JsonResponse($q);
    }

    public function getTasks(Application $app){
        if(!$this->userModel->in()) return new JsonResponse($this->gameModel->err());
        $cid = $app['request']->request->get('city_id');
        $uid = $this->userModel->info()['id'];

        $q = $this->gameModel->getTasks($uid, $cid);

        return new JsonResponse($q);
    }

    public function startWar(Application $app){
        if(!$this->userModel->in()) return new JsonResponse($this->gameModel->err());
        $cid = $app['request']->request->get('city_id');
        $tid = $app['request']->request->get('target_id');
        $units = $app['request']->request->get('units');
        $archers = $app['request']->request->get('archers');
        $uid = $this->userModel->info()['id'];

        $q = $this->gameModel->startWar($uid, $cid, $tid, $units, $archers);

        return new JsonResponse($q);
    }
}
