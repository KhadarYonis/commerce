<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 29/01/18
 * Time: 09:40
 */

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class MysqlDumpCommand extends Command
{
    private $mailer;

    public function __construct( \Swift_Mailer $mailer, $name = null)
    {
        parent::__construct($name);
        $this->mailer = $mailer;
    }


    protected function configure()
    {
        $this
            ->setName('app:mysql:dump')
            ->setDescription('Export Database and send it by mail')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = new \DateTime();
        $dateFormat = $date->format('Y-m-d');

        // export de la base
        $commond = "mysqldump -u root -ptroiswa commerce > $dateFormat-commerce.sql";

        // zip
        $commond .= " && zip $dateFormat-commerce.zip $dateFormat-commerce.sql";

        // suppression de l'export sql
        $commond .= " && rm $dateFormat-commerce.sql";

        // process: accès au terminal de l'os
        $process = new Process($commond);

        // exécution du process
        $process->run();

        // message de l'email
        $message = (new \Swift_Message("$dateFormat - dump mysql"))
            ->setFrom('contact@contact.com')
            ->setTo('admin@admin.com')
            ->setBody("$dateFormat - dump mysql")
            ->attach(\Swift_Attachment::fromPath("$dateFormat-commerce.zip"))
        ;

        // envoi  de l'émail

        $this->mailer->send($message);

        // récupération de la sortie du terminal
        $outputProcess =  $process->getOutput();

        // sortie
        $output->write($outputProcess);
        $output->writeln('Exported <comment> Database</comment>');
    }

}