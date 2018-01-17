<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 16/01/18
 * Time: 14:25
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends  Controller
{
    /**
     * @Route("/login", name="security.login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/logout", name="security.logout")
     */
    public function logout()
    {

        // methode non appelé
    }


    /**
     * @Route("/redirect-by-role", name="security.redirect.by.role")
     */
    public function redirectByRole()
    {
        // récupération de l'utilisateur vient extends controll er

        $user = $this->getUser();


        // récupération du rôle get roles vient user entity

        $roles = $user->getRoles();

        //dump($roles);exit;

        // test sur le role

        if(in_array('ROLE_ADMIN', $roles)) {
            return $this->redirectToRoute('admin.homepage.index');
        } else {
            return $this->redirectToRoute('profile.homepage.index');
        }
    }
}