<?php

namespace App\Controller;

use App\Core\Doctrine\Pagination;
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
     * @Route("/reply", methods={"POST"}, name="reply_create")

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

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $identity = $this->getIdentity();

        /** @var User $user */
        $user = $em->getRepository(User::class)
            ->find($identity->getId());

        /** @var Comment $comment */
        $comment = $em->getRepository(Comment::class)
            ->find($requestData->comment);

        if (!$comment) {
            throw new HttpNotFoundException('Comment not found with id' . $requestData->comment);
        }

        $reply = $this->deserialize($requestData, Reply::class, [
            AbstractNormalizer::IGNORED_ATTRIBUTES => Comment::GUARDED_FIELDS
        ]);

        $reply->setComment($comment);
        $reply->setUser($user);

        $em->persist($reply);
        $em->flush();

        $serializer = ['body', 'id', 'comment' => ['id'], 'creationDate', 'user' => ['id', 'fullName']];

        return $this->setResponse($reply, 200, [], [
            AbstractNormalizer::ATTRIBUTES => $serializer,
            AbstractNormalizer::IGNORED_ATTRIBUTES => Reply::HIDDEN_FIELDS
        ]);
    }

    /**
     * Get reply
     *
     * @Route("/reply", methods={"GET"}, name="reply_list")
     *
     * @param Request $request
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
        $criteria = $request->get('criteria') ? $request->get('criteria') : [];

        $criteria = array_merge_recursive($criteria, [
            'order' => ['creationDate DESC']
        ]);

        $serializer = ($request->get('serializer', null))
            ?: [
                'body', 'id', 'comment' => ['id'], 'creationDate', 'user' => ['id', 'fullName']
            ];


        $limit = ($request->get('limitPerPage') && intval($request->get('limitPerPage')) < 10)
            ? intval($request->get('limitPerPage'))
            : 10;

        $pagination = new Pagination(
            $limit,
            (($request->get('page')) ? intval($request->get('page')) : 1)
        );

        $paginatedData = $this->getDoctrine()->getManager()
            ->getRepository(Reply::class)
            ->getReplyList($criteria, $pagination);

        $response = [
            'data' => $paginatedData['result'],
            'page' => $paginatedData['currentPage'],
            'count' => $paginatedData['count'],
            'offset' => $paginatedData['offset'],
            'limitPerPage' => $paginatedData['limitPerPage'],
            'criteria' => $criteria
        ];
        
        return $this->setResponse($response, 200, [], [
            AbstractNormalizer::ATTRIBUTES => $serializer,
            AbstractNormalizer::IGNORED_ATTRIBUTES => Reply::HIDDEN_FIELDS
        ]);
    }
}