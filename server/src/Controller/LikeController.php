<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostLike;
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
 * Class LikeController
 * @package App\Controller
 */
class LikeController extends BaseController
{
    /**
     * Create post like
     *
     * @Route("/post/{id}/like", methods={"POST"}, name="post_like_create", requirements={"id"="\d+"})
     *
     * @param Request $request
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
     *         @SWG\Property(property="type", type="string"),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Create Post Like"
     * )
     * @SWG\Tag(name="Like")
     *
     */
    public function likePost(Request $request, Validator $validator)
    {
        $requestData = $this->getRequestContent();

        $validator
            ->setValidator(
                v::notEmpty()->stringType()->in(['like', 'dislike']),
                'type',
                'type is required and must be one of: like, dislike',
                true
            )
            ->validate($requestData);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $isLiked = $requestData->type === 'like';
        $postId = $request->get('id');

        $identity = $this->getIdentity();

        /** @var User $user */
        $user = $em->getRepository(User::class)
            ->find($identity->getId());

        /** @var Post $post */
        $post = $em->getRepository(Post::class)
            ->find($postId);

        if (!$post) {
            throw new HttpNotFoundException('Post not found with id' . $postId);
        }

        if ($post->getUser()->getId() === $identity->getId()) {
            throw new HttpBadRequestException('Your can\'t like your own post');
        }

        /** @var Post $post */
        $postLike = $em->getRepository(PostLike::class)
            ->findOneBy(['post' => $postId, 'user' => $identity->getId()]);

        if (!$postLike) {
            $postLike = new PostLike();
            $postLike->setUser($user);
            $postLike->setPost($post);
        } else {
            if ($postLike->getIsLiked() == $isLiked) {
                if ($isLiked)  {
                    throw new HttpBadRequestException('You already like this post');
                } else {
                    throw new HttpBadRequestException('Your already disliked this post');
                }
            }
        }

        $postLike->setIsLiked($isLiked);

        $em->persist($postLike);
        $em->flush();

        $serializer = [
            'body', 'id', 'likeCount', 'dislikeCount', 'likes' => ['user' => ['id', 'fullName'], 'isLiked'], 'creationDate', 'user' => ['id', 'fullName']
        ];

        return $this->setResponse($post, 200, [], [
            AbstractNormalizer::ATTRIBUTES => $serializer,
            AbstractNormalizer::IGNORED_ATTRIBUTES => Post::HIDDEN_FIELDS
        ]);
    }
}