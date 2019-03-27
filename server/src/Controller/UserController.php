<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\HttpConflictException;
use App\Exception\UniqueValueException;
use App\Exception\ValidationException;
use App\Service\Validator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\SerializerInterface;
use Respect\Validation\Validator as v;

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
     * @param Validator $validator
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
    public function register(SerializerInterface $serializer, Validator $validator)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $requestData = $this->getRequestContent();

        $validator
            ->setValidator(
                v::notEmpty()->email(),
                'email',
                'email must be a valid email address'
            )
            ->setValidator(
                v::stringType()->notEmpty()->length(3),
                'password',
                'password must be a string type with minimum length of 3'
            )
            ->validate($requestData);

        $user = $em->getRepository(User::class)
            ->findOneBy(['email' => $requestData->email]);

        if ($user) {
            throw new UniqueValueException('User already exists');
        }

        $user = $this->deserialize($requestData, User::class);

        $em->persist($user);
        $em->flush();

        return $this->setResponse('User registered successfully');
    }
}