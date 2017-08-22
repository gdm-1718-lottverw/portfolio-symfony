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

/**
 * Class CustomerFeedbackController.
 *
 * @author Lotte Verwerft
 */
class CustomerFeedbackController extends FOSRestController
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
    public function optionsCustomerFeedbackAction(){
        $response = new Response();
        $response->headers->set('Allow', 'OPTIONS, GET, POST, PUT');
        return $response;
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
    public function getProjectFeedbackAction($project_hash){

        $em = $this->getDoctrine()->getManager();
        $customerFeedback = $em->getRepository('AppBundle:CustomerFeedback')->getProjectFeedback($project_hash);
        return $customerFeedback;
    }
   
}