<?php

namespace GdmGent\Artestead;

use GdmGent\Artestead\Settings\JsonSettings;
use GdmGent\Artestead\Settings\YamlSettings;
use GdmGent\Artestead\Traits\GeneratesSlugs;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCommand extends Command
{
    use GeneratesSlugs;

    /**
     * The base path of the installation.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The name of the project folder.
     *
     * @var string
     */
    protected $projectName;

    /**
     * Slugified Project Name.
     *
     * @var string
     */
    protected $defaultName;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->basePath = getcwd();
        $this->projectName = basename($this->basePath);
        $this->defaultName = $this->slug($this->projectName);

        $this
            ->setName('make')
            ->setDescription('Install Artestead into the current project')
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'Type of hosting: drupal, laravel, symfony, wordpress') // New
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'The name of the virtual machine.', $this->defaultName)
            ->addOption('hostname', null, InputOption::VALUE_OPTIONAL, 'The hostname of the virtual machine.', str_replace('.local.local', '.local', "www.{$this->projectName}.local"))
            ->addOption('ip', null, InputOption::VALUE_OPTIONAL, 'The IP address of the virtual machine.')
            ->addOption('after', null, InputOption::VALUE_NONE, 'Determines if the after.sh file is created.')
            ->addOption('aliases', null, InputOption::VALUE_NONE, 'Determines if the aliases.sh file is created.')
            ->addOption('example', null, InputOption::VALUE_NONE, 'Determines if a Artestead example file is created.')
            ->addOption('json', null, InputOption::VALUE_NONE, 'Determines if the Artestead settings file will be in json format.');
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (! $this->vagrantfileExists()) {
            $this->createVagrantfile();
        }

        if (/*$input->getOption('aliases') && */! $this->aliasesFileExists()) {
            $this->createAliasesFile();
        }

        if (/*$input->getOption('after') && */! $this->afterShellScriptExists()) {
            $this->createAfterShellScript();
        }

        if (! $this->gitignoreExists()) {
            $this->createGitignore();
        }

        $format = $input->getOption('json') ? 'json' : 'yaml';

        if (! $this->settingsFileExists($format)) {
            $this->createSettingsFile($format, [
                'name' => $input->getOption('name'),
                'hostname' => $input->getOption('hostname'),
                'ip' => $input->getOption('ip'),
                'type' => $input->getOption('type'),
            ]);
        }

        if ($input->getOption('example') && ! $this->exampleSettingsExists($format)) {
            $this->createExampleSettingsFile($format);
        }

        $this->checkForDuplicateConfigs($output);

        $this->configureAliases();

        $output->writeln('Artestead Installed!');

        $command = 'composer require gdmgent/artestead';
        passthru($command);
    }

    /**
     * Determine if the Vagrantfile exists.
     *
     * @return bool
     */
    protected function vagrantfileExists()
    {
        return file_exists("{$this->basePath}/Vagrantfile");
    }

    /**
     * Create a Vagrantfile.
     *
     * @return void
     */
    protected function createVagrantfile()
    {
        copy(__DIR__.'/../resources/LocalizedVagrantfile', "{$this->basePath}/Vagrantfile");
    }

    /**
     * Determine if the aliases file exists.
     *
     * @return bool
     */
    protected function aliasesFileExists()
    {
        return file_exists("{$this->basePath}/aliases.sh");
    }

    /**
     * Create aliases file.
     *
     * @return void
     */
    protected function createAliasesFile()
    {
        copy(__DIR__.'/../resources/aliases.sh', "{$this->basePath}/aliases.sh");
    }

    /**
     * Determine if the after shell script exists.
     *
     * @return bool
     */
    protected function afterShellScriptExists()
    {
        return file_exists("{$this->basePath}/after.sh");
    }

    /**
     * Create the after shell script.
     *
     * @return void
     */
    protected function createAfterShellScript()
    {
        copy(__DIR__.'/../resources/after.sh', "{$this->basePath}/after.sh");
    }

    /**
     * Determine if the .gitignore file exists.
     *
     * @return bool
     */
    protected function gitignoreExists()
    {
        return file_exists("{$this->basePath}/.gitignore");
    }

    /**
     * Create the .gitignore file.
     *
     * @return void
     */
    protected function createGitignore()
    {
        copy(__DIR__.'/../resources/gitignore', "{$this->basePath}/.gitignore");
    }

    /**
     * Determine if the settings file exists.
     *
     * @param  string  $fileExtension
     * @return bool
     */
    protected function settingsFileExists($fileExtension)
    {
        return file_exists("{$this->basePath}/Artestead.{$fileExtension}");
    }

    /**
     * Create the artestead settings file.
     *
     * @param  string  $format
     * @param  array  $options
     * @return void
     */
    protected function createSettingsFile($format, $options)
    {
        $SettingsClass = ($format === 'json') ? JsonSettings::class : YamlSettings::class;

        $filename = $this->exampleSettingsExists($format) ?
            "{$this->basePath}/Artestead.{$format}.example" :
            __DIR__."/../resources/Artestead.{$format}";

        $settings = $SettingsClass::fromFile($filename);

        if (! $this->exampleSettingsExists($format)) {
            $settings->updateName($options['name'])
                     ->updateHostname($options['hostname']);
        }

        $settings->updateIpAddress($options['ip'])
                 ->configureSites($this->projectName, $this->defaultName, $options['type'])
                 ->configureSharedFolders($this->basePath, $this->defaultName)
                 ->save("{$this->basePath}/Artestead.{$format}");
    }

    /**
     * Determine if the example settings file exists.
     *
     * @param  string  $format
     * @return bool
     */
    protected function exampleSettingsExists($format)
    {
        return file_exists("{$this->basePath}/Artestead.{$format}.example");
    }

    /**
     * Create the artestead settings example file.
     *
     * @param  string  $format
     * @return void
     */
    protected function createExampleSettingsFile($format)
    {
        copy("{$this->basePath}/Artestead.{$format}", "{$this->basePath}/Artestead.{$format}.example");
    }

    /**
     * Checks if JSON and Yaml config files exist, if they do
     * the user is warned that Yaml will be used before
     * JSON until Yaml is renamed / removed.
     *
     * @param  OutputInterface  $output
     * @return void
     */
    protected function checkForDuplicateConfigs(OutputInterface $output)
    {
        if (file_exists("{$this->basePath}/Artestead.yaml") && file_exists("{$this->basePath}/Artestead.json")) {
            $output->writeln(
                '<error>WARNING! You have Artestead.yaml AND Artestead.json configuration files</error>'
            );
            $output->writeln(
                '<error>WARNING! Artestead will not use Artestead.json until you rename or delete the Artestead.yaml</error>'
            );
        }
    }

    /**
     * Updates the aliases file with a shortcut to the project folder.
     */
    protected function configureAliases()
    {
        $file = "{$this->basePath}/aliases.sh";
        if (file_exists($file)) {
            $fileContent = file_get_contents($file);
            $fileContent = str_replace(
                'alias p=\'cd ~/Code/artestead\'',
                'alias p=\'cd ~/Code/'.$this->defaultName.'\'',
                $fileContent
            );
            file_put_contents($file, $fileContent);
        }
    }
}
