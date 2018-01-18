<?php

namespace AppBundle\Controller\Profile;

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
                '/fr/profile/',
                'Profile'
            ],
            [
                '/en/profile/',
                'Profile'
            ]
        ];
    }

    /**
     * @dataProvider listRoutes
     */
    public function testRoutes(string $url, string $title)
    {
        // client : simule un navigateur
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user2',
            'PHP_AUTH_PW'   => 'user2'
        ]);

        // crawler : récupére le DOM de l'URL ciblée
        $crawler = $client->request('GET', $url);

        // asset[..](statusVoulu, statusRetourner)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains($title, $crawler->filter('.container > .container h1')->text());

        $this->assertEquals(1, $crawler->filter('.container > .container h1')->count());
    }


}
