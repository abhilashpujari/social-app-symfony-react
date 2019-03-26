<?php
namespace App\Controller;

use App\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BaseController
 * @package App\Controller
 */
class BaseController extends AbstractController
{
    /** @var  ValidatorInterface $validator */
    protected $validator;

    /** @var SerializerInterface $deserialize */
    protected $deserialize;

    public function __construct(
        SerializerInterface $deserialize,
        ValidatorInterface $validator
    )
    {
        $this->deserialize = $deserialize;
        $this->validator =  $validator;
        $this->init();
    }

    protected function init()
    {
    }

    protected function validate($entity)
    {
        $errors = [];
        foreach ($this->validator->validate($entity) as $error) {
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
     * @param string $format
     * @return object
     */
    protected function deserialize($data, $class, $format = 'json')
    {
        $data = is_object($data) || is_array($data)
            ? json_encode($data)
            : $data;

        return $this->deserialize->deserialize($data, $class, $format);
    }

    /**
     * @param null $content
     * @param int $statusCode
     * @param array $headers
     * @param string $format
     * @return JsonResponse|Response
     */
    protected function setResponse($content = null, $statusCode = 200, $headers = array(), $format = 'json')
    {
        /** @var Request $request */
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $accept = $request->getAcceptableContentTypes();

        if (in_array('application/json', $accept) || $format == 'json') {
            $response = new JsonResponse($content, $statusCode, $headers);
        } else {
            $response = new Response($content, $statusCode, $headers);
        }

        return $response;
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