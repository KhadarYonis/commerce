<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageControllerTest extends WebTestCase
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
                '/fr/',
                'Catégories',
                'Besoin d\'idées ?'
            ],
            [
                '/en/',
                'Categories',
                'Need ideas ?'
            ]
        ];
    }

    /**
     * @dataProvider listRoutes
     */
    public function testRoutes(string $url, string $title, string $idea)
    {
        // client : simule un navigateur
        $client = static::createClient();

        // crawler : récupére le DOM de l'URL ciblée
        $crawler = $client->request('GET', $url);

        // asset[..](statusVoulu, statusRetourner)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains($title, $crawler->filter('.container > .row:nth-of-type(2) h1')->text());
        $this->assertContains($idea, $crawler->filter('.container > .row:nth-of-type(3) h1')->text());


        // compter le nombre de boutons catégories
        $this->assertGreaterThan(0, $crawler->filter('.container > .row:nth-of-type(2) .btn', ['test ok'])->count());

        // compter les 6 produits aléatoire
        $this->assertEquals(6, $crawler->filter('.container > .row:nth-of-type(3) .col-sm-4')->count());
        $this->assertFalse($crawler->filter('.container > .row:nth-of-type(3) .col-sm-4')->count() === 2);


    }

}
