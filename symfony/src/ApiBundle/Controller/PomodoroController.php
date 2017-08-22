<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\Pomodoro\PomodoroType;
use AppBundle\Entity\Pomodoro;
use AppBundle\Entity\User;
use AppBundle\Entity\Task;
use AppBundle\Entity\Issue;
use AppBundle\Entity\ChecklistItem;
use DateTime;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use ApiBundle\Form\Issue\IssueType;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PomodoroController.
 *
 * @author Lotte Verwerft
 */
class PomodoroController extends FOSRestController
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
    public function optionsPomodoroAction(){
        $response = new Response();
        $response->headers->set('Allow', 'OPTIONS, GET, POST, PUT');
        return $response;
    }

    /**
     *  Select all received pomodoro for one user.
     * @param ParamFetcher $paramFetcher
     * @return mixed
     * @FOSRest\Get(requirements = {"_format": "json|jsonp"})
     * @param string $username
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getUserProjectsAction($username){
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('AppBundle:Project')->findProjects($username);
        return $projects;
    }

    /**
     *  Select all received pomodoro for one user.
     * @param ParamFetcher $paramFetcher
     * @return mixed
     //* @FOSRest\View(serializerGroups={"Pomodoros_getReceived"})
     * @FOSRest\Get(requirements = {"_format": "json|jsonp"})
     * @param string $username
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getReceiverAction($username){

        $em = $this->getDoctrine()->getManager();
        // GET USER ID
        $id = $em->getRepository('AppBundle:User')->getUserByUsername($username);
        
        return $id;
    }

    /**
     *  Select all pomodoro for a task .
     * @param ParamFetcher $paramFetcher
     * @return mixed
     * @FOSRest\Get(requirements = {"_format": "json|jsonp"})
     * @FOSRest\View(serializerGroups={"Users_getUser", "Tasks_getTask", "Issues_getIssue", "Items_getItem"})
     * @param string $hash
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getPomodoroCountAction($hash){
        $em = $this->getDoctrine()->getManager();
        $task = $em
            ->getRepository(Task::class)
            ->getTask($hash);
            if(!$task instanceof Task){
                $item = $em->getRepository('AppBundle:ChecklistItem')->getItem($hash);
                if(!$item instanceof ChecklistItem) {
                    $issue = $em->getRepository('AppBundle:Issue')->getIssue($hash);
                     if(!$issue instanceof Issue) {
                        throw new NotFoundHttpException('Bad request');
                     }
                    return $count =  $em->getRepository(Pomodoro::class)->countPomodoroIssue($hash);
                } else {
                   return $count =  $em->getRepository(Pomodoro::class)->countPomodoroItem($hash);
                }
        } else {
          return $count =  $em->getRepository(Pomodoro::class)->countPomodoroTask($hash);
        }
    }


    /**
     * Post a new pomodoro of a task, issue and item.
     *
     *
     * @param Request $request
     * @param string $hash
     * @param string $user_id
     * @return View|Response
     * @FOSRest\View(serializerGroups={"Users_getUser", "Tasks_getTask", "Issues_getIssue", "Items_getItem"})
     * @FOSRest\Post(
     *     "{username}/pomodoro/{hash}"
     * )
     * @Nelmio\ApiDoc(
     *     input = ApiBundle\Form\Pomodoro\PomodoroType::class,
     *     statusCodes = {
     *         Response::HTTP_CREATED : "Created"
     *     }
     * )
     */
    public function postPomodoroAction(Request $request, string $hash, string $username)
    {
        $pomodoro = new Pomodoro();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')
                ->getUserByUsername($username);
    
        if (!$user instanceof User) {
            throw new NotFoundHttpException('Unable to find user');
        }    
    
        $task = $em
            ->getRepository(Task::class)
            ->getTask($hash);
            
            if(!$task instanceof Task){
                $item = $em->getRepository('AppBundle:ChecklistItem')->getItem($hash);
                if(!$item instanceof ChecklistItem) {
                    $issue = $em->getRepository('AppBundle:Issue')->getIssue($hash);
                     if(!$issue instanceof Issue) {
                        throw new NotFoundHttpException('Bad request');
                     }
                    $pomodoro->setIssue($issue);
                } else {
                    $pomodoro->setItem($item);
                }
        } else {
           $pomodoro->setTask($task);
        }
        $user->addPomodoro($pomodoro);
        return $this->processForm($request, $pomodoro);
    }


    /**
     * Update an pomodoro.
     *
     * @param Request $request
     * @param int $pomodoro_id
     *
     * @return Response
     * @FOSRest\View(serializerGroups={"Pomodoros_getPomodoro","Users_getUser", "Tasks_getTask", "Issues_getIssue", "Items_getItem"})
     * @FOSRest\put(
     *     "pomodoro/{pomodoro_id}/completed"
     * )
     *
     * @Nelmio\ApiDoc(
     *     input = PomodoroType::class,
     *     statusCodes = {
     *         Response::HTTP_NO_CONTENT: "No Content"
     *     }
     * )
     */
    public function putPomodoroAction(Request $request, int $pomodoro_id)
    {
        $em = $this->getDoctrine()->getManager();
        $pomodoro = $em->getRepository(Pomodoro::class)->find($pomodoro_id);
        
        if (!$pomodoro instanceof Pomodoro) {
            throw new NotFoundHttpException('Not found');
        }

        return $this->processForm($request, $pomodoro);
    }

    /**
     * Pomodoro owner change.
     *
     * @param Request $request
     * @param int $pomodoro_id
     * @param int $user_id
     *
     * @return Response
     * @FOSRest\View(serializerGroups={"Pomodoros_getPomodoro","Users_getUser", "Tasks_getTask", "Issues_getIssue", "Items_getItem"})
     * @FOSRest\put(
     *     "pomodoro/{pomodoro_id}/receiver/{user_id}"
     * )
     *
     * @Nelmio\ApiDoc(
     *     input = PomodoroType::class,
     *     statusCodes = {
     *         Response::HTTP_NO_CONTENT: "No Content"
     *     }
     * )
     */
    public function putChangePmodoroOwnerAction(Request $request, int $pomodoro_id, int $user_id)
    {
        $em = $this->getDoctrine()->getManager();
        $pomodoro = $em->getRepository(Pomodoro::class)->find($pomodoro_id);
        if (!$pomodoro instanceof Pomodoro) {
            throw new NotFoundHttpException('Pomodoro not found');
        }
        $user = $em->getRepository('AppBundle:User')->getUser($user_id);
        $sender = $pomodoro->getUsers();

        if (!$user instanceof User || $sender instanceof User) {
            throw new NotFoundHttpException('User not found');
        }

        $user->addPomodoro($pomodoro);
        $pomodoro->setSender($sender[0]);
        $pomodoro->setReceiver($user);

        return $this->processForm($request, $pomodoro);
    }
    
    /**
     * Process  form
     *
     * @param Request $request
     * @param Pomodoro $pomodoro
     * @return View|Response
     */
    public function processForm(Request $request, $pomodoro)
    {
        $form = $this->createForm(PomodoroType::class, $pomodoro, ['method' => $request->getMethod()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $statusCode = is_null($pomodoro->getId()) ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;
            $em = $this->getDoctrine()->getManager();
            $em->persist($pomodoro);
            $em->flush();
            $response = new Response();
            $response->setStatusCode($statusCode);
            $response->setContent(json_encode([
                'id' => $pomodoro->getId(),
                'finished' => $pomodoro->getFinished(),
                'inProgress' => $pomodoro->getInProgress(),
                'time' => $pomodoro->getTime()
            ]));
            return $response;
        }
        return View::create($form, Response::HTTP_BAD_REQUEST);
    }

}