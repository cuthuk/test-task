<?php
namespace Task\Model;

use Task\Model\ModelAbstract as Model;
use Task\Entity\SessionLog as Entity;

class SessionLogModel extends Model
{
    const SESSION_ACTIVE = 1;
    const SESSION_CLOSED = 0;

    protected function getEntity($data = null)
    {
        return new Entity($data);
    }
}