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

class ReplyController extends BaseController
{
    /**
     * Create reply
     *
     * @Route("/reply", methods={"POST"})

     *
     * @param Validator $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \App\Exception\HttpBadRequestException
     * @throws \App\Exception\HttpConflictException
     * @throws \App\Exception\ValidationException
     *
     * @SWG\Response(
     *     response=200,
     *     description="Create Reply"
     * )
     * @SWG\Tag(name="Reply")
     *
     */
    public function create(Validator $validator)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $requestData = $this->getRequestContent();

        $validator
            ->setValidator(
                v::notEmpty()->stringType(),
                'comment',
                'comment is required and must be a string type',
                true
            )
            ->setValidator(
                v::noWhitespace()->notEmpty()->intType(),
                'parent',
                'parent is required and  must be a integer type',
                true
            )
            ->setValidator(
                v::noWhitespace()->notEmpty()->intType(),
                'post',
                'post is required and  must be a integer type',
                true
            )
            ->validate($requestData);


        $identity = $this->getIdentity();

        /** @var User $user */
        $user = $em->getRepository(User::class)
            ->find($identity->getId());

        /** @var Post $post */
        $parentComment = $em->getRepository(Comment::class)
            ->find($requestData->parent);

        /** @var Post $post */
        $post = $em->getRepository(Post::class)
            ->find($requestData->post);

        $comment = $this->deserialize($requestData, Comment::class, [
            AbstractNormalizer::IGNORED_ATTRIBUTES => Comment::GUARDED_FIELDS
        ]);

        $comment->setParent($parentComment);
        $comment->setPost($post);
        $comment->setUser($user);

        $em->persist($comment);
        $em->flush();

       return $this->setResponse('Reply created successfully');
    }
}