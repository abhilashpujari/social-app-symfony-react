<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Exception\HttpBadRequestException;
use App\Exception\HttpNotFoundException;
use App\Service\Validator;
use Doctrine\ORM\EntityManager;
use Respect\Validation\Validator as v;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class CommentController
 * @package App\Controller
 */
class CommentController extends BaseController
{
    /**
     * Create comment
     *
     * @Route("/comment", methods={"POST"})
     *
     * @param Validator $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws HttpNotFoundException
     * @throws \App\Exception\HttpBadRequestException
     * @throws \App\Exception\HttpConflictException
     * @throws \App\Exception\ValidationException
     *
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="JSON Payload",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *     type="object",
     *         @SWG\Property(property="body", type="string", example="test content"),
     *         @SWG\Property(property="post", type="integer", example=1)
     *     )
     * )
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
                'body',
                'body is required and must be a string type',
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

        if (!$user) {
            throw new HttpNotFoundException('Post not found with id' . $requestData->post);
        }

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
    public function commentList(Request $request)
    {
        $param = $request->query->getIterator()->getArrayCopy();
        $criteria = isset($param['criteria']) ? $param['criteria'] : [];

        $criteria = array_merge_recursive($criteria, [
            'order' => ['creationDate DESC']
        ]);

        $serializer = ($request->get('serializer', null))
            ?: [
                'body', 'id', 'post' => ['id'], 'creationDate', 'user' => ['id', 'fullName']
            ];

        $commentObject = $this->getDoctrine()->getManager()
            ->getRepository(Comment::class)
            ->getCommentList($criteria);

        $response = [
            'data' => $commentObject
        ];

        return $this->setResponse($response, 200, [], [
            AbstractNormalizer::ATTRIBUTES => $serializer,
            AbstractNormalizer::IGNORED_ATTRIBUTES => Comment::HIDDEN_FIELDS
        ]);
    }
}