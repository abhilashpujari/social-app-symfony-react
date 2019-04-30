<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\HttpConflictException;
use App\Exception\HttpUnauthorizedException;
use App\Exception\UniqueValueException;
use App\Exception\ValidationException;
use App\Service\Validator;
use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Respect\Validation\Validator as v;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends BaseController
{
    /**
     * Authenticate User
     *
     * @Route("/authenticate", methods={"POST"})
     *
     * @param Validator $validator
     * @param JWTEncoderInterface $jwtEncoder
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws HttpConflictException
     * @throws HttpUnauthorizedException
     * @throws ValidationException
     * @throws \App\Exception\HttpBadRequestException
     *
     * @SWG\Response(
     *     response=200,
     *     description="Authenticate user"
     * )
     * @SWG\Tag(name="User")
     *
     */
    public function authenticate(Validator $validator, JWTEncoderInterface $jwtEncoder)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $requestData = $this->getRequestContent();

        $validator
            ->setValidator(
                v::noWhitespace()->notEmpty()->email(),
                'email',
                'email must be a valid email address',
                true
            )
            ->setValidator(
                v::stringType()->noWhitespace()->notEmpty()->length(3),
                'password',
                'password must be a string type with minimum length of 3',
                true
            )
            ->validate($requestData);

        /** @var User $user */
        $user = $em->getRepository(User::class)
            ->findOneBy(['email' => $requestData->email]);

        if (!$user) {
            throw new NotFoundHttpException('User not found!!');
        }

        if (!$user->verifyPassword($requestData->password)) {
            throw new HttpUnauthorizedException('Authorization failed!!');
        }

        $token = $jwtEncoder
        ->encode([
            'fullName' => $user->getFullName(),
            'id' => $user->getId(),
            'roles' => $user->getRoles()
        ]);

        $user->setLastAccessTime(new \DateTime('now'));

        $em->persist($user);
        $em->flush();

        return $this->setResponse('Authenticated successfully', 200, ['X-AUTH-TOKEN' => $token]);
    }


    /**
     * User Registration
     *
     * @Route("/register", methods={"POST"})
     *
     * @param Validator $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws HttpConflictException
     * @throws UniqueValueException
     * @throws ValidationException
     * @throws \App\Exception\HttpBadRequestException
     *
     * @SWG\Response(
     *     response=200,
     *     description="Register user"
     * )
     * @SWG\Tag(name="User")
     *
     */
    public function register(Validator $validator)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $requestData = $this->getRequestContent();

        $validator
            ->setValidator(
                v::noWhitespace()->notEmpty()->email(),
                'email',
                'email must be a valid email address',
                true
            )
            ->setValidator(
                v::stringType()->noWhitespace()->notEmpty()->length(3),
                'password',
                'password must be a string type with minimum length of 3',
                true
            )
            ->validate($requestData);

        /** @var User $user */
        $user = $em->getRepository(User::class)
            ->findOneBy(['email' => $requestData->email]);

        if ($user) {
            throw new UniqueValueException('User already exists');
        }

        $user = $this->deserialize($requestData, User::class, [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['roles', 'lastAccessTime']
        ]);

        $user->setRoles([User::ROLE_USER]);

        $em->persist($user);
        $em->flush();

        return $this->setResponse('User registered successfully');
    }
}