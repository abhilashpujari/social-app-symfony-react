<?php
namespace App\Controller;

use App\Exception\ValidationException;
use App\Service\Auth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage;

/**
 * Class BaseController
 * @package App\Controller
 */
class BaseController extends AbstractController
{
    /** @var  ValidatorInterface $validator */
    protected $validator;

    /** @var SerializerInterface $serializer */
    protected $serializer;

    /**
     * @var Auth $auth
     */
    protected $auth;

    /**
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param Auth $auth
     */
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        Auth $auth
    )
    {
        $this->serializer = $serializer;
        $this->validator =  $validator;
        $this->auth = $auth;
        $this->init();
    }

    protected function init()
    {
    }

    /**
     * @param $entity
     * @param null $groups
     * @param null $constraints
     * @return bool
     * @throws ValidationException
     */
    protected function validate($entity, $groups = null, $constraints = null)
    {
        $errors = [];
        foreach ($this->validator->validate($entity, $constraints, $groups) as $error) {
            $errors[$error->getPropertyPath()] = $error->getMessage();
        };

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        return true;
    }

    /**
     * @param $data
     * @param $class
     * @param array $context
     * @param string $format
     * @return object
     */
    protected function deserialize($data, $class, $context = [], $format = 'json')
    {
        $data = is_object($data) || is_array($data)
            ? json_encode($data)
            : $data;

        return $this->serializer->deserialize($data, $class, $format, $context);
    }

    /**
     * @param null $content
     * @param int $statusCode
     * @param array $headers
     * @param string $format
     * @param array $context
     * @return JsonResponse|Response
     */
    protected function setResponse($content = null, $statusCode = 200, $headers = [], $context = [], $format = 'json')
    {
        /** @var Request $request */
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $accept = $request->getAcceptableContentTypes();

        if (in_array('application/json', $accept) || $format == 'json') {
            $response = $this->json($content, $statusCode, $headers, $context);
        } else {
            $response = new Response($content, $statusCode, $headers);
        }

        /** @var Auth $identity */
        $identity = $this->getIdentity();
        if ($identity->isAuthenticated()) {
            $response->headers->set('X-AUTH-TOKEN', $identity->getToken());
        }

        return $response;
    }

    /**
     * @return Auth
     */
    protected function getIdentity()
    {
        return $this->auth;
    }

    /**
     * @return resource|\stdClass|string
     */
    protected function getRequestContent()
    {
        /** @var Request $request */
        $request = $this->container->get('request_stack')->getCurrentRequest();

        if (json_decode($request->getContent())) {
            $data = json_decode($request->getContent());
        } else {
            $data = $request->request;
        }

        return $data;
    }
}