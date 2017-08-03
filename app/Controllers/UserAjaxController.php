<?php

namespace Controllers;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use Models\UserModel;

class UserAjaxController implements ControllerProviderInterface{
    public function connect(Application $app){
        $indexController = $app['controllers_factory'];
        $indexController->post('/signup', [$this, 'signup']);
        $indexController->post('/login', [$this, 'login']);
        $indexController->post('/signout', [$this, 'signout']);
        $indexController->post('/data', [$this, 'data']);


        $this->userModel = new UserModel($app['db'], $app['session']);
        $this->userInfo = $this->userModel->info();
        return $indexController;
    }
    public function index(Application $app){
        return true;
    }

    public function signup(Application $app){
        $user = $app['request']->request->get('username');
        $email = $app['request']->request->get('email');
        $password = $app['request']->request->get('password');
        $password2 = $app['request']->request->get('password2');

        $q = $this->userModel->signup($user, $email, $password, $password2);

        return new JsonResponse($q);
    }

    public function login(Application $app){
        $username = $app['request']->request->get('username');
        $password = $app['request']->request->get('password');

        $q = $this->userModel->login($username, $password);

        return new JsonResponse($q);
    }

    public function signout(){
        $q = $this->userModel->signout();
        return new JsonResponse($q);
    }

    public function data(){
        $q = $this->userModel->getData();
        return new JsonResponse($q);
    }

}
