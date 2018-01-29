<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 29/01/18
 * Time: 09:40
 */

namespace AppBundle\Command;


use AppBundle\Entity\ExchangeRate;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateExchangeRateCommand extends Command
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine, $name = null)
    {
        parent::__construct($name);
        $this->doctrine = $doctrine;
    }

    /**
     * configuration de la commande
     *      setName: nom de la commande -> obligatoire
     *      setDescription : description
     *      setHelp: aide // accessible avec l'option -h
     *      addArgument: ajouter un argument -> par défaut optionnel
     *      addOption: ajouter une option
     */
    protected function configure()
    {
        $this
            ->setName('app:exchange:rate:update')
            ->setDescription('Update exchange rate in database')
        ;
    }

    /**
     * exécution de la commande
     *      $input: permet de récupérer les arguments définissent dans cinfigure()
     *      $output: affichage de sortie de la console
     *          - style: <info> / <error> / <question> / <comment>
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // récuperation le taux de change
        $results = json_decode(file_get_contents('https://api.fixer.io/latest?symbols=USD,GBP'));

        // mis à jour dns la table
        $update = $this->doctrine->getRepository(ExchangeRate::class)->updateExchangerate($results->rates);

        // sortie
        $output->writeln('Exchange rates <question> updated </question>');
        foreach ($results->rates as $key => $value) {
            $output->writeln("$key <question> $value </question>");
        }
    }

}