<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\FuelType;
use App\Form\Type\TripType;
use App\Form\Type\CryptoPriceType;
use App\Entity\FuelLog;
use App\Entity\Trip;
use App\Entity\CryptoPrices;
use Luno\Types\Ticker;


//use Symfony\Component\Routing\Annotation\Route;

class HolmesPlaceController extends AbstractController
{
   
    public function index()
    {
        $te = $this->GetTripEntries();
        $fe = $this->GetFuelEntries();
        $cp = $this->getCryptoLatest();
       
            
     
        return $this->render('holmes_place/index.html.twig', [
            'controller_name' => 'Holmes Place Website',
            'current_date' => date("F j, Y, g:i a"),
            'trip_entries' => $te,
            'fuel_entries' => $fe,
            'crypto_prices' => $cp,
        ]);
    }
    
    /*getTickers
     * Get latest Ticker values
     * the true value in the json_decode ensures an array is returned from json_decode
     */
    public function getTickers(){
        $jsonurl = "https://api.mybitx.com/api/1/tickers";
        $json = file_get_contents($jsonurl);
        return (json_decode($json));
    }
    
    /*updateCryptPrices
     * Updates the database with the latest Luno prices
     * 
     * 
     */
    public function updateCryptoPrices(){
        $data = $this->getTickers();
        $xbtzar = new Ticker();
      //  echo "<table border='2'>";
        // var_dump($data);
        foreach ($data as $ticker) {
    
            foreach ($ticker as $currency){
                if ($currency->pair == "XBTZAR"){
                  $xbtzar->setAsk($currency->ask);
                  $xbtzar->setBid($currency->bid);
                  $xbtzar->setLastTrade($currency->last_trade);
                  $xbtzar->setPair($currency->pair);
                  $xbtzar->setRolling24HourVolume($currency->rolling_24_hour_volume);
                  $xbtzar->setTimestamp($currency->timestamp);
                }
            }
        }
                // echo "<tr>";  
                // echo "<td>".$currency->ask."</td>";
                // echo "<td>".$currency->bid."</td>";
                // echo "<td>".$currency->last_trade."</td>";
                // echo "<td>".$currency->pair."</td>";
                // echo "<td>".$currency->rolling_24_hour_volume."</td>";
                // echo "<td>".$currency->timestamp."</td>";
                // echo "</tr>";
           
            
       // }
       //  echo "<tr><td>".$xbtzar->getAsk()."</td><td>".$xbtzar->getPair()."</td></tr>";
       //  echo "</table>";
         
        return $this->render('holmes_place/showcrypto.html.twig',[
            'xbtzar' => $xbtzar,
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
    
    /** getCryptoLatest
     *  Returns: latest entry in the crypto_prices table
    */
    public function getCryptoLatest(){
        //Query
        $sql = "select * from crypto_prices order by date desc limit 1";
        //set parameters 
        //you may set as many parameters as you have on your query
        //prices['color'] = blue; 
        //create the prepared statement, by getting the doctrine connection
        $stmnt = $this->getDoctrine()->getConnection()->prepare($sql);
        $stmnt->execute();
        return $stmnt->fetchAll();
    }
    
    /** getCryptoPrices
     *  Returns latest crypto currency prices
     */
    public function getCryptoPrices(){
        $repository = $this->getDoctrine()->getRepository(CryptoPrices::class);
        $cryptoPrices = $repository->findAll();
        return $cryptoPrices;
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
    public function CryptoEntry(Request $request){
       $price = new CryptoPrices(); 
       $form = $this->createForm(CryptoPriceType::class,$price);
       $form->handleRequest($request);
       
       if ($form->isSubmitted() && $form->isValid()) {
        // $form->getData() holds the submitted values
        // but, the original `$task` variable has also been updated
        $cr_price = $form->getData();
        
        // Save the entry to the database.
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($cr_price);
         $entityManager->flush();

        return $this->redirectToRoute('task_success');
       }
       
       
       return $this->render('holmes_place/crypto_entry.html.twig', [
            'action' => 'New Crypto Entry',
            'current_date' => date("F j, Y, g:i a"),
             'form' => $form->createView(),
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
