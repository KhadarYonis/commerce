<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 16/01/18
 * Time: 16:25
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class SearchController extends Controller
{
    /**
     * @Route("/search", name="search.index")
     */
    public function indexAction(ManagerRegistry $doctrine, Request $request):Response
    {
        // récuperation de la saisie
        // request->request POST
        // request->query GET
        // request REQUEST

        $search = $request->request->get('search');

        // récupération de la locale
        $locale = $request->getLocale();

        $categories = $doctrine->getRepository(Category::class)->findAll();

        if(!$search) {
            $products = $doctrine->getRepository(Product::class)->findAll();
        }

        else {
            $products = $doctrine->getRepository(Product::class)->getSearchResults($locale, $search);
        }


        return $this->render('search/index.html.twig', [
            "categories" => $categories,
            "products" => $products,
       ]);
    }

}