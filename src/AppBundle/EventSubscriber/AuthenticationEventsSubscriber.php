<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 18/01/18
 * Time: 16:15
 */

namespace AppBundle\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;

class AuthenticationEventsSubscriber implements EventSubscriberInterface
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.

        return [
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'authenticationFailure'
        ];
    }

    public function authenticationFailure(AuthenticationFailureEvent $event)
    {
        //dump('fail');exit;

        /*
         * session : ne pas utiliser $_SESSION
         *      service : SessionInterface
         *      méthodes :
         *          set(clé, valeur) : créer une entrée
         *          get(clé) : récupérer la valeur d'une entrée
         *          has(clé) : tester l'existence d'une entrée
         *          remove(clé) : supprimer une entrée
         *          clear() : vider la session
         *          invalidate() : stopper la session
         */

        // tester si la clé existe (clé choisi par nous même authentication_failure)

        if($this->session->has('authentication_failure')) {

            // récupération  de la valeur
            $value = $this->session->get('authentication_failure');

            // si les 3 échecs ne sont pas atteints
            if($value < 3) {
                $value++;
                $this->session->set('authentication_failure', $value);
            }
        } else {
            $this->session->set('authentication_failure', 1);

        }

        dump($this->session->get('authentication_failure')); exit;
    }

}