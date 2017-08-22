<?php

namespace ApiBundle\Controller;


use AppBundle\Services\HashCode;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use DateTime;
/**
 * Class LocationController.
 *
 * @author Lotte Verwerft
 */
class LocationController extends FOSRestController
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
    public function optionsLocationAction(){
        $response = new Response();
        $response->headers->set('Allow', 'OPTIONS, GET, POST, PUT');
        return $response;
    }

    /**
     * Update an location.
     *
     * @param Request $request
     * @param int $checklist_id
     *
     * @return Response
     *
     * @FOSRest\View()
     * @FOSRest\Put(
     *     requirements = {
     *         "checklist_id" : "\d+",
     *         "_format" : "json|xml"
     *     }
     * )
     *
     * @Nelmio\ApiDoc(
     *     input = ChecklistItemType::class,
     *     statusCodes = {
     *         Response::HTTP_NO_CONTENT: "No Content"
     *     }
     * )
     */
    public function putLocationAction(Request $request, $checklist_id)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em
            ->getRepository(ChecklistItem::class)
            ->find($checklist_id);

        if (!$item instanceof ChecklistItem) {
            throw new NotFoundHttpException();
        }
        $item->setUpdatedAt(new DateTime());
        return $this->processForm($request, $item);
    }

}