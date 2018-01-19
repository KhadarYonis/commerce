<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 17/01/18
 * Time: 16:13
 */

namespace AppBundle\EventSubscriber;


use AppBundle\Events\AccountEvents;
use AppBundle\Events\UserTokenCreateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserTokenEventsSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }
    // obligatoire
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
            AccountEvents::PASSWORD_FORGET => 'create'
        ];
    }

    /*
     * un gestionnaire d'événement en paramètre
     */
    public function create(UserTokenCreateEvent $event)
    {
        /*
         * envoi d'un email
         *      service d'emailing : SwiftMailer
         *      message : Swift_Message
         *          setFrom() : méthode obligatoire //expéditeur
         *          setTo() : destinaire
         *          setBody() : corps du message
         *              par défaut : type text/plain > texte non enrichie
         *      $mailer->send()  : envoie le message
         */

        $message = (new \Swift_Message('Sujet du message'))
            ->setFrom('contact@website.com')
            ->setTo($event->getUser()->getUserEmail())
            ->setBody(
                $this->twig->render('emailing/password_forget.create.html.twig', [
                    'data' => $event->getUser()
                ]), 'text/html'
            )
        ;

        $this->mailer->send($message);

       // dump($event); exit;
    }

}