<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Auth;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * Class AccountController
 * @package App\Controller
 */
class AccountController extends BaseController
{
    /**
     * Get User Account Details
     *
     * @Route("/account", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get user account info"
     * )
     * @SWG\Tag(name="User")
     *
     */
    public function view()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Auth $identity */
        $identity = $this->getIdentity();

        /** @var User $user */
        $user = $em->getRepository(User::class)
            ->find($identity->getId());

        if (!$user) {
            throw new NotFoundHttpException('User not found!!');
        }

        return $this->setResponse($user, 200, []);
    }
}