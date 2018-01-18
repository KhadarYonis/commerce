<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 17/01/18
 * Time: 16:04
 */

namespace AppBundle\Events;


use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class AccountCreateEvent extends Event
{
    /*
     * l'événement sert d'interface entre le déclencher et le souscripteur
     */

    private $user;

    /**
     * @return User
     */
    public function getUser():User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }


}