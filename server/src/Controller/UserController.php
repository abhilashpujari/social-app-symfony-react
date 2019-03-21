<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\HttpConflictException;
use App\Exception\UniqueValueException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends BaseController
{
    /**
     * Creates user
     *
     * @Route("/user", methods={"POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws HttpConflictException
     * @throws UniqueValueException
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns created user info"
     * )
     * @SWG\Tag(name="User")
     *
     */
    public function register(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $requestData = $this->getRequestContent();

        $user = $em->getRepository(User::class)->findOneBy(['email' => $requestData->email]);

        if ($user) {
            throw new UniqueValueException('User already exists');
        }

        if (!isset($requestData->email) || !isset($requestData->password)) {
            throw new HttpConflictException('email and password field is required');
        }

        $user = new User();
        $user->setEmail($requestData->email);
        $user->setPassword($requestData->password);
        $em->persist($user);
        $em->flush();

        return $this->setResponse('User registered successfully');
    }
}
