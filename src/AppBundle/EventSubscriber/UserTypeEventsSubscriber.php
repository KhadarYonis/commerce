<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 22/01/18
 * Time: 16:20
 */

namespace AppBundle\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;

class UserTypeEventsSubscriber implements EventSubscriberInterface
{

    private $requestStack;

    // request en inclut que dans controller : utiliser requestStack autre que controller

    public function __construct(RequestStack $requestStack)
    {
        // masterRequest : cibler la requête principale
        $this->requestStack = $requestStack->getMasterRequest();
    }


    // getSubscribedEvents fait le lien entre un événement et son gestionnaire

    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.

        return [
            FormEvents::POST_SET_DATA => 'preSetData'
        ];
    }

    public function preSetData(FormEvent $event)
    {
        // récupérer la route

        $route = $this->requestStack->get('_route');

        // récupération de la saisie
        $data = $event->getData();

        // formulaire
        $form = $event->getForm();

        if($route === 'profile.manage.index') {

            $form->remove('username');
            $form->remove('password');
            $form->remove('email');
        }

      // dump($data, $form);
    }

}