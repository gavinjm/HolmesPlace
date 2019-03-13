<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

//use Symfony\Component\Routing\Annotation\Route;

class HolmesPlaceController extends AbstractController
{
   
    public function index()
    {
        return $this->render('holmes_place/index.html.twig', [
            'controller_name' => 'HolmesPlaceController',
        ]);
    }
    
    public function SaveFuelEntry(Request $request){
       
    }
    
    public function FuelEntry(){
        return $this->render('holmes_place/fuelentry.html.twig', [
            'action' => 'Capture a New Fuel Entry',
        ]);
    }
    
    public function NewServer(){
        return $this->render('holmes_place/serverentry.html.twig', [
            'action' => 'New Server Entry',
        ]);
    }
}
