<?php
/** Description of StatisticConsolidator
 * 
 *
 * @author HolmesPlace
 */

namespace App\Entity\Utility;

use App\Entity\Ticker;
use DateTime;

class StatisticConsolidator {
    
 /** curlTickers
  *  gET Ticker data from Luno API using CURL.
  */
    public function curlTickers(){
       // jSON URL which should be requested
 $json_url = "https://api.mybitx.com/api/1/tickers";

 // $username = "your_username";  // authentication
 // $password = "your_password";  // authentication
 // CURLOPT_USERPWD => $username . “:” . $password,  // authentication
 // CURLOPT_POSTFIELDS => $json_string

// jSON String for request
// 
// $json_string = "[your json string here]";

// Initializing curl
 $ch = curl_init( $json_url );

// Configuring curl options
 $options = array(
 CURLOPT_RETURNTRANSFER => true,
 CURLOPT_HTTPHEADER => array("Content-type: application/json") ,
  );

// Setting curl options
 curl_setopt_array( $ch, $options );

// Getting results
 $result = curl_exec($ch); // Getting jSON result string
 print_r($result);
return $result;

}

/** getETHZAR
     * extracts the Etherium/Rand pait,ETHZAR, price details, value from the array.
     * @returns a Ticker
     */
    public function getETHZAR($data){
     $ethzar = new Ticker();
        foreach ($data as $ticker) {
           foreach ($ticker as $currency){
             if ($currency['pair'] == "ETHZAR"){
                  $ethzar->setPair($currency['pair']);
                  $ethzar->setAsk($currency['ask']);
                  $ethzar->setBid($currency['bid']);
                  $ethzar->setLastTrade($currency['last_trade']);
                  $ethzar->setRolling24HourVolume($currency['rolling_24_hour_volume']);
                  $ethzar->setTimestamp($currency['timestamp']);              
                }
            }
        }
        return $ethzar;   
        
    }
    
/** getXBTZAR
 * extracts the Bitcoin,XBTZAR, price details, value from the array.
 * @returns a Ticker
 */
    public function getXBTZAR($data){
        $xbtzar = new Ticker();
        foreach ($data as $ticker) {
           foreach ($ticker as $currency){
             if ($currency['pair'] == "XBTZAR"){
                  $xbtzar->setPair($currency['pair']);
                  $xbtzar->setAsk($currency['ask']);
                  $xbtzar->setBid($currency['bid']);
                  $xbtzar->setLastTrade($currency['last_trade']);
                  $xbtzar->setRolling24HourVolume($currency['rolling_24_hour_volume']);
                  $xbtzar->setTimestamp($currency['timestamp']);              
                }
            }
        }
        return $xbtzar;
    }
 
 
/** processMileages
  * @parameter $data and associative array with the data from fuel statistics.
  * @returns an associative array with of the fuel statistics
  */   
    public function processMileages($cm,$pm){
    $pmcount = count($pm)-1; 
    
       $pmTotalFuel = $this->fuelStats($pm)['fuel'];
            $pmCost = $this->fuelStats($pm)['cost'];
       
       $cmTotalFuel = $this->fuelStats($cm)['fuel'];
            $cmCost = $this->fuelStats($cm)['cost'];
                   
        $endcount = count($cm);
        $cmDistance = $cm[$endcount-1]['odometer'] - $cm[0]['odometer'];     
        if ($cmDistance==0) $cmDistance = 1;
        // Add everything to an associative array to send to page.
        //$fuelStats = Array(); // An empty array.
        $fuelStats= array(
             "pmCost" => $pmCost,
             "pmFuel" => $pmTotalFuel,
             "pmDistance" => ($pm[$pmcount]['odometer'] - $pm[0]['odometer']),
             "pmEconomy" => round(($pmTotalFuel / ($pm[$pmcount]['odometer'] - $pm[0]['odometer']) * 100),2),
             "cmCost" => $cmCost,
             "cmFuel" => $cmTotalFuel,
             "cmDistance" => $cmDistance,
             "cmEconomy" => round(($cmTotalFuel / $cmDistance * 100),2),
            ); 
    return $fuelStats;
}

/**
  * processCryptos
  *  @parameters $cc an associative array with the data from table crypto_currency.
  *  @parameters $cp an associative array with the last three crypto prices.
  *  @returns an associative array with of the crypto-currency statistics formatted for the summary page. 
  */   
    public function processCryptos($cc,$cp){
    // Calculate the growth as a %age between pvs and curr price
    // This is fairly inaccurate as some days multiple entries are captured.
    // The formula used is: (currPrice - pvsPrice) / pvsPrice * 100
        $bgrowth = round(($cp[0]['btc_price']-$cp[2]['btc_price']) / $cp[2]['btc_price']*100,2);
        $egrowth = round(($cp[0]['eth_price']-$cp[2]['eth_price']) / $cp[2]['eth_price']*100,2);
    
   // Calculate the value of Cryptos
        $btc_value= round( $cp[0]['btc_price'] * $cc[0]['balance'],2);
        $eth_value= round($cp[0]['eth_price'] * $cc[1]['balance'],2);
   // Calculate the percentage growth in rands value
        $rgrowth = round((($btc_value+$eth_value)/-6500 *100),2);
      
      $cryptoStats =  array(
          'bgrowth'=>$bgrowth,
          'egrowth'=>$egrowth,
          'ccCrypto'=>$cc,
          'btc_value' => $btc_value,
          'eth_value' => $eth_value,
          'rgrowth' => $rgrowth,
          );
    return $cryptoStats;
}

/** fuelStats
 * @parameters $fuel_array
 * @returns: the sum of the fuel entries.
 * 
 */
    public function fuelStats($fuelArray){
    $pmcount = count($fuelArray);
    $pmTotalFuel=0;
    $pmCost=0;
    for ($i=0;$i<$pmcount; $i++){
            $pmTotalFuel = $pmTotalFuel + $fuelArray[$i]['liters'];
            $pmCost = $pmCost + $fuelArray[$i]['amount'];       
        }
   return array('fuel' => $pmTotalFuel, 'cost' => $pmCost);     
}

/** myEpochConverter
 * function to test converting from UTC Epoch to current date/time
 */
    public function myEpochConverter(){
    
   // $epoch = 1483228800;
    $epoch = 1562767263822;
    $dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
    echo $dt->format('Y-m-d H:i:s'); // output = 2017-01-01 00:00:00
    
    date_default_timezone_set('Africa/Johannesburg');
          $ts = 1562767263822; //
          //Adjust for Local Time zone -7200000
          $ts += 7200000;
          // $gmtTimezone = new DateTimeZone('Africa/Johannesburg');
          $datetime = new DateTime("@$ts");
           
           echo "<br><br>Timestamp Conversion:".$datetime->format('d-m-Y H:i:s');
}

/** Temp time stuff
          // echo "<br>Using date:".date("Y-m-d h:i:s", $ts);
          // $tzFrom ="UTC";
          // $tzTo = "Africa/Johannesburg";
           
          //  $dte = new DateTime("@$ts",new \DateTimeZone($tzFrom));
          //  $dte->setTimezone(new \DateTimeZone($tzTo));
          //  $dte->getTimezone();
          //  print_r($dte->format('d-m-Y H:i:s'));
          // $dte->setTimezone('Africa/Harare');
            
          // $dte->format('Y-m-d H:i:s');
          // $cryptoPrices->setDate($dte);
 * 
 */
}

