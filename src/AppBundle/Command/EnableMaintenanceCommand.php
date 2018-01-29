<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 29/01/18
 * Time: 09:40
 */

namespace AppBundle\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnableMaintenanceCommand extends Command
{
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
            ->setName('app:maintenance:enable')
            ->setDescription('Enable or disable maintenance mode')
            ->setHelp('You must use true or false as value')
            ->addArgument('value', InputArgument::REQUIRED, 'use true or false')
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
        // récuperation de l'argument value
        $value = $input->getArgument('value');

        // tester la valeur saisie
        if($value !== "true" && $value !== "false") {
            throw new InvalidArgumentException('You must use true or false as value');
        }

        // import du fichier
        $file = file_get_contents('app/config/maintenance.yml');

        //$content = preg_replace('/maintenance_enable: (true|false)/', "maintenance_enable: $value", $file);
        $content = preg_replace('/true|false/', $value, $file);


        // modification du contenu
        file_put_contents('app/config/maintenance.yml', $content);



        // sortie

        $message =  ($value === "true" ? 'Maintenance <comment> enable </comment>' : 'Maintenance <comment> disable </comment>');

        $output->writeln($message);

//        $output->writeln("<info> Mon message </info>");
//        $output->writeln("<error> Mon message 2</error>");
//        $output->writeln("<question> Mon message 3</question>");
//        $output->writeln("<comment> Mon message 4</comment>");

    }

}