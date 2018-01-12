<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="homepage.index")
     */
    public function indexAction()
    {
        // replace this example code with whatever you need
        return $this->render('homepage/index.html.twig');
    }
}
