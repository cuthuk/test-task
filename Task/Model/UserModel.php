<?php
namespace Task\Model;

use Task\Model\ModelAbstract as Model;
use Task\Entity\User as Entity;
use Task\Db\MySql as MySql;
use Task\Model\SessionLogModel as SessionLog;

class UserModel extends Model
{
    protected function getEntity($data = null)
    {
        return new Entity($data);
    }

    public function register($data)
    {
        $data['registered'] = date(MySql::SQL_DATETIME);
        $result = $this->create($data);
        return $result;
    }

    public function login($login, $password, $remember)
    {
        $where = array(
            'login = ' => $login,
            ' AND password = ' => $password
        );
        $user = $this->findBy($where);
        if ($user) {
            $_SESSION['user_id'] = $user->getId();
            if ($remember) {
                $token = md5(time().$login);
                $sessionLog = new SessionLogModel();
                $data = array(
                    'user_id' => $user->getId(),
                    'login_date' => date(MySql::SQL_DATETIME),
                    'user_ip' => $_SERVER['REMOTE_ADDR'],
                    'token' => $token,
                    'is_active' => $sessionLog::SESSION_ACTIVE
                );
                $sessionLog->create($data);
                setcookie('token', $token, time() + 60 * 60 * 24 * 14);
            }
        }
        return $user;

    }

    public function authorizeByToken($token)
    {
        $sessionLog = new SessionLogModel();
        $where = array(
            'token = ' => $token,
            ' AND is_active = ' => $sessionLog::SESSION_ACTIVE
        );

        $activeSession = $sessionLog->findBy($where);
        if ($activeSession) {
            $_SESSION['user_id'] = $activeSession->getUserId();
        }

    }

    public function logout($userId)
    {
        unset ($_COOKIE['token']);
        $sessionLog = new SessionLogModel();
        $where = array(
            'user_id =' => $userId,
            ' AND is_active = ' => $sessionLog::SESSION_ACTIVE
        );
        $data = array(
            'is_active' => $sessionLog::SESSION_CLOSED
        );
        $sessionLog->update($data, $where);
        session_destroy();
        return true;
    }

    public function isAuth($token = null)
    {
        if ($token) {
            $this->authorizeByToken($token);
        }
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
    }
}