<?php
/**
 * Created by PhpStorm.
 * User: cuthuk
 * Date: 25.09.2016
 * Time: 0:34
 */

namespace Task\Entity;

use Task\Entity\EntityAbstract as Entity;

class SessionLog extends Entity
{
    private $id;
    private $user_id;
    private $login_date;
    private $user_ip;
    private $token;
    private $is_active;

    public static function getTable()
    {
        return 'session_log';
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getLoginDate()
    {
        return $this->login_date;
    }

    /**
     * @param mixed $login_date
     */
    public function setLoginDate($login_date)
    {
        $this->login_date = $login_date;
    }

    /**
     * @return mixed
     */
    public function getUserIp()
    {
        return $this->user_ip;
    }

    /**
     * @param mixed $user_ip
     */
    public function setUserIp($user_ip)
    {
        $this->user_ip = $user_ip;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * @param mixed $is_active
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
    }
}