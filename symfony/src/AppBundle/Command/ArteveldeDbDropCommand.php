<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ArteveldeDbDropCommand.
 *
 * Use:
 * $ console artevelde:db:drop
 *
 * @author Olivier Parent <olivier.parent@arteveldehs.be>
 * @copyright Copyright © 2016-2017, Artevelde University College Ghent
 */
class ArteveldeDbDropCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('artevelde:db:drop')
            ->setDescription('Drops the database')
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

        $application = $this->getApplication();

        $commands = [
            'doctrine:database:drop' => ['--force' => true],
        ];

        foreach ($commands as $commandName => $commandParameters) {
            $parameters = [
                'command' => $commandName,
            ];
            if (is_array($commandParameters)) {
                foreach ($commandParameters as $commandParameter => $value) {
                    $parameters[$commandParameter] = $value;
                }
            }
            $commandInput = new ArrayInput($parameters);

            $application
                ->find($commandName)
                ->run($commandInput, $output);
        }

        $output->writeln("Database `${dbName}` dropped!");
    }
}