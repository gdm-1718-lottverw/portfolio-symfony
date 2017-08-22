<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\Checklist\ChecklistType;
use ApiBundle\Form\Checklist\ChecklistItemType;
use ApiBundle\Form\Checklist\ItemType;
use AppBundle\Entity\ChecklistItem;
use AppBundle\Entity\Project;
use AppBundle\Entity\Checklist;
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
 * Class ChecklistController.
 *
 * @author Lotte Verwerft
 */
class ChecklistController extends FOSRestController
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
    public function optionsChecklistAction(){
        $response = new Response();
        $response->headers->set('Allow', 'OPTIONS, GET, POST, PUT');
        return $response;
    }

    /**
     * Count all checklist items for One project
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
    public function getChecklistCountAction($project_hash){

        $em = $this->getDoctrine()->getManager();
        $countChecklist = $em->getRepository('AppBundle:Checklist')->countChecklistUnfinishedItems($project_hash);
        return $countChecklist;
    }

    /**
     * Get all items of a checklists for One project
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
    public function getChecklistItemsAction($project_hash){
        $em = $this->getDoctrine()->getManager();
        $checklist = $em->getRepository('AppBundle:ChecklistItem')->getChecklistItems($project_hash);
        return $checklist;
    }

    /**
     * Get item by hash
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
    public function getItemsAction($hash){
        $em = $this->getDoctrine()->getManager();
        $checklist = $em->getRepository('AppBundle:ChecklistItem')->getItemByHash($hash);
        return $checklist;

    }
    /**
     *  Create a checklist with some items
     *
     *
     * @param Request $request
     * @param string $hash
     * @return View|Response
     * @FOSRest\Post(
     *     "project/{hash}/checklist/create"
     * )
     * @Nelmio\ApiDoc(
     *     input = ApiBundle\Form\Checklist\ChecklistType::class,
     *     statusCodes = {
     *         Response::HTTP_CREATED : "Created"
     *     }
     * )
     */
    public function postChecklistAction(Request $request, string $hash)
    {
        $em = $this->getDoctrine()->getManager();

        $project = $em
            ->getRepository(Project::class)
            ->findProjectByHash($hash);
        if (!$project instanceof Project) {
            throw new NotFoundHttpException("project is not an instance of Project");
        }

        $item = new ChecklistItem();
        $hashCode = new HashCode();
        $item->setHash($hashCode->generateHashCode(rand(10, 20)));
        if($project->getChecklist() === null){
            $list = new Checklist();
            $list->setCreatedAt(new DateTime());
            $em->persist($list);
            $project->setChecklist($list);
            $em->persist($project);
            $item->setChecklist($list);
            $item->setCreatedAt(new DateTime());
           return $this->processForm($request, $item);
        } else {
            $item->setChecklist($project->getChecklist());
            return $this->processForm($request, $item);
        }
    }
    /**
     * Update an articles position.
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
    public function putChecklistItemAction(Request $request, $checklist_id)
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

    /**
     * Process  form
     *
     * @param Request $request
     * @param ChecklistItem $item
     * @return View|Response
     */
    public function processForm(Request $request, $item)
    {
        $form = $this->createForm(ItemType::class, $item, ['method' => $request->getMethod()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $statusCode = is_null($item->getId()) ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();
            $response = new Response();
            $response->setStatusCode($statusCode);
            $response->setContent(json_encode([
                'item' => ['id' => $item->getId(), 'hash' => $item->getHash()],
            ], true));
            return $response;
        }
        return View::create($form, Response::HTTP_BAD_REQUEST);
    }
}