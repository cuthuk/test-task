<?php

namespace Task\Entity;

use Task\Entity\EntityAbstract as Entity;

class User extends Entity
{

    private $id;
    private $login;
    private $password;
    private $registered;

    public static function getTable()
    {
        return 'users';
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
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    /**
     * @param mixed $registred
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;
    }
}