<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="homepage.index")
     */
    public function indexAction(ManagerRegistry $doctrine, Request $request):Response
    {

        //récupération de la langue
        $locale = $request->getLocale();

        //récupération des catégories

        $categories = $doctrine->getRepository(Category::class)->getCategoriesByLocaleWithProductsCount($locale);

        $products = $doctrine->getRepository(Product::class)->getProductsRandom($locale);

        //dump($products);exit;


        return $this->render('homepage/index.html.twig', [
            'categories' => $categories,
            'products' => $products
        ]);
    }

    /**
     * @Route("/category/{slugCategory}", name="homepage.category")
     */
    public function categoryAction(ManagerRegistry $doctrine, Request $request, string $slugCategory):Response
    {

        //récupération de la langue
        $locale = $request->getLocale();

        $products = $doctrine->getRepository(Category::class)->getProductsByCategory($locale, $slugCategory);

        //dump($products);exit;

        return $this->render('homepage/category.html.twig', [
            "products" => $products
        ]);
    }


    /**
     * @Route("/product/{slugProduct}", name="homepage.product")
     */

    public function productIndex(ManagerRegistry $doctrine, Request $request, string $slugProduct)
    {
        //récupération de la langue
        $locale = $request->getLocale();

        $product = $doctrine->getRepository(Product::class)->getOneProduct($locale, $slugProduct);

        //dump($product);exit;

        return $this->render('homepage/product.html.twig', [
            "product" => $product
        ]);
    }
}
