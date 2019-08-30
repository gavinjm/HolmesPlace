<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use App\Form\Type\FuelType;
use App\Form\Type\TripType;
use App\Form\Type\CryptoPriceType;
use App\Entity\FuelLog;
use App\Entity\Trip;
use App\Entity\CryptoPrices;
use App\Entity\Ticker;
use App\Entity\Utility\StatisticConsolidator;
use App\Entity\Utility\ProcessCSV;



//use Symfony\Component\Routing\Annotation\Route;

class HolmesPlaceController extends AbstractController
{
    /* Global class private variables */
    private $SC; //= new StatisticConsolidator();
    
    public function Index(){
        // Lets get the information from the GLobal $_Server[]
        $request = Request::createFromGlobals();
        $content =  $request->getContent();
         // Initialise the StatistisConsolidator.
        print_r($content);
        
        
        return $this->render('holmes_place/homepage.html.twig', [
            'action' => 'Index Page',
        ]);
    }   
    
    public function SummaryPage()
    {
        $this->SC = new StatisticConsolidator();
         $mn = date("m");    // Get the current month "m" = 05 06 etc.
         $cm = $this->GetFuelEntriesForMonth($mn);       //Current Month
        $pm = $this->getFuelEntriesForMonth($mn-1);     // Previous Month
        // print_r($pm);
        $cp = $this->getCryptoLatest();                 // selects from crypto_prices the latest crypto currency prices
        $cc = $this->getCurrencies();                   // Selects crypto_currency ->> the crypto names/balances.
        $FuelStats = $this->SC->processMileages($cm,$pm);
        $CrytoStats = $this->SC->processCryptos($cc,$cp);
               
     
        return $this->render('holmes_place/summary.html.twig', [
            'controller_name' => 'Holmes Place Website',
            'current_date' => date("F j, Y, g:i a"),
            'month' => $mn,
            'fuel_entries' => $cm,
            'crypto_prices' => $cp,
            'fuelStats' => $FuelStats,
            'cryptoStats' => $CrytoStats,
        ]);
    }
    
    
    
    /** getTickers
     ** Get latest Ticker values
     * the true value in the json_decode ensures an array is returned from json_decode
     */
    public function getTickers(){
        $opts = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n")); 
    //Basically adding headers to the request
        $context = stream_context_create($opts);
        //$html = file_get_contents($url,false,$context);
       // $html = htmlspecialchars($html);
        
        $jsonurl = "https://api.mybitx.com/api/1/tickers";
        $json = file_get_contents($jsonurl,false,$context);
        // the true param to json_decode ensures an associative array is returned.
        return (json_decode($json,true));
         //return (json_decode($json,true));
    }
    
    /** showTickerData
     ** displays the contents of the ticker table.
     * 
     */
    public function showTickerData(){
       /* $repository = $this->getDoctrine()->getRepository(Ticker::class);
        $tickers = $repository->findBy(
                array(),
                array('id' => 'DESC')
            );
        
        */
        $tickers= $this->getTickerPrices();
        return $this->render('holmes_place/showTickerTable.html.twig',[
            'data' => $tickers,
         
           ]); 
    
        
        
    }
    
    
    
    /** 
     * updateCryptoPrices
     * Updates the database with the latest Luno prices
     * calls getTickers() to return the latest data from Luno api site.
     * TH200196
     */
    public function updateCryptoPrices(){
        $this->SC = new StatisticConsolidator();
        $data = $this->getTickers();
       // $data = $this->curlTickers();
        
       // Get the Bitcoin XBTZAR price from the data array as a Ticker 
         $xbtzar = $this->SC->getXBTZAR($data);
         $ethzar = $this->SC->getETHZAR($data);
         
         $cryptoPrices = new CryptoPrices();
           
           $cryptoPrices->setBtcPrice($xbtzar->getLastTrade());
           $cryptoPrices->setEthPrice($ethzar->getLastTrade());
           $cryptoPrices->setDate(new DateTime());
           
                   
      // Save the Ticker XBTZAR to the database.
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($xbtzar);
         $entityManager->persist($ethzar);
         $entityManager->persist($cryptoPrices);
         $entityManager->flush(); 
        return $this->render('holmes_place/showcrypto.html.twig',[
            'data' => $data,
            'dte' => date('D, d M Y H:i:s',(int)$xbtzar->getTimestamp()),
        ]); 
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
         
         return $this->redirectToRoute('task_success', array('slug'=> 'Crypto'));
       }
       
       
       return $this->render('holmes_place/crypto_entry.html.twig', [
            'action' => 'New Crypto Entry',
            'current_date' => date("F j, Y, g:i a"),
             'form' => $form->createView(),
        ]);
    }
    
    
    /** getCurrencies
     * Returns: the wallet names and balances
     */
    public function getCurrencies(){
        $sql = "select name, balance from crypto_currency";
        //create the prepared statement, by getting the doctrine connection
        $stmnt = $this->getDoctrine()->getConnection()->prepare($sql);
        $stmnt->execute();
        return $stmnt->fetchAll();   
    }
    
    /** getCryptoLatest
     *  Returns: latest entry in the crypto_prices table
    */
    public function getCryptoLatest(){
        //Query
        $sql = "select date,btc_price,eth_price from crypto_prices order by id desc limit 3";
        //create the prepared statement, by getting the doctrine connection
        $stmnt = $this->getDoctrine()->getConnection()->prepare($sql);
        $stmnt->execute();
        return $stmnt->fetchAll();
    }
    
    /** 
     * Old_getTickerPrices
     *  Returns latest ticker prices
     *  Working but not flexible.
     */
    public function Old_getTickerPrices(){
        $repository = $this->getDoctrine()->getRepository(Ticker::class);
        $cryptoPrices = $repository->findAll();
        return $cryptoPrices;
    }
    
     /** 
     * getTickerPrices
     *  Returns latest ticker prices
     */
    public function getTickerPrices(){
     $sql="select 
           timestamp,pair,ask,bid,last_trade,rolling_24_hour_volume
           from 
           ticker where pair='XBTZAR'
           ORDER BY timestamp DESC";
      $stmnt = $this->getDoctrine()->getConnection()->prepare($sql);
        $stmnt->execute();
        //@tag print_r($sql);
        return $stmnt->fetchAll();
        
        
    }
    /** 
     * getFuelStatistics
     * Returns last totals for fuel and amount for a specific month.
     */
    public function GetFuelStatistics($mnth){
       
    $em = $this->getDoctrine()->getManager();
    // $month = "'2019-".$mnth."%'";
    $sql = "SELECT ROUND(SUM(liters),2) as Fuel, ROUND(SUM(amount),2)as Cost FROM fuel_log where MONTH(date) like ".$mnth; 
    $stmt = $em->getConnection()->prepare($sql);
    $stmt->bindValue(1,$mnth);
    $stmt->execute();
    return $stmt->fetchAll();
    }
    
     /**
     * GetTripEntries
     * Returns all trip entries from table trips
     * 
     */
    public function GetTripEntries(){
       
        $repository = $this->getDoctrine()->getRepository(Trip::class);
        $tripEntry = $repository->findAll();
        return $tripEntry;
    }
    
    /** 
     * getMonthlyFuelEntries
     * Returns all fuel entries for month $mnth
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
    public function getFuelEntriesForMonth($mnth)
    {   
        $em = $this->getDoctrine()->getManager();
    // $month = "'2019-".$mnth."%'";
        $sql = "SELECT * FROM fuel_log where MONTH(date) like ".$mnth;
       // print_r($sql);
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1,$mnth);
        $stmt->execute();
        return $stmt->fetchAll();
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
    
    /** Process each Transaction to extract the ACTION,CURRENCY and AMOUNT from the description field. 
     * @param type associative array $entry
     * @return string
     */
    public function ExtractDetail($entry){
        // $transaction['description']
        $str = explode(' ', $entry['description']);
       return $str;
    }
    
    /** CombineArrays
     * 
     */
    public function CombineArrays($result,$parsed){
        $final=array();
        $sz = (count($result) == count($parsed)?count($result):0);
        if ($sz!=0){ // Arrays must be same sizes

            for ($i=0;$i<$sz; $i++){ // Loop through the array
                // Each element is an array and we need to add additional entries
              $final[$i] =0; 
            }
            
        }
    }
    /** DisplayTransactions
     * 
     * @param Request $request
     */
    public function DisplayTransactions(Request $request){
          $em = $this->getDoctrine()->getManager();
          $sql = "select timestamp,description,currency, balance, available_balance from transactions ORDER BY timestamp asc";
          $stmt = $em->getConnection()->prepare($sql);
          $stmt->execute();
          $result = $stmt->fetchAll();
         $i=0;
          foreach($result as $entry){
             $parsed[$i++] = $this->ExtractDetail($entry);
          }
          // Now loop through the result array again and add the individuall entrys to the end of each line.
          return $this->render('holmes_place/btcTransactions.html.twig',
             ['combined'=>$result,'parsed'=>$parsed]);        
    }
    public function ReadInFiles(Request $request){
        
         $csv = new ProcessCSV();
         $btcTransactions = $csv->readIn("Bitcoin");
         $ethTransactions = $csv->readIn("Etherium");
              
     return 0;
        
    }
   
    /**Saves the transactions to the transaction database.
    *
    * 
    */
    public function SaveTransactions($t){
        
        for ($x=0; $x < count($t); $x++){
          echo "<br>".$x. "--".$t[$x];  
        }
        $cc_tr_id = ($t[9]==''? 'none':$t[9]);
        $cc_add = ($t[10]==0? 0:$t[10]);
        $val = ($t[11]==''? 'none':$t[11]);
       
        // Convert the date/time in $t[2] to timestamp.
        // $timestamp = date("Y-m-d H:i:s");
         
         $em = $this->getDoctrine()->getManager();
         //print_r($t); 
         $sql = "INSERT INTO `transactions` ";
        $sql .= "(`wallet_id`,`timestamp`,`description`,`currency`,";
        $sql .= "`balance_delta`,`available_bal_delta`,`balance`,`available_balance` ";
        $sql .= ",`cc_transaction_id`,`cc_address`,`value`)";
        $sql .= " VALUES ('$t[0]', UNIX_TIMESTAMP('$t[2]'),'$t[3]','$t[4]',$t[5],$t[6],$t[7],$t[8],'$cc_tr_id',$cc_add,'$val')";
               
        echo "<br>SQL = ". $sql;
      $stmt = $em->getConnection()->prepare($sql);
       // $stmt->bindValue(1,$mnth);
       $stmt->execute();
    }
    /** 
     * Success page for all database entries
     * @param string $action 
     */
    public function task_success($action="default"){
        $act = $action;  
      
     return $this->render('holmes_place/task_success.html.twig',['action' => $act,]); 
      
    }
}
