<?php

namespace AppBundle\Controller\Profile;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/profile")
 *
 */
class HomepageController extends Controller
{
    /**
     * @Route("/", name="profile.homepage.index")
     */
    public function indexAction():Response
    {

        return $this->render('profile/homepage/index.html.twig');
    }


}
