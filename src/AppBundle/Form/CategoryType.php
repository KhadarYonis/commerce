<?php

namespace AppBundle\Form;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{

    private $locales;
    private $doctrine;

    public function __construct(array $locales, ManagerRegistry $doctrine)
    {
        $this->locales = $locales;
        $this->doctrine = $doctrine;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // récupération des données du formulaire (ex: entité)

        $entity = $builder->getData();

        //dump($entity); exit;
        // créer plusieurs champs selon les langues

        foreach ($this->locales as $key => $value) {
            /*
             * mapped : permet de définir si un champ est relié à une propriété de l'entité; par défaut true
             * data : permet de définir une valeur au champ
             */

            $builder
                // champ name
                ->add("name_$value", TextType::class , [
                    'mapped' => false,
                    'data' => $entity->translate($value)->getName()
                ])
                // champ description
                ->add("description_$value", TextareaType::class , [
                    'mapped' => false,
                    'data' => $entity->translate($value)->getDescription()
                ])
            ;
        }

        // écouter : récuperer la saisie et de fusionner les traductions

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
            // saisi du formulaire
            $data =  $event->getData();


            // données du formulaire
            $form = $event->getForm();

            $entity = $form->getData();



            // création des traductions

            foreach ($this->locales as $key => $value) {

                // méthode translate est fourni par doctrine behaviors
                $entity->translate($value)->setName($data["name_$value"]);
                $entity->translate($value)->setDescription($data["description_$value"]);
            }

            $entity->mergeNewTranslations();




             //dump($entity); exit;
        });

    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Category'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_category';
    }


}
