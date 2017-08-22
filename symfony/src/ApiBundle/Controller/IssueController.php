<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Issue;
use AppBundle\Entity\Project;
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
use AppBundle\Services\HashCode;
/**
 * Class IssueController.
 *
 * @author Lotte Verwerft
 */
class IssueController extends FOSRestController
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
    public function optionsIssueAction(){
        $response = new Response();
        $response->headers->set('Allow', 'OPTIONS, GET, POST, PUT');
        return $response;
    }

    /**
     * Count all issues for One project
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
    public function getIssuesCountAction($project_hash){

        $em = $this->getDoctrine()->getManager();
        $countIssue = $em->getRepository('AppBundle:Issue')->countIssues($project_hash);
        return $countIssue;
    }

    /**
     * Get all the feedback for one project using it's hash code.
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
    public function getProjectIssuesAction($project_hash){
        $em = $this->getDoctrine()->getManager();
        $issues = $em->getRepository('AppBundle:Issue')->getProjectIssues($project_hash);
        return $issues;
    }


    /**
     * Get an issue using it's hash code.
     *
     * @param ParamFetcher $paramFetcher
     * @return mixed
     *
     * @FOSRest\Get(requirements = {"_format": "json|jsonp"})
     * @param string $hash
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getIssueAction($hash){
        $em = $this->getDoctrine()->getManager();
        $issues = $em->getRepository('AppBundle:Issue')->getIssueByHash($hash);
        return $issues;
    }


    /**

     *
     * @param Request $request
     * @param int $issue_id
     *
     * @return Response
     *
     * @FOSRest\View()
     * @FOSRest\Put(
     *     requirements = {
     *         "issue_id" : "\d+",
     *         "_format" : "json|xml"
     *     }
     * )
     *
     * @Nelmio\ApiDoc(
     *     input = IssueType::class,
     *     statusCodes = {
     *         Response::HTTP_NO_CONTENT: "No Content"
     *     }
     * )
     */
    public function putIssueAction(Request $request, $issue_id)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em
            ->getRepository(Issue::class)->find($issue_id);

        if (!$item instanceof Issue) {
            throw new NotFoundHttpException();
        }
        $item->setUpdatedAt(new DateTime());
        
        return $this->processForm($request, $item);
    }

    /**
     * Create a new issue
     *
     * @param Request $request
     * @return View|Response
     *
     * @FOSRest\View()
     * @FOSRest\Post(
     *     requirements = {
     *         "_format" : "json|xml"
     *     }
     * )
     *
     * @param string $project_hash
     *
     * @Nelmio\ApiDoc(
     *     input = IssueType::class,
     *     statusCodes = { Response::HTTP_CREATED: "Created" }
     *     )
     */
    public function postIssueAction(Request $request, $project_hash)
    {
        $em = $this->getDoctrine()->getManager();

        $project = $em
            ->getRepository('AppBundle:Project')->findProjectByHash($project_hash);
        if(!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        $issue = new Issue();
        $hash = new HashCode();
        $issue->setProject($project);
        $issue->setCreatedAt(new DateTime);
        $issue->setHash($hash->generateHashCode(rand(10, 20)));
    
        return $this->processForm($request, $issue);
    }

    /**
     * Process  form
     *
     * @param Request $request
     * @param Issue $issue
     * @return View|Response
     */
    public function processForm(Request $request, $issue)
    {
        $form = $this->createForm(IssueType::class, $issue, ['method' => $request->getMethod()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $statusCode = is_null($issue->getId()) ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;
            $em = $this->getDoctrine()->getManager();
            $em->persist($issue);
            $em->flush();
            $response = new Response();
            $response->setStatusCode($statusCode);
            $response->setContent(json_encode([
                'issue' => ['id' => $issue->getId(), 'hash' => $issue->getHash()],
                
            ], true));
            return $response;
        }
        return View::create($form, Response::HTTP_BAD_REQUEST);
    }

}