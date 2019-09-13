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
     * $data = array containing prices of 5 currency pairs. 
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
    public function processMileages($cm,$pm,$ppm){
    $pmcount = count($pm)-1;
    $ppmcount = count($ppm)-1;
    
       $ppmFuel = $this->fuelStats($ppm)['fuel'];
       $ppmCost = $this->fuelStats($ppm)['cost'];
       
       $pmTotalFuel = $this->fuelStats($pm)['fuel'];
            $pmCost = $this->fuelStats($pm)['cost'];
       
       $cmTotalFuel = $this->fuelStats($cm)['fuel'];
            $cmCost = $this->fuelStats($cm)['cost'];
            
        // Take into account no readings -0 or 1 - first reading of the month
        if ( count($cm)< 2 ){    
           $cmDistance = 1;  // No entries just set to 1 to avoid divide by 0
        } else {
            $endcount = count($cm);
            $cmDistance = $cm[$endcount-1]['odometer'] - $cm[0]['odometer'];
        }
        // Add everything to an associative array to send to page.
      $fuelStats= array(
             "ppmCost" => $ppmCost,
             "ppmFuel" => $ppmFuel,
             "ppmDistance" => ($ppm[$ppmcount]['odometer'] - $ppm[0]['odometer']),
             "ppmEconomy" => round(($ppmFuel / ($ppm[$ppmcount]['odometer'] - $ppm[0]['odometer']) * 100),2), 
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

/** convertToDate
 * converts and UTC Epoch timestamp into Date/Time object.
 * 
 */
    public function convertToDate($UTC_Time){
    //$epoch time is represented in Milliseconds from Luno API
    //convert to seconds /1000 and discarding the modulo
        $epoch = round($UTC_Time / 1000);
        $dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
        return $dt->format('d-m-YY H:i:s');
}

/** myEpochConverter
 * function to test converting from UTC Epoch to current date/time
 */
    public function myEpochConverter($epoch){
  /*  
   if (ini_get('date.timezone')) {
      echo '<br><br>PHP.ini date.timezone: ' . ini_get('date.timezone');
    }
    if (date_default_timezone_get()) {
     echo "<br>Current Time Zone: " . date_default_timezone_get() . '<br />';
    }
    */
    //$epoch time is represented in Milliseconds from Luno API
    //convert to seconds /1000 and discarding the modulo
    $epoch = round($epoch / 1000);
    $dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
   // $dt->setTimestamp($epoch);
    echo "<br><br>Not adjusted for TimeZone: $epoch";
    echo "<br>Using DateTime { $epoch } : ".$dt->format('Y-m-d H:i:s'); // output = 2017-01-01 00:00:00
   
    
        $ts = $epoch+7200; //Adjust for Local Time zone -7200000
        date_default_timezone_set('Africa/Windhoek');
        if (date_default_timezone_get()) {
        echo "<br><br>Time Zone Adjusted to : " . date_default_timezone_get();
        }
          $datetime = new DateTime("@$ts");
           echo "<br>Timestamp adjusted for TimeZone +7200000: $ts";
           echo "<br>Timestamp Conversion { $ts } :".$datetime->format('Y-m-d H:i:s');
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

