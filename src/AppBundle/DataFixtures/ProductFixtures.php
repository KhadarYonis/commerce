<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 12/01/18
 * Time: 12:22
 */

namespace  AppBundle\DataFixtures;

use AppBundle\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    private $locales = ['en' => 'en_US', 'fr' => 'fr_FR'];
    
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 40; $i++) {

            $faker = \Faker\Factory::create();

            // cibler les propriétés non traduites
            $product = new Product();

            $product->setPrice($faker->randomFloat(2,1,999.99));
            $product->setStock($faker->numberBetween(0, 100));
            /*
             * image
             *      cibler la racine du projet
             *      le dossier ciblé doit exister
             */
            $product->setImage(
                    $faker->image(
                        'web/img/product',
                        '400',
                        '400',
                        'cats',
                        false
                    )
            );

            // associer le produit à une catégorie
            $product->setCategory(
              $this->getReference("category" . $faker->numberBetween(0, 3))
            );


            foreach($this->locales as $key => $value) {

                // use the factory to create a Faker\Generator instance
                $faker = \Faker\Factory::create($value);



                //créer des valeurs traduits pour les propriétés
                $name = ($key === 'fr') ? 'produit' : 'product';
                //$description = ($value === 'fr') ? 'description' : 'description';

                $description = $faker->realText();

                // méthode translate est fourni par doctrine behaviors
                $product->translate($key)->setName($name . $i);
                $product->translate($key)->setDescription($description);
            }

            //méthode mergeNewTranslations est fourni par doctrine behaviors
            $product->mergeNewTranslations();



            $manager->persist($product);
        }

        $manager->flush();


    }

    public function getDependencies()
    {
        return array(
            CategoryFixtures::class
        );
    }
}