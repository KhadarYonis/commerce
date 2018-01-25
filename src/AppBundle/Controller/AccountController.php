<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 16/01/18
 * Time: 16:25
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Entity\UserToken;
use AppBundle\Events\AccountCreateEvent;
use AppBundle\Events\AccountEvents;
use AppBundle\Events\UserTokenCreateEvent;
use AppBundle\Form\UserResetpassword;
use AppBundle\Form\UserTokenType;
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

    /**
     * @Route("/password.forget", name="account.password.forgot")
     */

    public function resetPasswordAction(Request $request, EventDispatcherInterface $dispatcher, ManagerRegistry $doctrine)
    {
        $userToken =  new UserToken();

        // on récupère le formulaire
        $form = $this->createForm(UserTokenType::class, $userToken);

        // récuperation des données dans la requète
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $data->setToken(bin2hex(random_bytes(12)));




          //  $interval = ;


            // recupérer l'objet user qui posséde un mail que on vient de taper s'il existe sinon return null
            $user = $doctrine->getRepository(User::class)->findOneBy(['email' => $data->getUserEmail()]);

            $userTokenEmail = $doctrine->getRepository(UserToken::class)->findOneBy([
                'userEmail' => $data->getUserEmail()
            ]);


            // événement
            $event = new UserTokenCreateEvent();

            // $data objet userToken

            $event->setUser($data);

            if($user) {


                $data->setExpirationDate(new \DateTime('+1 day'));

                if (!$userTokenEmail && date_diff($data->getExpirationDate(), new \DateTime())->format('%d') <= "1") {

                    $dispatcher->dispatch(AccountEvents::PASSWORD_FORGET, $event);

                    // on enregistre le contact dans bdd
                    $em = $this->getDoctrine()->getManager();

                    $em->persist($data);

                    $em->flush();
                }

            }

            // message flash

            $this->addFlash('notice', 'Un mail a été envoyé sur ta boîte mail.');
            // redirection
            return $this->redirectToRoute('account.password.forgot');
        }


        // on generer le html du formulaire crée
        $formView = $form->createView();


        return $this->render('account/password.forgot.html.twig', [
            'form' => $formView
        ]);
    }



    /**
     * @Route("/password/recovery/{email}/{token}", name="account.reset.password")
     */

    public function reset_passwordAction(ManagerRegistry $doctrine, Request $request, string $email, string $token):Response
    {

        $rc = $doctrine->getRepository(UserToken::class);


        $userToken = $rc->findOneBy([
            'userEmail' => $email,
            'token' => $token
        ]);


        $user = $doctrine->getRepository(User::class)->findOneBy(['email' => $userToken->getUserEmail()]);

        // on récupère le formulaire
        $form = $this->createForm(UserResetpassword::class, $user);

        // récuperation des données dans la requète
       $form->handleRequest($request);

        //if($form->isSubmitted() && $form->isValid()) {
            //if($userToken) {
                $data = $form->getData();
           // }
        //}

        // on generer le html du formulaire crée
        $formView = $form->createView();
        //dump($userToken->getUserEmail(), $user, $data); exit;

            return $this->render('account/password.reset.html.twig', [
                'form' => $formView
            ]);


    }
}