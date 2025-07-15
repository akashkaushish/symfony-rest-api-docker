<?php

// src/Controller/DefaultController.php
  namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default')]
    public function index(): Response
    {
        return new Response("we are here to make APIS");
    }
}
