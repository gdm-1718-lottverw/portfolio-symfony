<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ArteveldeDbResetCommand.
 *
 * Use:
 * $ console artevelde:db:reset --migrate --seed
 *
 * @author Olivier Parent <olivier.parent@arteveldehs.be>
 * @copyright Copyright © 2016-2017, Artevelde University College Ghent
 */
class ArteveldeDbResetCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('artevelde:db:reset')
            ->setDescription('Drops database and runs artevelde:db:init')
            ->addOption('migrate', null, InputOption::VALUE_NONE, 'Migrates Doctrine Migrations')
            ->addOption('seed', null, InputOption::VALUE_NONE, 'Loads Doctrine Fixtures');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        // Get variables from `app/config/parameters.yml`
        $dbName = $container->getParameter('database_name');

        $application = $this->getApplication();

        $commands = [
            'artevelde:db:drop' => null,
            'artevelde:db:init' => null,
        ];

        if ($input->getOption('migrate') || $input->getOption('seed')) {
            $options = [];

            if ($input->getOption('migrate')) {
                $options['--migrate'] = true;
            }

            if ($input->getOption('seed')) {
                $options['--seed'] = true;
            }

            $commands['artevelde:db:init'] = $options;
        }

        foreach ($commands as $commandName => $commandParameters) {
            $parameters = [
                'command' => $commandName,
            ];
            $commandInput = new ArrayInput($parameters);

            $application
                ->find($commandName)
                ->run($commandInput, $output);
        }

        $output->writeln("Database `${dbName}` reset!");
    }
}