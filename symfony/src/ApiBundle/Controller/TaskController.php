<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ApiBundle\Form\Task\TaskType;
use ApiBundle\Form\Task\TaskUpdate;
use AppBundle\Services\HashCode;
use AppBundle\Entity\User;
use AppBundle\Entity\Project;
use AppBundle\Entity\Task;
use DateTime;
/**
 * Class TaskController.
 *
 * @author Lotte Verwerft
 */
class TaskController extends FOSRestController
{
    /**
     * Test API options and Requirements
     *
     * @return Response
     *
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *          Response::HTTP_OK = "OK"
     *     }
     * )
     */
    public function optionsTaskAction(){
        $response = new Response();
        $response->headers->set('Allow', 'OPTIONS, GET, POST, PUT');
        return $response;
    }

    /**
     * Return all tasks for one project.
     *
     * @param ParamFetcher $paramFetcher
     * @return mixed
     *
     * @FOSRest\Get(requirements = {"_format": "json|jsonp"})
     * @param string $project_hash
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getProjectTasksAction(string $project_hash){

        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AppBundle:Task')->getProjectTasks($project_hash);

        return $task;
    }

    /**
     * Return all tasks for one project.
     *
     * @param ParamFetcher $paramFetcher
     * @return mixed
     *
     * @FOSRest\Get(requirements = {"_format": "json|jsonp"})
     * @param string $username
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getUserTasksAction($username){
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AppBundle:Task')->getUserTasks($username);
        return $task;
    }

    /**
     * Return one task for hashcode.
     *
     * @param ParamFetcher $paramFetcher
     * @return mixed
     * @FOSRest\View(serializerGroups={"Tasks_getTask"})
     * @FOSRest\Get(requirements = {"_format": "json|jsonp"})
     * @param string $task_hash
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getTaskAction($task_hash){
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AppBundle:Task')->getTask($task_hash);
        return $task;
    }
    /**
     *  Create a new task
     *
     *
     * @param Request $request
     * @param string $username
     * @param string $hash
     * @return View|Response
     * @FOSRest\Post(
     *     "task/{username}/project/{hash}"
     * )
     * @Nelmio\ApiDoc(
     *     input = ApiBundle\Form\Task\TaskType::class,
     *     statusCodes = {
     *         Response::HTTP_CREATED : "Created"
     *     }
     * )
     */
    public function postTaskAction(Request $request, string $username, string $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em
            ->getRepository(User::class)
            ->getUserByUsername($username);

        if (!$user instanceof User) {
            throw new NotFoundHttpException("user is not an instance of User");
        }
        $project = $em
            ->getRepository(Project::class)
            ->findProjectByHash($hash);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException("project is not an instance of Project");
        }
        $task = new Task();
        $hash = new HashCode();
        $task->setHash($hash->generateHashCode(10));
        $task->setUser($user);
        $task->setProject($project);
        $task->setCreatedAt(new DateTime);
        
        return $this->processForm($request, $task);
    }
    /**
     * Update a task
     *
     * @param Request $request
     * @param string $hash
     *
     * @return Response
      * @FOSRest\put(
     *     "task/{hash}"
     * )
     * @Nelmio\ApiDoc(
     *     input = TaskType::class,
     *     statusCodes = {
     *         Response::HTTP_NO_CONTENT: "No Content"
     *     }
     * )
     */
    public function putTaskAction(Request $request, string $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository(Task::class)->getTask($hash);
       
        if (!$task instanceof Task) {
            throw new NotFoundHttpException('Task not found');
        }
        $task->setUpdatedAt(new DateTime());
        return $this->processForm($request, $task);
    }

    /**
     * Process form
     *
     * @param Request $request
     * @param Task $task
     * @return View|Response
     */
    public function processForm(Request $request, $task)
    {
        $form = $this->createForm(TaskType::class, $task, ['method' => $request->getMethod()]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $statusCode = is_null($task->getId()) ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            $response = new Response();
            $response->setStatusCode($statusCode);
            $response->setContent(json_encode([
                'task' => ['id' => $task->getId(), 'hash' => $task->getHash()],
            ]));
            return $response;
        }
        return View::create($form, Response::HTTP_BAD_REQUEST);
    }


}