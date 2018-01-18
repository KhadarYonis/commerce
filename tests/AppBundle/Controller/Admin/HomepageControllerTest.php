<?php

namespace AppBundle\Controller\Admin;

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
                '/fr/admin/',
                'Dashborad - Bonjour admin'
            ],
            [
                '/en/admin/',
                'Dashborad - Bonjour admin'
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
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin'
        ]);

        // crawler : récupére le DOM de l'URL ciblée
        $crawler = $client->request('GET', $url);

        // asset[..](statusVoulu, statusRetourner)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains($title, $crawler->filter('.container > .container h1')->text());

        $this->assertEquals(1, $crawler->filter('.container > .container h1')->count());
    }


}
