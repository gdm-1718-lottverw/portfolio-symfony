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
 * Class ProjectController.
 *
 * @author Lotte Verwerft
 */
class ProjectController extends FOSRestController
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
    public function optionsProjectAction(){
        $response = new Response();
        $response->headers->set('Allow', 'OPTIONS, GET, POST, PUT');
        return $response;
    }

    /**
     * Return all Projects
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
     * Return one project for hashcode.
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
    public function getProjectAction($project_hash){
      
        $em = $this->getDoctrine()->getManager();
     
        $project = $em->getRepository('AppBundle:Project')->findOngoingProjectByHash($project_hash);
          
        return $project;
    }


    /**
     *  Create a new project
     *
     *
     * @param Request $request
     * @param string $username
     * @return View|Response
     * @FOSRest\Post(
     *     "project/{username}"
     * )
     * @Nelmio\ApiDoc(
     *     input = ApiBundle\Form\Project\ProjectType::class,
     *     statusCodes = {
     *         Response::HTTP_CREATED : "Created"
     *     }
     * )
     */
    public function postProjectAction(Request $request, string $username)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em
            ->getRepository(User::class)
            ->getUserByUsername($username);

        if (!$user instanceof User) {
            throw new NotFoundHttpException("user is not an instance of User");
        }
       
        $project = new Project();
        $hash = new HashCode();
        $project->setHash($hash->generateHashCode(15));
        $user->addProject($project);
        $project->setCreatedAt(new DateTime);

        return $this->processForm($request, $project);
    }

    /**
     *  Update a project.
     *
     * @param Request $request
     * @param string $hash
     * @return View|Response
     * @FOSRest\Put(
     *     "project/{hash}"
     * )
     * @Nelmio\ApiDoc(
     *     input = ApiBundle\Form\Project\ProjectType::class,
     *     statusCodes = {
     *         Response::HTTP_CREATED : "Created"
     *     }
     * )
     */
    public function putProjectAction(Request $request, string $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('AppBundle:Project')->findProjectByHash($hash);
        
        if (!$project instanceof Project) {
            throw new NotFoundHttpException("Project not found");
        }
        $project->setUpdatedAt(new DateTime());
        return $this->processForm($request, $project);
    }


    /**
     * Process form
     *
     * @param Request $request
     * @param Project $project
     * @return View|Response
     */
    public function processForm(Request $request, $project)
    {
        $form = $this->createForm(ProjectType::class, $project, ['method' => $request->getMethod()]);
        $form->handleRequest($request);
       
        if ($form->isValid()) {
            $statusCode = is_null($project->getId()) ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();
            $response = new Response();
            $response->setStatusCode($statusCode);
            // Redirect to the URI of the resource.
            $response->headers->set('Location',
                $this->generateUrl('api_v1_get_project', [
                    'project_hash' => $project->getHash(),
                ], true)
            );
            $response->setContent(json_encode([
                'id' => $project->getId(),
                'hash' => $project->getHash()
            ]));
            return $response;
        }
        return View::create($form, Response::HTTP_BAD_REQUEST);
    }
}