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
use AppBundle\Entity\User;
use AppBundle\Entity\Project;
use ApiBundle\Form\Project\ProjectType;
use AppBundle\Services\HashCode;
use DateTime;
/**
 * Class AdminController.
 *
 * @author Lotte Verwerft
 */
class AdminController extends FOSRestController
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
    public function optionsAdminAction(){
        $response = new Response();
        $response->headers->set('Allow', 'OPTIONS, GET, POST, PUT');
        return $response;
    }

    /**
     * Return one project for hashcode.
     *
     * @param ParamFetcher $paramFetcher
     * @param string $project_hash
     * @return mixed
     * @FOSRest\View(serializerGroups={"Admin_getProjects"})
     * @FOSRest\Get(
     *      "admin/projects/{project_hash}",
     *     requirements = {"_format": "json|jsonp"})

     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getProjectByHashAction($project_hash){

        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('AppBundle:Project')->findProjectByHash($project_hash);

        return $project;
    }

    /**
     * Return project stats.
     *
     * @param ParamFetcher $paramFetcher
     * @return mixed
     *
     * @FOSRest\Get(requirements = {"_format": "json|jsonp"})
     * @FOSRest\Get(
     *     "admin/project/stats"
     * )
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getProjectStatsAction(){

        $em = $this->getDoctrine()->getManager();
        $today = new DateTime;

        // Retrieve all different kinds of progress.
        $red = $em->getRepository('AppBundle:Project')->findProjectRed($today);
        $green = $em->getRepository('AppBundle:Project')->findProjectGreen($today);
        $orange = $em->getRepository('AppBundle:Project')->findProjectOrange($today);

        // New array to return all at once.
        $stats = [
            "red" => $red,
            "green" => $green,
            "orange" => $orange
        ];
        // return the stats.
        return $stats;
    }

    /**
     * Return projects.
     *
     * @param ParamFetcher $paramFetcher
     * @return mixed
     * @FOSRest\View(serializerGroups={"Admin_getProjects"})
     * @FOSRest\Get(requirements = {"_format": "json|jsonp"})
     * @FOSRest\Get(
     *     "admin/projects"
     * )
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getAdminProjectsAction(){

        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:Project')->getAdminProjects();

        return $users;
    }

    /**
     * Return user location.
     *
     * @param ParamFetcher $paramFetcher
     * @return mixed
     * @FOSRest\Get(requirements = {"_format": "json|jsonp"})
     * @FOSRest\Get(
     *     "admin/users/location"
     * )
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getUsersLoacationAction(){

        $em = $this->getDoctrine()->getManager();
        $location = $em->getRepository('AppBundle:Location')->getUserLocation();
        return $location;

    }


    /**
     * Return project stats.
     *
     * @param ParamFetcher $paramFetcher
     * @return mixed
     * @FOSRest\Get(requirements = {"_format": "json|jsonp"})
     * @FOSRest\Get(
     *     "admin/users"
     * )
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getAdminUsersAction(){

        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->getUserProfile();

        return $users;
    }

    /**
     * Soft delete user
     *
     * @param ParamFetcher $paramFetcher
     * @return mixed
     * @param int $id
     * @FOSRest\View(serializerGroups={"Users_getUser"})
     * @FOSRest\Get(requirements = {"_format": "json|jsonp"})
     * @FOSRest\Get(
     *     "admin/user/{id}/delete"
     * )
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getDeleteUserAction(int $id){

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->getUser($id);

        if (!$user instanceof User) {
            throw new NotFoundHttpException();
        }

        $user->setEnabled(false);
        $user->setDeletedAt(new DateTime());
        $em->persist($user);
        $em->flush();

        $users = $em->getRepository('AppBundle:User')->getUserProfile();

        return $users;
    }
}