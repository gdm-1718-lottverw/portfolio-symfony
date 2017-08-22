<?php
namespace ApiBundle\Controller;

use ApiBundle\Form\Attachment\AttachmentType;
use AppBundle\Entity\Attachment;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use DateTime;

/**
 * Class ImageController.
 *
 * @author Lotte Verwerft
 */
class AttachmentController extends FOSRestController
{
    /**
     * Test API options and requirements.
     *
     * @return Response
     *
     * @Nelmio\ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         Response::HTTP_OK: "OK"
     *     }
     * )
     */
    public function optionsImagesAction(): Response
    {
        $response = new Response();
        $response->headers->set('Allow', 'OPTIONS, GET, POST, PUT');
        return $response;
    }
    /**
     * Post a new image of a user.
     *
     *
     * @param Request $request
     * @param int $user_id
     *
     * @return View|Response
     * @FOSRest\View(serializerGroups={"Attachments_getAttachment"})
     * @FOSRest\Post(
     *     "/users/{user_id}/attachments",
     *     requirements = {
     *         "user_id" : "\d+"
     *     }
     * )
     * @Nelmio\ApiDoc(
     *     input = ApiBundle\Form\Attachment\AttachmentType::class,
     *     statusCodes = {
     *         Response::HTTP_CREATED : "Created"
     *     }
     * )
     */
    public function postAttachmentAction(Request $request, int $user_id)
    {
        
        $em = $this->getDoctrine()->getManager();
        $user = $em
            ->getRepository(User::class)
            ->find($user_id);

        if (!$user instanceof User) {
            throw new NotFoundHttpException();
        }

        $attachments =  $user->getAttachments();

        foreach ($attachments as $a){
            $a->setDeletedAt(new DateTime());
        }

        $attachment = new Attachment();
        $data = $_FILES['file'];
        $file = new UploadedFile($data['tmp_name'], $data['name'], $data['type'], $data['size'], $data['error']);
        $uploadDirectory = 'uploads';
        $fileName = sha1_file($file->getRealPath()).'.'.$file->guessExtension();
        $fileLocator = realpath($this->getParameter('kernel.root_dir').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web').DIRECTORY_SEPARATOR.$uploadDirectory;
        $file->move($fileLocator, $fileName);

        $attachment->setSrc($request->getScheme().'://'.$request->getHttpHost().'/'.$uploadDirectory.'/'.$fileName);
        $user->addAttachment($attachment);
        $attachment->setCreatedAt(new DateTime);
        $attachment->setName($fileName);
        $attachment->setAlt($fileName);
        $attachment->setFile($file);
        $em = $this->getDoctrine()->getManager();
        $em->persist($attachment); // Manage entity Image for persistence.
        $em->flush(); // Persist to database.
        return $attachment;
    }

}