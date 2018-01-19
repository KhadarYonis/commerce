<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 17/01/18
 * Time: 15:31
 */

namespace AppBundle\Events;


class AccountEvents
{
    /*
     * liste des événements sous forme de constantes
     *      valeur : identifiant unique
     */
    const CREATE = 'account.create';
    const DELETE = 'account.delete';
    const PASSWORD_FORGET = 'userToken.create';

}