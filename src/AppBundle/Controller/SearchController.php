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
use Symfony\Component\HttpFoundation\Response;



class SearchController extends Controller
{
    /**
     * @Route("/search", name="search.index")
     */
    public function indexAction(ManagerRegistry $doctrine):Response
    {

        $categories = $doctrine->getRepository(Category::class)->findAll();
        $products = $doctrine->getRepository(Product::class)->findAll();


        return $this->render('search/index.html.twig', [
            "categories" => $categories,
            "products" => $products,
       ]);
    }

}