<?php

namespace AppBundle\Controller\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 *
 */
class HomepageController extends Controller
{
    /**
     * @Route("/", name="admin.homepage.index")
     */
    public function indexAction(ManagerRegistry $doctrine, Request $request):Response
    {

        return $this->render('admin/homepage/index.html.twig');
    }


}
