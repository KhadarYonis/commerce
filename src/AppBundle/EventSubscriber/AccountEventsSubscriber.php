<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 17/01/18
 * Time: 16:13
 */

namespace AppBundle\EventSubscriber;


use AppBundle\Events\AccountEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountEventsSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        /*
         * doit retourner un tableau
         *   clé : l'événement écouté
         *   valeur : nom du gestionnaire d'événement
         *   AccountEvents(vient de la classe de  Events/AccountEvents)
         *  el.addEventListener('click = AccountEvents::CREATE ', onclick = create)
         * function onclick(e) = public function create(AccountEvents $event)
         */

        return [
           // AccountEvents::CREATE => 'create'
            AccountEvents::CREATE => 'create'
        ];
    }

    /*
     * un gestionnaire d'événement en paramètre
     */
    public function create()
    {
        dump('ok');
    }

}