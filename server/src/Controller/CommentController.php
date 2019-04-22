<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class CommentController extends BaseController
{
    /**
     * Get comment
     *
     * @Route("/comment", methods={"GET"})

     *
     * @SWG\Response(
     *     response=200,
     *     description="Get comment"
     * )
     * @SWG\Tag(name="Comment")
     *
     */
    public function getComment()
    {
       return $this->setResponse('Awesome it works!!');
    }
}