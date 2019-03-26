<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\HttpConflictException;
use App\Exception\UniqueValueException;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @param SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws HttpConflictException
     * @throws UniqueValueException
     * @throws ValidationException
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns created user info"
     * )
     * @SWG\Tag(name="User")
     *
     */
    public function register(SerializerInterface $serializer)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $requestData = $this->getRequestContent();

        if (!isset($requestData->email) || !isset($requestData->password)) {
            throw new HttpConflictException('email and password field is required');
        }

        $user = $em->getRepository(User::class)
            ->findOneBy(['email' => $requestData->email]);

        if ($user) {
            throw new UniqueValueException('User already exists');
        }

        $user = $this->deserialize($requestData, User::class);

        $this->validate($user);

        $em->persist($user);
        $em->flush();

        return $this->setResponse('User registered successfully');
    }
}