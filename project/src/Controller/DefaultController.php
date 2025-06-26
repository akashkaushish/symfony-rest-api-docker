<?php
// src/Controller/DefaultController.php
  namespace App\Controller;

  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\Routing\Annotation\Route;

  class DefaultController extends AbstractController
  {
    #[Route('/', name: 'default')]
      public function index(): string
      {
        return "we are here to make APIS";
      }
  }
