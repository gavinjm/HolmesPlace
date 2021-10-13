<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Utility\StatisticConsolidator;

//use Symfony\Component\Routing\Annotation\Route;

class HolmesPlaceController extends AbstractController
{
    /* Global class private variables */
    private $SC; //= new StatisticConsolidator();
       
    public function admin()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }
    
    public function Index(Request $request){
        // Lets get the information from the GLobal $_Server[]
        $content =  $request->getContent();
        $cp = $this->runSQLQuery("select date,btc_price,eth_price from crypto_prices order by id desc limit 1");
        $ip = $request->getClientIp();
        $location = $this->getLocation();
        $weatherData = $this->getWeatherData();
        $graphData = $this->convertToString($weatherData);
         
        return $this->render('holmes_place/homepage.html.twig', [
            'content' => $content,
            'weatherdata' => $weatherData[count($weatherData)-1],
            'location'=>$location,
            'graphstring'=>$graphData,
            'cryptoPrices'=>$cp,
        ]);
    }   
 
    private function convertToString($weatherArray){
        $arrString = "";
        foreach ($weatherArray as $row=>$tick){
            $arrString = $arrString.$weatherArray[$row]['pressure_mb']."_";
        }
        return $arrString;
    }
    
   public function BeeApp(Request $request){
       $sql = "select diary.date,location.location,diary.activity, diary.entry_details from beehives "
               . "right join diary "
               . "on beehives.id = diary.hive "
               . "right join location on beehives.location=location.id order by diary.date desc";
       $sqlHoneyWax = "select sum(honey_weight) as honey, sum(wax_weight) as wax from hive_outputs";
       $sqlHives = "select location.location,beehives.id as 'hive', beehives.date_populated as 'Date', beehives.hive_type from"
                . " beehives "
                . "right join location on beehives.location=location.id order by beehives.id";
         $hives = $this->runSQLQuery($sqlHives);
         $hive_output = $this->runSQLQuery($sqlHoneyWax);
         $content = $this->runSQLQuery($sql);
        return $this->render('Bees/bee_index.html.twig', [
            'diary' => $content,
            'hives' => $hives,
            'hive_output' => $hive_output,
           ]);
    }
    
    private function runSQLQuery(string $qry){
        $stmnt = $this->getDoctrine()->getConnection()->prepare($qry);
        $stmnt->execute();
        $result =  $stmnt->fetchAll();
      return $result;
        
    }  
 
    public function GetPreviousMonth($mn){
       if($mn==1){ return 12;} else { return ($mn-1);}  
    }
    
    public function SummaryPage(){
         $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
         $this->SC = new StatisticConsolidator();
         $mn = date("m");                                   // Get the current month "m" = 05, 06 etc.
         $yr = date("Y");
               
         $cm = $this->GetFuelEntriesForMonth($mn,$yr);       // $cm = array of fuel entries for Current Month
         
     
        $cp = $this->getCryptoLatest();       // selects from crypto_prices the latest crypto currency prices
        $cc = $this->getCurrencies();         // Selects crypto_currency ->> the crypto names/balances.
                
        //$FuelStats = $this->SC->processMileages($cm,$pm,$ppm);
        $CrytoStats = $this->SC->processCryptos($cc,$cp);
               
          return $this->render('private/summary.html.twig', [
            'controller_name' => 'Holmes Place Website',
            'current_date' => date("F j, Y, g:i a"),
            'month' => $mn,
            'year' => $yr,
            'fuel_entries' => $cm,
            'crypto_prices' => $cp,
            'cryptoStats' => $cc,
        ]);
    }
    
    public function Weather(Request $request){
        $data = $this->getWeatherData();
       
        return $this->render('Weather/weather_index.html.twig', [
            'data' => $data,
           ]);
    }
            
    private function getWeatherDataWeb(){
        
        $opts = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n")); 
    //Basically adding headers to the request
        $context = stream_context_create($opts);    
        $jsonurl = "http://api.weatherapi.com/v1/current.json?key=f5286cbe189940599ea115722211407&q=-30.71802,30.464566&aqi=no";
        $json = file_get_contents($jsonurl,false,$context);
        // the true param to json_decode ensures an associative array is returned.
        return (json_decode($json,true));
    }
    
    private function getLocation(){
        $sql = "select * from location";
        $stmnt = $this->getDoctrine()->getConnection()->prepare($sql);
        $stmnt->execute();
        $res = $stmnt->fetchAll();
        //print_r($res);
        return $res[0];
    }
    
    private function getWeatherData(){
       
       $sql = "select * from weatherdata ORDER BY last_updated ASC";
       $stmnt = $this->getDoctrine()->getConnection()->prepare($sql);
       $stmnt->execute();
       $res = $stmnt->fetchAll();
       return $res;
    }
    
    public function UpdateWeather(){
        $data = $this->getWeatherDataWeb();
        $this->saveWeatherData($data);
       return $this->redirectToRoute('index');
    }
    
    private function saveWeatherData($data){
        
     $sqlGetLastUpdated = "select last_updated from weatherdata ORDER BY last_updated DESC LIMIT 1";
     $sqlInsert = " INSERT INTO weatherdata values (0,"
                   ."CAST(".$data['current']['pressure_mb']." AS DECIMAL(6,2)),"
                   ."'".$data['current']['last_updated']."',"
                   .$data['current']['temp_c'].","
                   .$data['current']['precip_mm'].","
                   .$data['current']['uv'].","
                   ."'".$data['current']['wind_dir']."')";
     $stmnt = $this->getDoctrine()->getConnection()->prepare($sqlGetLastUpdated);
     $stmnt->execute();
     $lastUpdatedDbase = $stmnt->fetchAll();
     
     $lastUpdatedCurrent = $data['current']['last_updated'];
      
        if ($lastUpdatedCurrent > $lastUpdatedDbase[0]['last_updated'] ){
            $update=$this->getDoctrine()->getConnection()->prepare($sqlInsert);
            $update->execute();         
        }
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
    public function getFuelEntriesForMonth($mnth,$yr)
    {   
        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT * FROM fuel_log where MONTH(date) like ".$mnth;
        $sql .= " AND YEAR(date) like ".$yr;
    
         // print_r($sql);
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1,$mnth);
        $stmt->execute();
        return $stmt->fetchAll();
    }   
              
}
