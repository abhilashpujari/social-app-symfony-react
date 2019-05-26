<?php

namespace App\Controller;

use App\Entity\User;
use App\Helper;
use App\Service\Mailer;
use App\Service\Validator;
use App\Token;
use Doctrine\ORM\EntityManager;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Respect\Validation\Validator as v;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * Class PasswordController
 * @package App\Controller
 */
class PasswordController extends  BaseController
{
    /**
     * Forgot Password
     *
     * @Route("/forgot-password", methods={"POST"})
     *
     * @param Validator $validator
     * @param CacheItemPoolInterface $redisCache
     * @param Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \App\Exception\HttpBadRequestException
     * @throws \App\Exception\HttpConflictException
     * @throws \App\Exception\ValidationException
     *
     * @SWG\Response(
     *     response=200,
     *     description="Forgot Password"
     * )
     * @SWG\Tag(name="Password")
     *
     */
    public function forgotPassword(
        Validator $validator,
        CacheItemPoolInterface $redisCache,
        Mailer $mailer
    )
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $requestData = $this->getRequestContent();

        $validator
            ->setValidator(
                v::noWhitespace()->email(),
                'email',
                'email must be a valid email address',
                true
            )
            ->validate($requestData);

        /** @var User $user */
        $user = $em->getRepository(User::class)
            ->findOneBy(['email' => $requestData->email]);

        if (!$user) {
            throw new NotFoundHttpException('Invalid User!!');
        }

        $token = Helper::generateUuid();

        /** @var CacheItemInterface $cachedItem */
        $cachedItem = $redisCache->getItem(Token::FORGOT_PASSWORD_PREFIX . $token);
        $cachedItem->expiresAfter(60 * 60 * 24); // 1 day
        $cachedItem->set($user->getId());
        $redisCache->save($cachedItem);

        $mailerParams = [
            'resetLink' => getenv('WEB_APP_DOMAIN') . '/reset-password/' . $token
        ];
        $mailer->sendEmail('forgot-password.html.twig', $mailerParams, $user->getEmail(), getenv('MAILER_EMAIL'), getenv('MAILER_NAME'));

        return $this->setResponse('');
    }

    /**
     * Update Password
     *
     * @Route("/reset-password", methods={"PUT"})
     *
     * @param Validator $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \App\Exception\HttpBadRequestException
     * @throws \App\Exception\HttpConflictException
     * @throws \App\Exception\ValidationException
     *
     * @SWG\Response(
     *     response=200,
     *     description="Update Password"
     * )
     * @SWG\Tag(name="Password")
     *
     */
    public function resetPassword(Validator $validator, CacheItemPoolInterface $redisCache)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $requestData = $this->getRequestContent();

        $validator
            ->setValidator(
                v::stringType()->noWhitespace()->notEmpty()->length(3),
                'password',
                'password must be a string type with minimum length of 3',
                true
            )
            ->setValidator(
                v::stringType()->noWhitespace()->notEmpty(),
                'token',
                'token must be a string type',
                true
            )
            ->validate($requestData);

        $token = Token::FORGOT_PASSWORD_PREFIX . $requestData->token;

        if (false === $redisCache->hasItem($token)) {
            throw new Exception('Something went wrong!!!');
        }

        /** @var CacheItemInterface $cachedItem */
        $cachedItem = $redisCache->getItem($token);
        $userId = $cachedItem->get();

        /** @var User $user */
        $user = $em->getRepository(User::class)
            ->find($userId);

        if (!$user) {
            throw new NotFoundHttpException('Invalid User!!');
        }

        $user->setPassword($requestData->password);
        $em->persist($user);
        $em->flush();

        return $this->setResponse('Password successfully updated!!!');
    }
}