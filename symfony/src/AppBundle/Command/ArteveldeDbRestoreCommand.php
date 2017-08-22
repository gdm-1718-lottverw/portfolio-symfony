<?php
namespace AppBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
/**
 * Class ArteveldeDbRestoreCommand.
 *
 * Use:
 * $ console artevelde:db:restore
 *
 * @author Olivier Parent <olivier.parent@arteveldehs.be>
 * @copyright Copyright © 2016-2017, Artevelde University College Ghent
 */
class ArteveldeDbRestoreCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('artevelde:db:restore')
            ->setDescription('Restores the database from latest backup SQL dump')
        ;
    }
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        // Get variables from `app/config/parameters.yml`
        $dbName = $container->getParameter('database_name');
        $dbUsername = $container->getParameter('database_user');
        $dbPassword = $container->getParameter('database_password');
        $dbDumpPath = $container->getParameter('database_dump_path');
        $command = "MYSQL_PWD=${dbPassword} mysqldump --user=${dbUsername} ${dbName} < ${dbDumpPath}/latest.sql";
        exec($command);
        $output->writeln("Backup for database `${dbName}` restored!");
    }
}