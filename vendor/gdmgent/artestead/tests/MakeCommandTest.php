<?php

namespace Tests;

use Symfony\Component\Yaml\Yaml;
use GdmGent\Artestead\MakeCommand;
use Tests\Traits\GeneratesTestDirectory;
use PHPUnit\Framework\TestCase as TestCase;
use GdmGent\Artestead\Traits\GeneratesSlugs;
use Symfony\Component\Console\Tester\CommandTester;

class MakeCommandTest extends TestCase
{
    use GeneratesSlugs, GeneratesTestDirectory;

    /** @test */
    public function it_displays_a_success_message()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertContains('Artestead Installed!', $tester->getDisplay());
    }

    /** @test */
    public function it_returns_a_success_status_code()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertEquals(0, $tester->getStatusCode());
    }

    /** @test */
    public function a_vagrantfile_is_created_if_it_does_not_exists()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Vagrantfile')
        );
        $this->assertEquals(
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Vagrantfile'),
            file_get_contents(__DIR__.'/../resources/LocalizedVagrantfile')
        );
    }

    /** @test */
    public function an_existing_vagrantfile_is_not_overwritten()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Vagrantfile',
            'Already existing Vagrantfile'
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertEquals(
            'Already existing Vagrantfile',
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Vagrantfile')
        );
    }

    /** @test */
    public function an_aliases_file_is_created_if_requested()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--aliases' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'aliases.sh')
        );
        // $this->assertEquals(
        //     file_get_contents(__DIR__.'/../resources/aliases.sh'),
        //     file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'aliases.sh')
        // );
    }

    /** @test */
    public function an_existing_aliases_file_is_not_overwritten()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'aliases.sh',
            'Already existing aliases'
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--aliases' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'aliases.sh')
        );
        $this->assertEquals(
            'Already existing aliases',
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'aliases.sh')
        );
    }

    /** @test */
    public function an_after_shell_script_is_created_if_requested()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--after' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'after.sh')
        );
        $this->assertEquals(
            file_get_contents(__DIR__.'/../resources/after.sh'),
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'after.sh')
        );
    }

    /** @test */
    public function an_existing_after_shell_script_is_not_overwritten()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'after.sh',
            'Already existing after.sh'
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--after' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'after.sh')
        );
        $this->assertEquals(
            'Already existing after.sh',
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'after.sh')
        );
    }

    /** @test */
    public function an_example_artestead_yaml_settings_is_created_if_requested()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--example' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml.example')
        );
    }

    /** @test */
    public function an_existing_example_artestead_yaml_settings_is_not_overwritten()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml.example',
            'name: Already existing Artestead.yaml.example'
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--example' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml.example')
        );
        $this->assertEquals(
            'name: Already existing Artestead.yaml.example',
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml.example')
        );
    }

    /** @test */
    public function an_example_artestead_json_settings_is_created_if_requested()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--example' => true,
            '--json' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json.example')
        );
    }

    /** @test */
    public function an_existing_example_artestead_json_settings_is_not_overwritten()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json.example',
            '{"name": "Already existing Artestead.json.example"}'
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--example' => true,
            '--json' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json.example')
        );
        $this->assertEquals(
            '{"name": "Already existing Artestead.json.example"}',
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json.example')
        );
    }

    /** @test */
    public function a_artestead_yaml_settings_is_created_if_it_is_does_not_exists()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml')
        );
    }

    /** @test */
    public function an_existing_artestead_yaml_settings_is_not_overwritten()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml',
            'name: Already existing Artestead.yaml'
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertEquals(
            'name: Already existing Artestead.yaml',
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml')
        );
    }

    /** @test */
    public function a_artestead_json_settings_is_created_if_it_is_requested_and_it_does_not_exists()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--json' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json')
        );
    }

    /** @test */
    public function an_existing_artestead_json_settings_is_not_overwritten()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json',
            '{"message": "Already existing Artestead.json"}'
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertEquals(
            '{"message": "Already existing Artestead.json"}',
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json')
        );
    }

    /** @test */
    public function a_artestead_yaml_settings_is_created_from_a_artestead_yaml_example_if_it_exists()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml.example',
            "message: 'Already existing Artestead.yaml.example'"
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml')
        );
        $this->assertContains(
            "message: 'Already existing Artestead.yaml.example'",
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml')
        );
    }

    /** @test */
    public function a_artestead_yaml_settings_created_from_a_artestead_yaml_example_can_override_the_ip_address()
    {
        copy(
            __DIR__.'/../resources/Artestead.yaml',
            self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml.example'
        );

        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--ip' => '192.168.10.11',
        ]);

        $this->assertTrue(file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml'));
        $settings = Yaml::parse(file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml'));

        $this->assertEquals('192.168.10.11', $settings['ip']);
    }

    /** @test */
    public function a_artestead_json_settings_is_created_from_a_artestead_json_example_if_is_requested_and_if_it_exists()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json.example',
            '{"message": "Already existing Artestead.json.example"}'
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--json' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json')
        );
        $this->assertContains(
            '"message": "Already existing Artestead.json.example"',
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json')
        );
    }

    /** @test */
    public function a_artestead_json_settings_created_from_a_artestead_json_example_can_override_the_ip_address()
    {
        copy(
            __DIR__.'/../resources/Artestead.json',
            self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json.example'
        );

        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--json' => true,
            '--ip' => '192.168.10.11',
        ]);

        $this->assertTrue(file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json'));
        $settings = json_decode(file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json'), true);

        $this->assertEquals('192.168.10.11', $settings['ip']);
    }

    /** @test */
    public function a_artestead_yaml_settings_can_be_created_with_some_command_options_overrides()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--name' => 'test_name',
            '--hostname' => 'test_hostname',
            '--ip' => '127.0.0.1',
        ]);

        $this->assertTrue(file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml'));
        $settings = Yaml::parse(file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml'));
        $this->assertEquals('test-name', $settings['name']);
        $this->assertEquals('test-hostname', $settings['hostname']);
        $this->assertEquals('127.0.0.1', $settings['ip']);
    }

    /** @test */
    public function a_artestead_json_settings_can_be_created_with_some_command_options_overrides()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--json' => true,
            '--name' => 'test_name',
            '--hostname' => 'test_hostname',
            '--ip' => '127.0.0.1',
        ]);

        $this->assertTrue(file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json'));
        $settings = json_decode(file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json'), true);
        $this->assertEquals('test-name', $settings['name']);
        $this->assertEquals('test-hostname', $settings['hostname']);
        $this->assertEquals('127.0.0.1', $settings['ip']);
    }

    /** @test */
    public function a_artestead_yaml_settings_has_preconfigured_sites()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertTrue(file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml'));
        $projectDirectory = basename(getcwd());
        $projectName = $this->slug($projectDirectory);
        $settings = Yaml::parse(file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml'));
        $this->assertEquals([
            'map' => $this->slug("{$projectDirectory}.local", true),
            'to' => "/home/vagrant/Code/{$projectName}/www",
        ], $settings['sites'][0]);
    }

    /** @test */
    public function a_artestead_json_settings_has_preconfigured_sites()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--json' => true,
        ]);

        $this->assertTrue(file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json'));
        $projectDirectory = basename(getcwd());
        $projectName = $this->slug($projectDirectory);
        $settings = json_decode(file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json'), true);
        $this->assertEquals([
            'map' => $this->slug("{$projectDirectory}.local", true),
            'to' => "/home/vagrant/Code/{$projectName}/www",
        ], $settings['sites'][0]);
    }

    /** @test */
    public function a_artestead_yaml_settings_has_preconfigured_shared_folders()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertTrue(file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml'));
        $projectDirectory = basename(getcwd());
        $projectName = $this->slug($projectDirectory);
        $settings = Yaml::parse(file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml'));

        // The "map" is not tested for equality because getcwd() (The method to obtain the project path)
        // returns a directory in a different location that the test directory itself.
        //
        // Example:
        //  - project directory: /private/folders/...
        //  - test directory: /var/folders/...
        //
        // The curious thing is that both directories point to the same location.
        //
        $this->assertRegExp("/{$projectDirectory}/", $settings['folders'][0]['map']);
        $this->assertEquals("/home/vagrant/Code/{$projectName}", $settings['folders'][0]['to']);
    }

    /** @test */
    public function a_artestead_json_settings_has_preconfigured_shared_folders()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--json' => true,
        ]);

        $this->assertTrue(file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json'));
        $projectDirectory = basename(getcwd());
        $projectName = $this->slug($projectDirectory);
        $settings = json_decode(file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json'), true);

        // The "map" is not tested for equality because getcwd() (The method to obtain the project path)
        // returns a directory in a different location that the test directory itself.
        //
        // Example:
        //  - project directory: /private/folders/...
        //  - test directory: /var/folders/...
        //
        // The curious thing is that both directories point to the same location.
        //
        $this->assertRegExp("/{$projectDirectory}/", $settings['folders'][0]['map']);
        $this->assertEquals("/home/vagrant/Code/{$projectName}", $settings['folders'][0]['to']);
    }

    /** @test */
    public function a_warning_is_thrown_if_the_artestead_settings_json_and_yaml_exists_at_the_same_time()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.json',
            '{"message": "Already existing Artestead.json"}'
        );
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Artestead.yaml',
            "message: 'Already existing Artestead.yaml'"
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertContains('WARNING! You have Artestead.yaml AND Artestead.json configuration files', $tester->getDisplay());
    }
}
