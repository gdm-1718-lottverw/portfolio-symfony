<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\User\GroupType;
use AppBundle\Entity\User;
use AppBundle\Entity\Groups;
use DateTime;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use ApiBundle\Form\User\UserType;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class TeamMemberController.
 *
 * @author Lotte Verwerft
 */
class TeamMemberController extends FOSRestController
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
    public function optionsTeamMemberAction(){
        $response = new Response();
        $response->headers->set('Allow', 'OPTIONS, GET, POST, PUT');
        return $response;
    }

    /**
     * Return all team members for one project.
     *
     * @param ParamFetcher $paramFetcher
     * @param string $project_hash 
     * @return mixed
     * @FOSRest\View(serializerGroups={"Users_getUser", "Groups_getGroup"})
     * @FOSRest\Get(requirements = {"_format": "json|jsonp"})
     * @param string $project_hash
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getProjectTeamAction($project_hash){
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('AppBundle:Groups')->getTeamMember($project_hash);
        return $team;
        
    }
    /**
     * Get team with users.
     *
     * @param ParamFetcher $paramFetcher
     * @return mixed
     * @FOSRest\View(serializerGroups={"Groups_getTeams"})
     * @FOSRest\Get(
     *     "admin/team",
     *     requirements = {"_format": "json|jsonp"})
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getTeamAction(){
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('AppBundle:Groups')->findAll();
        return $team;
    }
  
    /**
     * Return all team members for one project.
     *
     * @param ParamFetcher $paramFetcher
     * @param string $project_hash 
     * @return mixed
     * @FOSRest\View(serializerGroups={"Users_getUser", "Groups_getGroup", "Pomodoros_getPomodoro"})
     * @FOSRest\Get(requirements = {"_format": "json|jsonp"})
     * @param string $project_hash
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getProjectTeamPomodoroAction($project_hash){
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('AppBundle:Groups')->getTeamMemberAndPomodoroCount($project_hash);
        $coin;
        if(count($team) > 0){
            $newArray = $team;
            $newArray[0]['users'] = [];
            unset($newArray[0]['roles']);

            foreach($team[0]['users'] as $user){
                $coin = count($user['pomodoros']);
                $user['coin'] = $coin;
                unset($user['pomodoros']);
                unset($user['password']);
                unset($user['emailCanonical']);
                unset($user['usernameCanonical']);
                unset($user['roles']);
                array_push($newArray[0]['users'] , $user);
            }
            return $newArray[0];
        } else {
            return $team;
        }
    
        
        
    }

    /**
     * Add someone to a group
     *
     * @param ParamFetcher $paramFetcher
     * @param int $group_id
     * @param string $username
     * @return mixed
     * @FOSRest\View(serializerGroups={"Users_getUser", "Groups_getGroup"})
     * @FOSRest\Get(
     *     "admin/team/{group_id}/add/{username}",
     *     requirements = {"_format": "json|jsonp"})
     *
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getAddTeamMemberAction(int $group_id, string $username)
    {
        $em = $this->getDoctrine()->getManager();
        $team = $em
            ->getRepository(Groups::class)
            ->getTeam($group_id);

        if (!$team instanceof Groups) {
            throw new NotFoundHttpException("Group not an instance of Group");
        }
        $user = $em
            ->getRepository(User::class)
            ->getUserByUsername($username);
        if (!$user instanceof User) {
            throw new NotFoundHttpException("user not an instance of User");
        }

        $user->addGroup($team);
        $em->persist($user);
        $em->flush();

        return $team;
        
    }

    /**
     * Add someone to a group
     *
     * @param ParamFetcher $paramFetcher
     * @param int $group_id
     * @param string $username
     * @return mixed
     * @FOSRest\View(serializerGroups={"Users_getUser", "Groups_getGroup"})
     * @FOSRest\Get(
     *     "admin/team/{group_id}/remove/{username}",
     *     requirements = {"_format": "json|jsonp"})
     *
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getRemoveTeamMemberAction(int $group_id, string $username)
    {
        $em = $this->getDoctrine()->getManager();
        $team = $em
            ->getRepository(Groups::class)
            ->getTeam($group_id);

        if (!$team instanceof Groups) {
            throw new NotFoundHttpException("Group not an instance of Group");
        }

        $user = $em
            ->getRepository(User::class)
            ->getUserByUsername($username);
        if (!$user instanceof User) {
            throw new NotFoundHttpException("user not an instance of User");
        }

        $user->removeGroup($team);
        $em->persist($user);
        $em->flush();

        return $team;

    }

    /**
     *  Create a new group
     *
     * @param Request $request
     * @return View|Response
     * @FOSRest\Post(
     *     "admin/team/create"
     * )
     * @Nelmio\ApiDoc(
     *     input = ApiBundle\Form\User\GroupType::class,
     *     statusCodes = {
     *         Response::HTTP_CREATED : "Created"
     *     }
     * )
     */
    public function postTeamAction(Request $request)
    {

        $group = new Groups();

        $group->setCreatedAt(new DateTime);
        $group->setRoles(array());
        $group->addRole('ROLE_USER');

        return $this->processForm($request, $group);
    }

    /**
     * Process form
     *
     * @param Request $request
     * @param Groups $group
     * @return View|Response
     */
    public function processForm(Request $request, $group)
    {
        $form = $this->createForm(GroupType::class, $group, ['method' => $request->getMethod()]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $statusCode = is_null($group->getId()) ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;
            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();
            $response = new Response();
            $response->setStatusCode($statusCode);
            $response->setContent(json_encode([
                'group' => ['id' => $group->getId()],
            ]));
            return $response;
        }
        return View::create($form, Response::HTTP_BAD_REQUEST);
    }


}