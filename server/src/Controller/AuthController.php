<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\HttpBadRequestException;
use App\Exception\HttpConflictException;
use App\Exception\HttpUnauthorizedException;
use App\Exception\UniqueValueException;
use App\Exception\ValidationException;
use App\Service\Validator;
use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Google_Client;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Respect\Validation\Validator as v;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

use App\Service\SocialLogin;

/**
 * Class AuthController
 * @package App\Controller
 */
class AuthController extends BaseController
{
    /**
     * Authenticate User
     *
     * @Route("/authenticate", methods={"POST"}, name="user_authenticate")
     *
     * @param Validator $validator
     * @param JWTEncoderInterface $jwtEncoder
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws HttpConflictException
     * @throws HttpUnauthorizedException
     * @throws ValidationException
     * @throws \App\Exception\HttpBadRequestException
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="JSON Payload",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *     type="object",
     *         @SWG\Property(property="email", type="string"),
     *         @SWG\Property(property="password", type="string")
     *     )
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Authenticate user"
     * )
     * @SWG\Tag(name="Auth")
     *
     */
    public function authenticate(Validator $validator, JWTEncoderInterface $jwtEncoder)
    {
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

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $user = $em->getRepository(User::class)
            ->findOneBy(['email' => $requestData->email]);

        if (!$user) {
            throw new NotFoundHttpException('Incorrect email or password');
        }

        if (!$user->getPassword() && ($user->getSocialProvider() && $user->getSocialId())) {
            throw new HttpBadRequestException('It seems you previously logged in using one of your\'s social account. Please login with same');
        }

        if (!$user->verifyPassword($requestData->password)) {
            throw new HttpUnauthorizedException('Incorrect email or password');
        }

        $token = $jwtEncoder
            ->encode([
                'fullName' => $user->getFullName(),
                'id' => $user->getId(),
                'roles' => $user->getRoles(),
                'isActive' => $user->getIsActive()
            ]);

        $user->setLastAccessTime(new \DateTime('now'));

        $em->persist($user);
        $em->flush();

        return $this->setResponse('Authenticated successfully', 200, ['X-Auth-Token' => $token]);
    }

    /**
     * User Social Login
     *
     * @Route("/social-login", methods={"POST"}, name="user_social_login")
     *
     * @param Validator $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws HttpConflictException
     * @throws HttpUnauthorizedException
     * @throws ValidationException
     * @throws \App\Exception\HttpBadRequestException
     *
     * @param Validator $validator
     * @param JWTEncoderInterface $jwtEncoder
     * @param SocialLogin $socialLogin
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws HttpConflictException
     * @throws ValidationException
     * @throws \App\Exception\HttpBadRequestException
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="JSON Payload",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *     type="object",
     *         @SWG\Property(property="tokenId", type="string")
     *         @SWG\Property(property="provider_type", type="string")
     *     )
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Social Login user"
     * )
     * @SWG\Tag(name="Auth")
     *
     */
    public function socialLogin(Validator $validator, JWTEncoderInterface $jwtEncoder, SocialLogin $socialLogin)
    {
        $requestData = $this->getRequestContent();

        $validator
            ->setValidator(
                v::noWhitespace()->notEmpty()->stringType()->in(['google', 'facebook']),
                'provider_type',
                'provider_type must be oneOf: google, facebook',
                true
            )
            ->setValidator(
                v::stringType()->noWhitespace()->notEmpty()->stringType(),
                'token',
                'token must be a string type',
                true
            )
            ->validate($requestData);

        $socialUser = $socialLogin->verifySocialUser($requestData->provider_type, $requestData->token);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $user = $em->getRepository(User::class)
            ->findOneBy(['email' => $socialUser->email]);

        // Check if user exists else create new one
        if (!$user) {
            $user = new User();
            $user->setEmail($socialUser->email);
            $user->setFirstName($socialUser->firstName);
            $user->setLastName($socialUser->lastName);
            $user->setRoles([User::ROLE_USER]);
        }

        $user->setSocialId($socialUser->id);
        $user->setSocialProvider($requestData->provider_type);

        $em->persist($user);
        $em->flush();

        $token = $jwtEncoder
            ->encode([
                'fullName' => $user->getFullName(),
                'id' => $user->getId(),
                'roles' => $user->getRoles(),
                'isActive' => $user->getIsActive()
            ]);

        return $this->setResponse('Authenticated successfully', 200, ['X-Auth-Token' => $token]);
    }

    /**
     * User Registration
     *
     * @Route("/register", methods={"POST"}, name="user_register")
     *
     * @param Validator $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws HttpConflictException
     * @throws UniqueValueException
     * @throws ValidationException
     * @throws \App\Exception\HttpBadRequestException
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="JSON Payload",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *     type="object",
     *         @SWG\Property(property="email", type="string"),
     *         @SWG\Property(property="password", type="string")
     *     )
     * )
     *
     *
     * @SWG\Response(
     *     response=200,
     *     description="Register user"
     * )
     * @SWG\Tag(name="Auth")
     *
     */
    public function register(Validator $validator)
    {
        $requestData = $this->getRequestContent();

        $validator
            ->setValidator(
                v::stringType()->notEmpty()->length(3),
                'firstName',
                'firstName is required and must be a string of min length 3',
                true
            )
            ->setValidator(
                v::stringType()->notEmpty()->length(3),
                'lastName',
                'lastName is required and must be a string of min length 3',
                true
            )
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

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $user = $em->getRepository(User::class)
            ->findOneBy(['email' => $requestData->email]);

        if ($user) {
            throw new UniqueValueException('User already exists');
        }

        $user = $this->deserialize($requestData, User::class, [
            AbstractNormalizer::IGNORED_ATTRIBUTES => USER::GUARDED_FIELDS
        ]);

        $user->setRoles([User::ROLE_USER]);

        $em->persist($user);
        $em->flush();

        return $this->setResponse('User registered successfully');
    }
}
