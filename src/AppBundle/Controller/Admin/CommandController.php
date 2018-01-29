<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 23/01/18
 * Time: 11:50
 */

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;


/**
 * @Route("/admin")
 *
 */
class CommandController extends Controller
{


    /**
     * @Route("/mysql-dump", name="admin.command.mysql.dump")
     */
    public function mysqlDumpAction(KernelInterface $kernel):Response
    {
        // application
        $application = new Application($kernel);
        $application->setAutoExit(false);

        // équivalent à configure()
        $input = new ArrayInput(
            array(
            'command' => 'app:mysql:dump'
            )
        );

        // sortie de la console
        $output = new BufferedOutput();
        $application->run($input, $output);


        return $this->render('admin/command/mysqldump.html.twig', [
            'result' => $output->fetch()
        ]);
    }

}