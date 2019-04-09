<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\FuelType;
use App\Form\Type\TripType;
use App\Entity\FuelLog;
use App\Entity\Trip;


//use Symfony\Component\Routing\Annotation\Route;

class HolmesPlaceController extends AbstractController
{
   
    public function index()
    {
        $te = $this->GetTripEntries();
        $fe = $this->GetFuelEntries();
        
        return $this->render('holmes_place/index.html.twig', [
            'controller_name' => 'Holmes Place Website',
            'current_date' => date("F j, Y, g:i a"),
            'trip_entries' => $te,
            'fuel_entries' => $fe,
        ]);
    }
    
    
    /** Success page for all database entries
    *  
    */
    public function task_success(){
        
        return $this->render('holmes_place/task_success.html.twig'); 
        
    }
    
    /**getTrips
     * Returns all trip entries from table trips
     * 
     */
    public function GetTripEntries(){
       
        $repository = $this->getDoctrine()->getRepository(Trip::class);
        $tripEntry = $repository->findAll();
        return $tripEntry;
    }
    
    
    /*
     * getFuelEntries
     * Returns last 10 fuel entries from the database into an array
     */
    public function GetFuelEntries(){
       
        $repository = $this->getDoctrine()->getRepository(FuelLog::class);
        $fuelEntry = $repository->findAll();
        return $fuelEntry;
    }
    
    /**
     * CryptoEntry
     * Captures the current crypto prices.
     * 
     */
    public function CryptoEntry(){
       return $this->render('holmes_place/crypto.html.twig', [
            'action' => 'New Crypto Entry',
            'current_date' => date("F j, Y, g:i a"),
        ]);
    }
    
    /**TripEntry
     * Capture a new trip entry.
     * If it is a business trip check the check-box else leave blank.
     * 
     */
    public function TripEntry(Request $request){
        $trip = new Trip();
        $form = $this->createForm(TripType::class,$trip);
        $form->handleRequest($request);
        
         if ($form->isSubmitted() && $form->isValid()) {
        // $form->getData() holds the submitted values
        // but, the original `$task` variable has also been updated
        $task = $form->getData();
        
        // Save the entry to the database.
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($task);
         $entityManager->flush();

        return $this->redirectToRoute('task_success');
    }
               
        return $this->render('holmes_place/tripentry.html.twig',[
            'action' => 'New Trip Entry',
            'form' => $form->createView(),
            'current_date' => date("F j, Y, g:i a"),
        ]);
    }
    
     /** FuelEntry
     * Provides a form to capture a new fuel entry
     * Entry is saved to database.
     * Displays the last 10 entries from the database.
     */
    public function FuelEntry(Request $request){
        
        
        $fuelEntry = new FuelLog();
        $form = $this->createForm(FuelType::class,$fuelEntry);
        $form->handleRequest($request);
        
        /* Check if form is submitted */
        if ($form->isSubmitted() && $form->isValid()) {
        // $form->getData() holds the submitted values
        // but, the original `$task` variable has also been updated
        $task = $form->getData();
        
        // Save the entry to the database.
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($task);
         $entityManager->flush();

        return $this->redirectToRoute('task_success');
    }
        
       return $this->render('holmes_place/fuelentry.html.twig', [
            'action' => 'New Fuel Entry Form ','form' => $form->createView(),
           
        ]);
    }
    
    public function NewServer(){
        return $this->render('holmes_place/serverentry.html.twig', [
            'action' => 'New Server Entry',
        ]);
    }
}
