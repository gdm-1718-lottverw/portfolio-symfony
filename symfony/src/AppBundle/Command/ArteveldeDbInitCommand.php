<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ArteveldeDbInitCommand.
 *
 * Use:
 * $ console artevelde:db:init --migrate --seed
 *
 * @author Olivier Parent <olivier.parent@arteveldehs.be>
 * @copyright Copyright © 2016-2017, Artevelde University College Ghent
 */
class ArteveldeDbInitCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('artevelde:db:init')
            ->setDescription('Initializes the database by creating database user and database')
            ->addOption('migrate', null, InputOption::VALUE_NONE, 'Migrates Doctrine Migrations')
            ->addOption('seed', null, InputOption::VALUE_NONE, 'Loads Doctrine Fixtures');
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
            'artevelde:db:user',
            'doctrine:database:create',
            'doctrine:schema:create',
        ];

        if ($input->getOption('migrate')) {
            $commands[] = 'doctrine:migrations:migrate';
        }

        if ($input->getOption('seed')) {
            $commands[] = 'doctrine:fixtures:load';
        }

        foreach ($commands as $commandName) {
            $parameters = [
                'command' => $commandName,
            ];
            $commandInput = new ArrayInput($parameters);

            if (in_array($commandName, ['doctrine:fixtures:load', 'doctrine:migrations:migrate'])) {
                $commandInput->setInteractive(false);
            }

            $application
                ->find($commandName)
                ->run($commandInput, $output);
        }

        $output->writeln("Database `${dbName}` initialized!");
    }
}