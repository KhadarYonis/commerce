<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 15/01/18
 * Time: 15:20
 */

namespace AppBundle\Twig;


use AppBundle\Entity\Category;
use Doctrine\Common\Persistence\ManagerRegistry;

class AppExtention extends \Twig_Extension
{

        /*
       * injection de services dans une classe autre qu'un contrôleur
       *  créer une propriéte par service
       *  injecter les services par le constructeur
       */

    private $doctrine;
    private $twig;

    public function __construct(ManagerRegistry $doctrine, \Twig_Environment $twig)
        {
            $this->doctrine = $doctrine;
            $this->twig = $twig;


        }

        public function getFunctions()
        {
            return [
                new \Twig_SimpleFunction('render_menu', [$this, 'create_menu']),
            ];
        }

        public function create_menu()
        {
            $rc = $this->doctrine->getRepository(Category::class);
            $categories = $rc->findAll();

            //dump($categories);exit;
            return $this->twig->render('inc/menu.html.twig', [
                'categories' => $categories
            ]);
        }

        public function getName()
        {
            return 'app_extension';
        }

}