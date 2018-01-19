<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 17/01/18
 * Time: 16:04
 */

namespace AppBundle\Events;


use AppBundle\Entity\UserToken;
use Symfony\Component\EventDispatcher\Event;

class UserTokenCreateEvent extends Event
{
    /*
     * l'événement sert d'interface entre le déclencher et le souscripteur
     */

    private $userToken;

    /**
     * @return UserToken
     */
    public function getUser():UserToken
    {
        return $this->userToken;
    }

    /**
     * @param UserToken $userToken
     */
    public function setUser(UserToken $userToken)
    {
        $this->userToken = $userToken;
    }


}