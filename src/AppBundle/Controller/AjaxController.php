<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 16/01/18
 * Time: 16:25
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/ajax")
 */
class AjaxController extends Controller
{
    /**
     * @Route("/cookies-disclaimer", name="ajax.cookies.disclaimer")
     */
    public function cookiesDisclaimerAction(Request $request, SessionInterface $session):JsonResponse
    {
        // récupération de la variable POST envoyée par le JS
        $disclaimerValue = $request->get('disclaimerValue');

        /*
         * route appelée en AJAX avec symfony
         *      - pas de vue
         *      - la route retourne du JSON avec JsonResponse
         */

        // modification de la valeur en session

        $session->set('cookiesDisclaimer', $disclaimerValue);

        $response = new JsonResponse([
            'success' => 'ok'
        ]);

        return $response;
    }
}
