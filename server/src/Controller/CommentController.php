<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Service\Validator;
use Doctrine\ORM\EntityManager;
use Respect\Validation\Validator as v;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class CommentController extends BaseController
{
    /**
     * Create comment
     *
     * @Route("/comment", methods={"POST"})
     *
     * @param Validator $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \App\Exception\HttpBadRequestException
     * @throws \App\Exception\HttpConflictException
     * @throws \App\Exception\ValidationException
     *
     * @SWG\Response(
     *     response=200,
     *     description="Create Comment"
     * )
     * @SWG\Tag(name="Comment")
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
                v::notEmpty()->intType(),
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
        $post = $em->getRepository(Post::class)
            ->find($requestData->post);

        $comment = $this->deserialize($requestData, Comment::class, [
            AbstractNormalizer::IGNORED_ATTRIBUTES => Comment::GUARDED_FIELDS
        ]);

        $comment->setPost($post);
        $comment->setUser($user);

        $em->persist($comment);
        $em->flush();

       return $this->setResponse('Comment created successfully');
    }

    /**
     * Get comment
     *
     * @Route("/comment", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \App\Exception\HttpBadRequestException
     * @throws \App\Exception\HttpConflictException
     * @throws \App\Exception\ValidationException
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get Comment"
     * )
     * @SWG\Tag(name="Comment")
     *
     */
    public function getList(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $serializer = ($request->get('serializer', null))
            ?: [
                'comment', 'id', 'reply', 'creationDate', 'user'
            ];

        $commentObject = $this->getDoctrine()->getManager()
            ->getRepository(Comment::class)
            ->getCommentList([]);


        /** @var User $user */
        $comment = '';

        return $this->setResponse($commentObject, 200, [], [
            AbstractNormalizer::ATTRIBUTES => $serializer,
            AbstractNormalizer::IGNORED_ATTRIBUTES => Comment::HIDDEN_FIELDS,
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
    }
}