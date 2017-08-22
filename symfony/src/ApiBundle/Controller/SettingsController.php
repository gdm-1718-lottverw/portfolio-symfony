<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\Settings\SettingsType;
use AppBundle\Entity\TimerSettings;
use AppBundle\Entity\User;
use DateTime;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\Time;

/**
 * Class SettingsController.
 *
 * @author Lotte Verwerft
 */
class SettingsController extends FOSRestController
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
    public function optionsSettingsAction(){
        $response = new Response();
        $response->headers->set('Allow', 'OPTIONS, GET, POST, PUT');
        return $response;
    }

    /**
     * Get timer settings
     * @param ParamFetcher $paramFetcher
     * @return mixed
     * @FOSRest\Get("settings/{username}", requirements = {"_format": "json|jsonp"})
     * @param string $username
     *
    // * @FOSRest\View(serializerGroups={"getSettings"})
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getUserSettingsAction($username){
        $em = $this->getDoctrine()->getManager();
        $settings = $em->getRepository('AppBundle:TimerSettings')->getSettings($username);
        return $settings;
    }
    /**
     * Post a setting.
     *
     *
     * @param Request $request
     * @param string $username
     * @return View|Response
     * @FOSRest\View(serializerGroups={"Users_getUser"})
     * @FOSRest\Post(
     *     "settings/{username}"
     * )
     * @Nelmio\ApiDoc(
     *
     *     input = ApiBundle\Form\Settings\SettingsType::class,
     *     statusCodes = {
     *         Response::HTTP_CREATED : "Created"
     *     }
     * )
     */
    public function postSettingAction(Request $request, string $username)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em
            ->getRepository(User::class)
            ->getUserByUsername($username);

        if (!$user instanceof User) {
            throw new NotFoundHttpException("user is not an instance of User");
        }

        $settings = new TimerSettings();
        $settings->setUser($user);
        return $this->processForm($request, $settings);
    }

    /**
     *  Update a setting.
     *
     * @param Request $request
     * @param string $username
     * @return View|Response
     * @FOSRest\Put(
     *     "settings/{username}"
     * )
     * @Nelmio\ApiDoc(
     *     input = ApiBundle\Form\Settings\SettingsType::class,
     *     statusCodes = {
     *         Response::HTTP_CREATED : "Created"
     *     }
     * )
     */
    public function putProjectAction(Request $request, string $username)
    {
        $em = $this->getDoctrine()->getManager();

        $settings = $em
            ->getRepository(TimerSettings::class)
            ->checkIfExists($username);

        if ($settings instanceof TimerSettings) {
            return $this->processForm($request, $settings);
        }


    }


    /**
     * Process form
     *
     * @param Request $request
     * @param TimerSettings $settings
     * @return View|Response
     */
    public function processForm(Request $request, $settings)
    {
        $form = $this->createForm(SettingsType::class, $settings, ['method' => $request->getMethod()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $statusCode = is_null($settings->getId()) ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;
            $em = $this->getDoctrine()->getManager();
            $em->persist($settings);
            $em->flush();
            $response = new Response();
            $response->setStatusCode($statusCode);
            $response->setContent(json_encode([
                'settings' => ['id' => $settings->getId()],
            ]));
            return $response;
        }
        return View::create($form, Response::HTTP_BAD_REQUEST);
    }


}