<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 29/01/18
 * Time: 09:40
 */

namespace AppBundle\Command;


use AppBundle\Entity\UserToken;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteExpirationUserTokensCommand extends Command
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine, $name = null)
    {
        parent::__construct($name);
        $this->doctrine = $doctrine;
    }

    protected function configure()
    {
        $this
            ->setName('app:delete:expiration:tokens')
            ->setDescription('Delete expiration reset password tokens in database')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // suppresion des tokens
        $delete = $this->doctrine->getRepository(UserToken::class)->deletedExpiratedTokens();

        // sortie

        $output->writeln('Expired tokens <comment> deleted</comment>');
    }

}