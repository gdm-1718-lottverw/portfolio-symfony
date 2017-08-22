<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\User\ProfileType;
use AppBundle\Entity\User;
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
 * Class UserController.
 *
 * @author Lotte Verwerft
 */
class UserController extends FOSRestController
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
    public function optionsUserAction(){
        $response = new Response();
        $response->headers->set('Allow', 'OPTIONS, GET, POST, PUT');
        return $response;
    }

    /**
     * Get an profile by username.
     *
     * @param ParamFetcher $paramFetcher
     * @return mixed
     *
     * @FOSRest\Get(requirements = {"_format": "json|jsonp"})
     * @FOSRest\View(serializerGroups={"Profiles_getProfile", "Attachments_getAttachment"})
     * @param string $username
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getProfileAction($username){
        $em = $this->getDoctrine()->getManager();
        $profile = $em->getRepository('AppBundle:User')->getProfileByUsername($username);
        return $profile;
    }

    /**
     * Get an profile by username.
     *
     * @param ParamFetcher $paramFetcher
     * @return mixed
     * @FOSRest\View(serializerGroups={"getUser_Username"})
     * @FOSRest\Get(
     *      "admin/users/short",
     *      requirements = {"_format": "json|jsonp"})
     *
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCode = {
     *          Response::HTTP_OK: "OK"
     *      }
     * )
     */
    public function getUsernameAction(){
        $em = $this->getDoctrine()->getManager();
        $usernames = $em->getRepository('AppBundle:User')->getSimpleUser();
        return $usernames;
    }


    /**
     * Update an articles position.
     *
     * @param Request $request
     * @param string $username
     *
     * @return Response
     *
     * @FOSRest\View()
     * @FOSRest\Put(
     *     "profiles/{username}",
     *     requirements = {
     *         "checklist_id" : "\d+",
     *         "_format" : "json|xml"
     *     }
     * )
     *
     * @Nelmio\ApiDoc(
     *     input = ProfileType::class,
     *     statusCodes = {
     *         Response::HTTP_NO_CONTENT: "No Content"
     *     }
     * )
     */
    public function putProfileAction(Request $request, string $username)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em
            ->getRepository(User::class)
            ->getUserByUsername($username);

        if (!$user instanceof User) {
            throw new NotFoundHttpException();
        }
        return $this->processProfileForm($request, $user);
    }

    /**
     * Create a new user
     *
     * @param Request $request
     *
     * @return View|Response
     *
     * @FOSRest\View()
     * @FOSRest\Post("/register")
     *
     * @Nelmio\ApiDoc(
     *     input = UserType::class,
     *     statusCodes = { Response::HTTP_CREATED: "Created" }
     *     )
     */
    public function postUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = new User();
        $user
            ->setEnabled(false)
            ->setRoles(array('ROLE_USER'))
            ->setPasswordRequestedAt(new DateTime())
            ->setSalt(md5(uniqid()))
        ;
        return $this->processForm($request, $user);
    }


    /**
     * Process  form
     *
     * @param Request $request
     * @param User $user
     * @return View|Response
     */
    public function processForm(Request $request, $user)
    {
        $form = $this->createForm(UserType::class, $user, ['method' => $request->getMethod()]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $statusCode = is_null($user->getId()) ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $response = new Response();
            $response->setStatusCode($statusCode);
            $response->setContent(json_encode([
                'user' => ['id' => $user->getId()]
            ], true));
            return $response;
        }
        return View::create($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Process  form
     *
     * @param Request $request
     * @param User $user
     * @return View|Response
     */
    public function processProfileForm(Request $request, $user)
    {
        $form = $this->createForm(ProfileType::class, $user, ['method' => $request->getMethod()]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $statusCode = is_null($user->getId()) ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $response = new Response();
            $response->setStatusCode($statusCode);
            $response->setContent(json_encode([
                'user' => ['id' => $user->getId()]
            ], true));
            return $response;
        }
        return View::create($form, Response::HTTP_BAD_REQUEST);
    }

}