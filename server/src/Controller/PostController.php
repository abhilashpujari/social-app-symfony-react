<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Service\Validator;
use Doctrine\ORM\EntityManager;
use Respect\Validation\Validator as v;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class PostController extends BaseController
{
    /**
     * Create post
     *
     * @Route("/post", methods={"POST"})
     *
     * @param Validator $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \App\Exception\HttpBadRequestException
     * @throws \App\Exception\HttpConflictException
     * @throws \App\Exception\ValidationException
     *
     * @SWG\Response(
     *     response=200,
     *     description="Create Post"
     * )
     * @SWG\Tag(name="Post")
     *
     */
    public function create(Validator $validator)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $requestData = $this->getRequestContent();

        $validator
            ->setValidator(
                v::oneOf(
                    v::notEmpty()->noWhitespace()->stringType(),
                    v::nullType()
                ),
                'content',
                'content must be a string or null type'
            )
            ->validate($requestData);

        $identity = $this->getIdentity();

        /** @var User $user */
        $user = $em->getRepository(User::class)
            ->find($identity->getId());

        $post = $this->deserialize($requestData, Post::class, [
            AbstractNormalizer::IGNORED_ATTRIBUTES => Post::GUARDED_FIELDS
        ]);

        $post->setUser($user);

        $em->persist($post);
        $em->flush();

        return $this->setResponse('Post created successfully');
    }
}