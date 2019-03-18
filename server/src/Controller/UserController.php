<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class UserController extends BaseController
{
    /**
     * @SWG\Response(
     *     response=200,
     *     description="Returns the rewards of an user"
     * )
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }
}
