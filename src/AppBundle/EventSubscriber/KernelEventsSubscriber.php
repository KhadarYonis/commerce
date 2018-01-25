<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 25/01/18
 * Time: 09:40
 */

namespace AppBundle\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelEventsSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $maintenanceEnable;
    private $session;

    public function __construct(\Twig_Environment $twig, bool $maintenanceEnable, SessionInterface $session)
    {
        $this->twig = $twig;
        $this->maintenanceEnable = $maintenanceEnable;
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        /*
         * définir plusieurs méthodes à un événement
         *      créer un array de array : paramètres
         *          - non de la méthode
         *          - priorité de l'événement (par défaut 0)
         */


        return [
            KernelEvents::REQUEST => 'maintenanceMode',
            KernelEvents::RESPONSE => [
                [ 'redirect404' ],
                [ 'addSecurityHeaders' ],
                [ 'cookiesDisclaimer' ]
            ]
        ];
    }

    public function maintenanceMode(GetResponseEvent $event)
    {
        // si la maintenance dans maintenance.yml

        if($this->maintenanceEnable) {

            // contenu
            $content = $this->twig->render('maintenance/index.html.twig');

            /*
             * modifier / remplacer la réponse
             * $event->setResponse : envoie d'une nouvelle résponse (Response / RedirectResponse / JsonResponse)
             */
            $response = new Response($content, 503);

            // retourner la réponse
            $event->setResponse($response);
        }

    }

    public function redirect404(FilterResponseEvent $event)
    {

        // code HTTP
        $code = $event->getResponse()->getStatusCode();

        // 404
        if($code === 404) {
            // résponse
            $response = new RedirectResponse('/fr/');

            // retourner la réponse

            $event->setResponse($response);
        }

    }

    public function addSecurityHeaders(FilterResponseEvent $event)
    {
        /*
         * default-src https:
         * self
         */
        $response = new Response(
            $event->getResponse()->getContent(),
            $event->getResponse()->getStatusCode(),
            [
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'DENY',
                'X-XSS-Protection' => '1; mode=block'
            ]
        );

        $event->setResponse($response);


    }

    public function cookiesDisclaimer(FilterResponseEvent $event)
    {

        // test l'existance de la clé dans la session
        if(!$this->session->has('cookiesDisclaimer')) {
            $this->session->set('cookiesDisclaimer', true);
        }

        // récupérer la valeur en session
        $sessionValue = $this->session->get('cookiesDisclaimer');


        $content = $event->getResponse()->getContent();

        // remplacement de contenu si la clé en session est true

        if ($sessionValue === true) {
            $content = str_replace('<body>', '<body>
            <div class="alert alert-warning alert-dismissible fade show" role="alert"> Ce site utilise des cookies
                <button type="button" class="close close-cookies-disclaimer" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
             </button>
</div>', $content);
        }

        // retourner la réponse
        $response = new Response($content);

        $event->setResponse($response);
    }
}
