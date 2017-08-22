<?php

namespace ApiBundle\Features\Context;

use AppBundle\AppBundle;
use AppBundle\Entity\Issue;
use AppBundle\Entity\Project;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\Exception;
use Behat\MinkExtension\Context\RawMinkContext;
use GuzzleHttp\Client;
use DateTime;
use AppKernel;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';
require_once __DIR__.'/../../../../app/autoload.php';
require_once __DIR__.'/../../../../app/AppKernel.php';



class FeatureContext implements SnippetAcceptingContext
{
    use \Behat\Symfony2Extension\Context\KernelDictionary;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    private static $container;

    protected $base_url = 'http://nmdad3/api';
    private $data = [];
    private $response;
    private $token;
    /**
     * @BeforeSuite
     */
    public static function bootstrapSymfony()
    {
        $kernel = new AppKernel('test', true);
        $kernel->boot();
        self::$container = $kernel->getContainer();
    }

    /**
     * LOGIN FEATURE
     */

    /**
     * @Given there are Users with the following details:
     */
    public function thereAreUsersWithTheFollowingDetails(TableNode $users)
    {
        foreach ($users->getColumnsHash() as $key => $val) {
            $em = self::$container->get('doctrine')->getManager();
            $check = $em->getRepository('AppBundle:User')->getUserByUsername($val['username']);
            if ($check == null) {
                // If not create a new user.
                $user = new User;
                $user->setUsername($val['username']);
                $user->setPlainPassword($val['password']);
                $user->setRoles(array('ROLE_ADMIN'));
                $user->setEmail($val['username'] . "@gmail.com");
                $user->setEmailCanonical($val['username'] . "@gmail.com");
                $user->setFirstName("jhon_" . $val['username']);
                $user->setLastName("Doe_" . $val['username']);
                $em->persist($user);
                $em->flush();
            }
        }
    }

    /**
     * @Given I have a user with following username :username and password :password
     */
    public function iHaveAUserWithFollowingUsernameAndPassword($username, $password)
    {
        $em = self::$container->get('doctrine')->getManager();
        $user = $em->getRepository('AppBundle:User')->getUserByUsername($username);

        if(empty($user)){
            echo "user not found";
        }

        $this->data = array(
            '_username' => $username,
            '_password' => $password
        );
    }

    /**
     * @When I check the user :method :url
     */
    public function iCheckTheUser($method, $url)
    {
        $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);

        $response = $client->post('http://nmdad3/api'. $url,
            ['body' => json_encode(
                [
                    '_username' => $this->data['_username'],
                    '_password' => $this->data['_password'],
                ]
            )]
        );

        $this->response = $response;
    }

    /**
     * @Then the response code should be :code
     */
    public function theResponseCodeShouldBe($code)
    {
        if($this->response->getStatusCode() != $code){
            if($this->response->getStatusCode() == 204){
                echo 'No content found';
            } else {
                throw new Exception();
            }
        }
    }


    /**
     * @Given I have the following payload
     */
    public function iHaveTheFollowingPayload(PyStringNode $object)
    {
        $data = json_decode($object, true);
        $this->data = $data;

    }

    /**
     * @When I request :method :url
     */
    public function iRequest($method, $url)
    {
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ],

        ]);

        if($method == "POST") {
            $response = $client->post('http://nmdad3/api' . $url,
                ['body' => json_encode(
                    [
                        'username' => $this->data['username'],
                        'plainPassword' => $this->data['password'],
                        'email' => $this->data['email'],
                        'emailCanonical' => $this->data['emailCanonical'],
                        'password' => $this->data['password'],
                        'firstName' => $this->data['firstName'],
                        'lastName' => $this->data['lastName']
                    ]
                )]
            );
           $this->response = $response;
        } elseif ($method == "GET"){
            $response = $client->get('http://nmdad3/api' . $url);
            $this->response = $response;
        }
    }


    /**
     * @Given I login the user :username :password
     */
    public function iLogIn($username, $password)
    {
        $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);

        $response = $client->post('http://nmdad3/api/login_check',
            ['body' => json_encode(
                [
                    '_username' => $username,
                    '_password' => $password,
                ]
            )]
        );
        $token = json_decode($response->getBody()->getContents(), true);
        $this->token = $token['token'];
    }

    /**
     * PROJECTS FEATURE
     */

    /**
     * @Given there are projects with the following details:
     */
    public function thereAreProjectsWithTheFollowingDetails(TableNode $projects)
    {
        foreach ($projects->getColumnsHash() as $key => $val) {
            $em = self::$container->get('doctrine')->getManager();
            $check = $em->getRepository('AppBundle:Project')->findProjectByHash($val['hash']);
            if ($check == null) {
                // If not create a new project.
                $project = new Project();
                $project->setTitle($val['title'] . rand(3, 100))
                    ->setDescription($val['description'])
                    ->setHash($val['hash'])
                    ->setInProgress(false)
                    ->setFinished(false)
                    ->setDeadline(new DateTime())
                    ->setCreatedAt(new DateTime())
                    ->setCustomer($val['customer']);
                $em->persist($project);
                $em->flush();
            }
        }
    }

    /**
     * @Given I have a project with following hash :hash
     */
    public function iHaveAProjectWithFollowingHash($hash)
    {
        $em = self::$container->get('doctrine')->getManager();
        $check = $em->getRepository('AppBundle:Project')->findProjectByHash($hash);
        if($check == null) {
            throw new Exception('not found');
        }
    }

    /**
     * @When I request :method :url with one parameter :param to get or add a :object
     */
    public function iRequestWithOneParameter($method, $url, $param, $object)
    {
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
                ]
        ]);

        if($method == "GET"){
            $response = $client->get('http://nmdad3/api' . $url . $param);
            $this->response = $response;
        }
        if($method == "POST"){
            $response = $client->post('http://nmdad3/api' . $url . $param,
                ['body' => json_encode(
                    [
                        $object =>   $this->data[$object],

                    ]
                )]
            );
            $this->response = $response;
        }

    }



    /**
     * @Then the response should contain a :param
     */
    public function theResponseShouldContainA($param)
    {
        $data = json_decode($this->response->getBody(), true);

        if($data[$param] == null){
            throw new Exception('not found');
        };
    }

    /**
     * ISSUES
     */

    /**
     * @Given there is a issue with the following details:
     */
    public function thereIsAIssueWithTheFollowingDetails(TableNode $issues)
    {
        foreach ($issues->getColumnsHash() as $key => $val) {
        $em = self::$container->get('doctrine')->getManager();
        $project = $em->getRepository('AppBundle:Project')->findProjectByHash("kdkjkzejf");
        $check = $em->getRepository('AppBundle:Issue')->getIssueByHash($val['hash']);
        if ($check == null) {
            // If not create a new project.
            $issue = new Issue();
            $issue->setTitle($val['title'])
                ->setDescription($val['description'])
                ->setHash($val['hash'])
                ->setInProgress(false)
                ->setSolved(false)
                ->setEstimatePomodoros(rand(3,10))
                ->setUrgent(true)
                ->setProject($project)
                ->setCreatedAt(new DateTime());
            $em->persist($issue);
            $em->flush();
        }
    }
    }

    /**
     * @When I request :method :url_par1 with one nested parameter :param :url_par2 to get or add a :object
     */
    public function iRequestWithOneNestedParameter($method, $url_par1, $param, $url_par2, $object)
    {
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ]
        ]);

        if($method == "GET"){
            $response = $client->get('http://nmdad3/api' . $url_par1 . $param . $url_par2);
            $this->response = $response;
        }
        if($method == "POST"){
            $response = $client->post('http://nmdad3/api' . $url_par1 . $param . $url_par2,
                ['body' => json_encode(
                    [
                        $object =>   $this->data[$object],

                    ]
                )]
            );
            $this->response = $response;
        }
    }



    /**
     * @Then the response should be an array
     */
    public function theResponseShouldBeAnArray()
    {
        $data = json_decode($this->response->getBody(), true);

        if(!is_array($data)){
            if(!empty($data)){
                throw new Exception('not an array');
            }
            echo "empty response";
        };
    }

    /**
     * @Then the response should be an object with name :name
     */
    public function theResponseShouldBeAnObject($name)
    {
        $data = json_decode($this->response->getBody());
        if(!property_exists($data, $name)){
           throw new Exception("Not an object");
        }
    }

    /**
     * TASKS
     */

    /**
     * @Given there are task with the following details:
     */
    public function thereAreTaskWithTheFollowingDetails(TableNode $tasks)
    {
        foreach ($tasks->getColumnsHash() as $key => $val) {
            $em = self::$container->get('doctrine')->getManager();
            $project = $em->getRepository('AppBundle:Project')->findProjectByHash("kdkjkzejf");
            // Prevent doubles
            $check = $em->getRepository('AppBundle:Task')->getTask($val['hash']);
            if ($check == null) {
                // If not create a new project.
                $task = new Task();
                $task->setName($val['name'])
                    ->setDescription($val['description'])
                    ->setHash($val['hash'])
                    ->setInProgress(false)
                    ->setFinished(false)
                    ->setEstimatePomodoros(rand(3,10))
                    ->setProject($project)
                    ->setCreatedAt(new DateTime());
                $em->persist($task);
                $em->flush();
            }
        }
    }



}
