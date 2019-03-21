<?php

namespace App\Controller;

use App\Exception\HttpNotFoundException;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends BaseController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        throw new HttpNotFoundException('Route not found');
    }
}
