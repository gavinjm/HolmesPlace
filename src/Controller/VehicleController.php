<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Form\Type\TripType;
use App\Entity\Trip;
use App\Form\Type\FuelType;
use App\Entity\Fuel;

/**
 * Description of VehicleController
 *
 * @author HolmesPlace
 */
class VehicleController extends AbstractController {
    
    //put your code here
    public function v_index(Request $request){
     // Get the current date
     $dt = getdate();
    // Create a form to capture year and month
        $defaultData = ['month'=>0,'year'=>$dt['year']];
        $form = $this->createFormBuilder($defaultData)
                ->add('year',TextType::class)
                ->add('month',TextType::class)
                ->add('Go',SubmitType::class)
                ->getForm();
        
// Handles the form data for submitted requests
        $data=[];
        $fuelEntries = [];   
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $fuelEntries = $this->getFuelEntriesForMonthYear((int)$data['month'],(int)$data['year']);
           
        } 
       
        return $this->render('Vehicles/vehicle_index.html.twig', [
            'page' => 'Vehicle Index Page',
            'form' => $form->createView(),
            'data' => $fuelEntries,
            ]);
    }
    
    /** FuelEntry
     * Provides a form to capture a new fuel entry
     * Entry is saved to database.
     * Displays the last 10 entries from the database.
     */
    public function FuelEntry(Request $request){
        
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');        
        $fuelEntry = new Fuel();
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

        return $this->redirectToRoute('vehicle');
    }
        
       return $this->render('Vehicles/fuelentry.html.twig', [
            'action' => 'New Fuel Entry Form ',
            'form' => $form->createView(),
           
        ]);
    }
    
    /**TripEntry
     * Capture a new trip entry.
     * If it is a business trip check the check-box else leave blank.
     * 
     */
    public function TripEntry(Request $request){
         $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
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
               
        return $this->render('Vehicles/tripentry.html.twig',[
            'action' => 'New Trip Entry',
            'form' => $form->createView(),
            'current_date' => date("F j, Y, g:i a"),
        ]);
    }
    
    /** 
     * getFuelEntriesForMonthYear
     * Returns all fuel entries for month $mnth and year $yr
     * parameter: $mnth
     * Table: fuel_log
     * id => int(11)
     * date => date
     * odometer => int(11)
     * liters => double
     * amount => double
     * location =>varchar(255)
     * tankfull => tinyint(1)
     */
    public function getFuelEntriesForMonthYear($mnth,$yr)
    {   
        $em = $this->getDoctrine()->getManager();
        if ($mnth==0) {
            $sql = "SELECT * FROM fuel where YEAR(date) like ".$yr;
          } else { 
        $sql = "SELECT * FROM fuel where MONTH(date) like ".$mnth;
        $sql .= " AND YEAR(date) like ".$yr;
          }
    
       // print_r($sql);
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1,$mnth);
        $stmt->execute();
        return $stmt->fetchAll();
    }   
    
     
}
