<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseController
 * @package App\Controller
 */
class BaseController extends AbstractController
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {

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