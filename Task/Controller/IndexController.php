<?php

namespace Task\Controller;

use \Task\Model\UserModel;
use \Task\Controller\ControllerAbstract as Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $model = new UserModel();
        if ($_SESSION['user_id']) {
            $user = $model->find($_SESSION['user_id']);
            return $this->_view->render('index/user.twig', array('user' => $user));
        }
        if (isset($_COOKIE['token'])) {

            $isAuth = $model->isAuth($_COOKIE['token']);
            if ($isAuth) {
                $user = $model->find($_SESSION['user_id']);
                return $this->_view->render('index/user.twig', array('user' => $user));
            }
        }
        return $this->_view->render('index/guest.twig');
    }

    public function registerAction()
    {
        $params = array();
        if (count($_POST)) {
            $model = new UserModel();
            //валидация формы. todo вынести отсюда
            if (strlen($_POST["login"]) < 6) {
                $params["error_message"] = 'Логин должен быть не менее 6 символов';
            }
            if ($_POST["password"] != $_POST["confirm_password"]) {
                $params["error_message"] = 'Пароли не совпадают';
            }
            $values = array(
                'login' => $_POST["login"],
                'password' => $_POST["password"]
            );
            $result = $model->register($values);
            if ($result) {
                header("Location: /");
            }
        }
        return $this->_view->render('index/register.twig', $params);
    }

    public function loginAction()
    {
        $params = array();
        if (count($_POST)) {
            $model = new UserModel();
            //валидация формы. todo вынести отсюда
            if (!strlen($_POST["login"])) {
                $params["error_message"] = 'Введите логин';
            }
            if (!strlen($_POST["password"])) {
                $params["error_message"] = 'Введите пароль';
            }

            $result = $model->login($_POST["login"], $_POST["password"], $_POST["remember"]);
            if ($result) {
                header("Location: /");
                die;
            } else {
                $params["error_message"] = 'Неверный логин или пароль';
            }
        }
        return $this->_view->render('index/register.twig', $params);
    }

    public function logoutAction()
    {
        $model = new UserModel();
        $model->logout($_SESSION['user_id']);
        header("Location: /");
    }
}