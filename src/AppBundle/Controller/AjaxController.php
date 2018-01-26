<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 16/01/18
 * Time: 16:25
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\ExchangeRate;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/ajax")
 */
class AjaxController extends Controller
{

    /**
     * @Route("/search", name="ajax.search")
     */
    public function searchAction(Request $request, ManagerRegistry $doctrine):Response
    {
        // récupération de la variable POST envoyée par le JS
        $selectValue = $request->get('selectValue');

        // produits de la catégorie
        $category = $doctrine->getRepository(Category::class)->find($selectValue);

        // supprimer les références avec les propriétés bidirectionnelles

        $objectNormalizer = new ObjectNormalizer();

        //callable reference d'une fonction qui est executable quand on l'appelle (c'est genre comme click, onclick)
        $objectNormalizer->setCircularReferenceHandler(function ($obj){
            return $obj;
        });



        /*
         * normaliers : format d'entrée des données
         * encoders : format de sortie des données
         */

        $normalizes = [$objectNormalizer];

        $encoders = [new JsonEncoder(), new XmlEncoder()];

        // serializer
        $serializer = new Serializer($normalizes, $encoders);


        // sérialisation
        $results = $serializer->serialize($category, 'json');

        // response
        $response = new Response($results);

        return $response;
    }

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



    /**
     * @Route("/currency", name="ajax.currency")
     */
    public function currencyAction(Request $request, ManagerRegistry $doctrine)
    {
        $selectValue = $request->get('selectValue');


        $exchangeRate = $doctrine->getRepository(ExchangeRate::class)->findOneBy(['device' => $selectValue]);

        $normalizes = [ new ObjectNormalizer()];

        $encoders = [new JsonEncoder(), new XmlEncoder()];

        // serializer
        $serializer = new Serializer($normalizes, $encoders);


        // sérialisation
        $results = $serializer->serialize($exchangeRate, 'json');


        // response
        $response = new Response($results);

        return $response;


    }



}
