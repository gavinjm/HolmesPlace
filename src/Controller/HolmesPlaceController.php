<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HolmesPlaceController extends AbstractController
{
    /**
     * @Route("/", name="holmes_place")
     */
    public function index()
    {
        return $this->render('holmes_place/index.html.twig', [
            'controller_name' => 'HolmesPlaceController',
        ]);
    }
}
