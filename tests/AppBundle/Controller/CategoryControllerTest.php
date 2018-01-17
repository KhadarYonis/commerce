<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{

    /*
     * @dataProvider : fournisseur de données
     *      - doit retourner un array de données
     *      - les entrées du tableau deviennent des paramètres dans la fonction callback
     */


    public function listCategory()
    {
        return [
            [
                '/fr/category/catégorie0',
                'Catégorie'
            ],
            [
                '/en/category/category0',
                'Category'
            ]
        ];
    }

    /**
     * @dataProvider listCategory
     */
    public function testCategory(string $url, string $title)
    {
        // client : simule un navigateur
        $client = static::createClient();

        // crawler : récupére le DOM de l'URL ciblée
        $crawler = $client->request('GET', $url);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains($title, $crawler->filter('.container > .row:nth-of-type(2) h1')->text());
    }

}
