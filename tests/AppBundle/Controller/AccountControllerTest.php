<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{

    /*
     * @dataProvider : fournisseur de données
     *      - doit retourner un array de données
     *      - les entrées du tableau deviennent des paramètres dans la fonction callback
     */

    public function listRoutes()
    {
        return [
            [
                '/fr/register',
                'S\'enregistrer',
                'Valider'
            ],
            [
                '/en/register',
                'Sign up',
                'Submit'
            ]
        ];
    }

    /**
     * @dataProvider listRoutes
     */
    public function testRoutes(string $url, string $title, string $button)
    {
        // client : simule un navigateur
        $client = static::createClient();


        // suivre toutes les redirections
        $client->followRedirects();

        // crawler : récupére le DOM de l'URL ciblée
        $crawler = $client->request('GET', $url);


        // asset[..](statusVoulu, statusRetourner)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains($title, $crawler->filter('.container > .row:nth-of-type(2) h1')->text());

        /*
         * données du formulaire
         *      - array associatif :
         *          clé : name du champ de saisie
         *          valeur : valeur saisie
         */

        $formData =[
            'appbundle_user[username]' => 'user' . time(),
            'appbundle_user[password]' => 'user',
            'appbundle_user[email]' => 'user' . time() . '@user.com',
        ];

        // sélectionner le formulaire par le bouton
        $form = $crawler->selectButton($button)->form($formData);

        // soumission du formulaire : mettre à jour le DOM
        $crawler = $client->submit($form);

        //dump($crawler);

        // tests sur la page d'atterrissage
        $this->assertEquals(1, $crawler->filter('.alert.alert-success')->count());




    }

}
