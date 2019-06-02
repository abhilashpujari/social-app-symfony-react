<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Reply;
use App\Entity\User;
use App\Exception\HttpNotFoundException;
use App\Service\Validator;
use Doctrine\ORM\EntityManager;
use Respect\Validation\Validator as v;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class ReplyController
 * @package App\Controller
 */
class ReplyController extends BaseController
{
    /**
     * Create reply
     *
     * @Route("/reply", methods={"POST"})

     *
     * @param Validator $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws HttpNotFoundException
     * @throws \App\Exception\HttpBadRequestException
     * @throws \App\Exception\HttpConflictException
     * @throws \App\Exception\ValidationException
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="JSON Payload",
     *     required=true,
     *     format="application/json",
     *     @SWG\Schema(
     *     type="object",
     *         @SWG\Property(property="body", type="string"),
     *         @SWG\Property(property="comment", type="integer")
     *     )
     * )
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
                'body',
                'body is required and must be a string type',
                true
            )
            ->setValidator(
                v::notEmpty()->intType(),
                'comment',
                'comment is required and must be a integer type',
                true
            )
            ->validate($requestData);

        $identity = $this->getIdentity();

        /** @var User $user */
        $user = $em->getRepository(User::class)
            ->find($identity->getId());

        /** @var Comment $comment */
        $comment = $em->getRepository(Comment::class)
            ->find($requestData->comment);

        if (!$user) {
            throw new HttpNotFoundException('Comment not found with id' . $requestData->cooment);
        }

        $reply = $this->deserialize($requestData, Reply::class, [
            AbstractNormalizer::IGNORED_ATTRIBUTES => Comment::GUARDED_FIELDS
        ]);

        $reply->setComment($comment);
        $reply->setUser($user);

        $em->persist($reply);
        $em->flush();

       return $this->setResponse('Reply created successfully');
    }

    /**
     * Get reply
     *
     * @Route("/reply", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \App\Exception\HttpBadRequestException
     * @throws \App\Exception\HttpConflictException
     * @throws \App\Exception\ValidationException
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get Reply"
     * )
     * @SWG\Tag(name="Reply")
     *
     */
    public function replyList(Request $request)
    {
        $param = $request->query->getIterator()->getArrayCopy();
        $criteria = isset($param['criteria']) ? $param['criteria'] : [];

        $criteria = array_merge_recursive($criteria, [
            'order' => ['creationDate DESC']
        ]);

        $serializer = ($request->get('serializer', null))
            ?: [
                'body', 'id', 'comment' => ['id'], 'creationDate', 'user' => ['id', 'fullName']
            ];

        $replyObject = $this->getDoctrine()->getManager()
            ->getRepository(Reply::class)
            ->getReplyList($criteria);

        $response = [
            'data' => $replyObject
        ];
        
        return $this->setResponse($response, 200, [], [
            AbstractNormalizer::ATTRIBUTES => $serializer,
            AbstractNormalizer::IGNORED_ATTRIBUTES => Reply::HIDDEN_FIELDS
        ]);
    }
}