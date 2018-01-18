<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 16/01/18
 * Time: 16:25
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Events\AccountCreateEvent;
use AppBundle\Events\AccountEvents;
use AppBundle\Form\UserType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

class AccountController extends Controller
{
    /**
     * @Route("/register", name="account.register")
     */
    public function registerAction(ManagerRegistry $doctrine, Request $request, TranslatorInterface $translator, EventDispatcherInterface $dispatcher):Response
    {
        // création d'un formulaire



        $user = new User();

        $type = UserType::class;

        $form = $this->createForm($type, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

           // dump($data); exit;

            //insertion

            $em = $doctrine->getManager();

            $em->persist($data);

            $em->flush();

            // message flash

            $this->addflash('notice', $translator->trans('flash_message.new_user'));

            // événement
            $event = new AccountCreateEvent();

            // $data objet user
            $event->setUser($data);


            // déclencher l'événement AccountEvents::CREATE (dispatch declencher comme addEventListener)

            $dispatcher->dispatch(AccountEvents::CREATE, $event);

            //exit;

            // redirection
            return $this->redirectToRoute('security.login');

        }


        return $this->render('account/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}