<?php

namespace AppBundle\Form;

use AppBundle\EventSubscriber\UserTypeEventsSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    // injecter la pile de requêtes

    private $requestStack;
    private $request;

    public function __construct(RequestStack $requestStack)
    {
        // masterRequest : cibler la requête principale
        $this->requestStack = $requestStack;
        $this->request = $requestStack->getMasterRequest();

//        dump($this->requestStack);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'username'
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'password'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'email.notblank'
                    ]),
                    new Email([
                        'message' => 'email.incorrect'
                    ])
                ]
            ])
            ->add('address', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'address'
                    ])
                ]
            ])
            ->add('zipCode', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'zipCode'
                    ])
                ]
            ])
            ->add('city', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'city'
                    ])
                ]
            ])
            ->add('country', CountryType::class, [
                'placeholder' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => 'country'
                    ])
                ]
            ])
        ;


        /*
         *  écouter : écouter un seul événement
         *  souscripteur : écouter plusieurs événements
         *
         * FormEvents -> la famille
         * FormEvent -> l'événement à écouter
         *
         */

        // souscripteur oblige RequestStack

        $subscriber = new UserTypeEventsSubscriber($this->requestStack);

        $builder->addEventSubscriber($subscriber);

        // écouter
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event){
            // récupérer le nom de la route
            $route = $this->request->get('_route');


            // tester la route
            //  création de compte

            if($route === 'account.register') {
                /*
                 *  l'événement renvoie
                 *     $event->getData() : saisie du formulaire
                 *     $event->getForm() : $builder du formulaire
                 *     $event->getForm()->getData() : données du formulaire (entité, modèle...)
                 */

                // récupération de la saisie
                $data = $event->getData();

                // formulaire
                $form = $event->getForm();

                // donnnées du formulaire
                $entity = $form->getData();

                // supprimer les champs du formulaire

                $form->remove('address');
                $form->remove('zipCode');
                $form->remove('city');
                $form->remove('country');

                //dump($data, $form, $entity);


            }
        });


    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
