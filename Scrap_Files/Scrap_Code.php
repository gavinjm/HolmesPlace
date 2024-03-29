/**
echo "<br>ID: ".$item['id']."  Date: ".$item['timestamp']  
                   ."  Description: ".$item['description']." Bal_Delta:".$item['balance_delta']
                   ."  Avail_Bal_Delta:".$item['available_bal_delta']."  Balance: ".$item['balance']
                   ." : ".$balanceCount;
*/


/*
//$balArray = Array($item['balance'],$item['balance_delta'], $item['available_bal_delta']);
          // $descriptionCount = $this->findByDescription($item['description']);
          // $dateCount = $this->findByDate($item['timestamp']);
          // $balanceCount = $this->findByBalances($balArray);

*/


/**
 foreach ($data as $ticker) {
           foreach ($ticker as $currency){
             if ($currency['pair'] == "XBTZAR"){
                  $xbtzar->setPair($currency['pair']);
                  $xbtzar->setAsk($currency['ask']);
                  $xbtzar->setBid($currency['bid']);
                  $xbtzar->setLastTrade($currency['last_trade']);
                  $xbtzar->setRolling24HourVolume($currency['rolling_24_hour_volume']);
                  $ts = $currency['timestamp'];
                  $xbtzar->setTimestamp($ts);              
                }
            }
        }

*/
<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class CR_PriceType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options)
   {
       //$builder->add('id');
       $builder->add('date', DateType::class, array('widget' => 'single_text'));
       $builder->add('currency');
       $builder->add('price');
       $builder->add('Save',SubmitType::class);
   }
   
   public function getName()
   {
       return 'CrypoPrice';
   }
   
   public function getDefaultOptions(array $options)
   {
       return array('data_class' => 'App\Entity\CryptoPrices');
   }

}
/*
 * *
 <h1> {{ action }}! ✅</h1>
      
 
   
    <ul>
        <li>Your controller at <code><a href="{{ '/var/www/html/HolmesPlace/src/Controller/HolmesPlaceController.php'|file_link(0) }}">src/Controller/HolmesPlaceController.php</a></code></li>
        <li>Your template at <code><a href="{{ '/var/www/html/HolmesPlace/templates/holmes_place/index.html.twig'|file_link(0) }}">templates/holmes_place/index.html.twig</a></code></li>
    </ul>
*
 */


/*//////////////////// Table from index page
<table border="1" padding="2">
      <tr><th>Date</th><th>Bitcoin</th><th>Etherium</th></tr>
      {% for cryptoprices in crypto_entries %}
      <tr>
          <td>{{ cryptoprices.date.format('Y-m-d') }}</td>
          <td>{{ cryptoprices.btcprice }}</td>
          <td>{{ cryptoprices.ethprice }}</td>
      </tr>
  
  
  {% endfor %}
  </table>
 *
 * 
 *  
 */

/*
 * 
 * <table>
        <tr><th>Fuel Statistics</th><th>Previous</th><th>Current</th><th>Crypto-Currencies</th><th></th><tr>
        <tr>
            <td>Fuel Used</td>
            <td style="text-align:right" >{{ fuelStats[0]['pmFuel'] }}</td><td></td>
            <td>Bitcoin</td>
            <td style="text-align:right">{{  crypto_prices[0].btc_price }}</td>
        </tr>
        <tr>
            <td>Fuel Cost</td>
            <td style="text-align:right">{{ fuelStats[0]['pmEconomy'] }}</td>
            <td></td>
            <td>Etherium</td>
            <td style="text-align:right">{{ crypto_prices[0].eth_price }}</td> 
        </tr>
        <tr>
            <td>Distance Travelled</td><td style="text-align:right">{{ total_dist }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
          <tr>
            <td>Fuel Efficiency</td><td style="text-align: right">{{ fuel_efficiency }} </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
   
 * 
 * 
 * 
 * 
 * 
 * 
 */
/*
 * 
 * {% for tick in tickers %}
              <li>Pair: {{ tick.pair }}</li>
              <li>Last Trade: {{  tick.last_trade }}</li>
          {% endfor %}

 * <tr><th>Ask</th><th>Bid</th><th>Last Trade</th><th>Volume</th><th>Pair</th><th>Price</th></tr>
          {% for ticker in crypto_values %}
 * <td> {{ ticker.ask }} </td>
                <td> {{ ticker.bid }} </td>
                <td> {{ ticker.last_trade }} </td>
                <td> {{ ticker.rolling_24_hour_volume }} </td>
                <td> {{ ticker.pair }} </td>
                <td> {{ ticker.timestamp }}</td>
 * 
 *  */
/*
 * foreach ($data as $ticker) {
    
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
 * 
 * 
 * 
 * 
 */

/*
 * <table border="1" padding="2">
       <tr><th>Date</th><th>Start Odo</th><th>End Odo</th><th>Description</th><th>Business</th><th>Distance</th></tr>
  {% for trip in trip_entries %}
          <tr>
              <td> {{ trip.date.format('Y-m-d') }} </td>
              <td> {{ trip.startOdo }} </td>
              <td> {{ trip.endOdo }} </td>
              <td> {{ trip.description }} </td>
              {% if trip.tripType == true %}
              <td> Business </td>
              {% elseif trip.tripType == false %}
                  <td> Private </td>
               {% endif %}
              <td> {{ trip.endOdo - trip.StartOdo }} </td>
          </tr>    
  {% endfor %}
  <table>
 */
/*
 * {% for ticker in data %} 
      {% for tick in ticker %}  
        <tr>
        <td> {{ tick['pair'] }} </td>
        <td> {{ tick['ask'] }} </td>
        <td> {{ tick['bid'] }} </td>
        <td> {{ tick['last_trade'] }} </td>
        <td> {{ tick['rolling_24_hour_volume'] }} </td>
        </tr>
       {% endfor %}
    {% endfor %}
 */


/*
  
     * getFuelStatistics
     * Returns last totals for fuel and amount for a specific month.

    public function GetFuelStatistics($mnth){
       
    $em = $this->getDoctrine()->getManager();
    // $month = "'2019-".$mnth."%'";
    
    $sql = "SELECT ROUND(SUM(liters),2) as Fuel, ROUND(SUM(amount),2)as Cost FROM fuel_log where MONTH(date) like ".$mnth;
    //$sql .= " AND YEAR(date) like '2020'";
    $stmt = $em->getConnection()->prepare($sql);
    $stmt->bindValue(1,$mnth);
    $stmt->execute();
    return $stmt->fetchAll();
    }
 */

/**
     * GetTripEntries
     * Returns all trip entries from table trips
     * 
  
    public function GetTripEntries(){
       
        $repository = $this->getDoctrine()->getRepository(Trip::class);
        $tripEntry = $repository->findAll();
        return $tripEntry;
    }
 * 
 *    
 */

/*
 * 
 *           <table>
              <th>Year</th><th>Jan</th><th>Feb</th><th>Mar</th><th>Apr</th><th>May</th><th>Jun</th><th>Jul</th>
              <th>Aug</th><th>Sep</th><th>Oct</th><th>Nov</th><th>Dec</th>
              <tr>
              <td>2019</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
          <tr><td>2020</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
          </table>
 * 
 * 
 * 
 * <form name="petrol_stats" action="/vehicle" method="post">
              <table>
                  <tr><th>Year</th><th>Month</th><th></th>
                  </tr>
                  <tr>
                      <td>
              <select name="year" id="year">
                  <option value="2018">2018</option>
                  <option value="2019">2019</option>
                  <option value="2020">2020</option>
              </select>
                      </td>
                      <td>
              <select name="month" id="month">
                  <option value="0">All</option>
                  <option value="1">Jan</option>
                  <option value="2">Feb</option>
                  <option value="3">Mar</option>
                  <option value="4">Apr</option>
                  <option value="5">May</option>
                  <option value="6">Jun</option>
                  <option value="7">July</option>
                  <option value="8">Aug</option>
                  <option value="9">Sep</option>
                  <option value="10">Oct</option>
                  <option value="11">Nov</option>
                  <option value="12">Dec</option>
              </input>
              </td>
              <td><input type="submit"/></td>
                </tr>
              </table>      
          </form>
 * 
 */
/*
 * <div class="inlineTable">
            <table>
                <tr><th>Fuel Statistics</th><th>{{ month-2 }}</th><th>{{ month-1 }}</th><th>{{ month }}</th></tr>
                <tr>
                    <td>Fuel Used</td>
                    <td style="text-align:right" >{{ fuelStats['ppmFuel'] }}</td>
                    <td style="text-align:right" >{{ fuelStats['pmFuel'] }}</td>
                    <td style="text-align:right" >{{ fuelStats['cmFuel'] }}</td></tr>           
                <tr><td>Fuel Cost</td>
            <td style="text-align:right">{{ fuelStats['ppmCost'] }}</td>       
            <td style="text-align:right">{{ fuelStats['pmCost'] }}</td>
            <td style="text-align:right">{{ fuelStats['cmCost'] }}</td></tr>
                <tr><td>Distance Travelled</td>
            <td style="text-align:right">{{ fuelStats['ppmDistance'] }}</td>        
            <td style="text-align:right">{{ fuelStats['pmDistance'] }}</td>
            <td style="text-align:right">{{ fuelStats['cmDistance'] }}</td></tr>
                <tr><td>Fuel Efficiency</td>
            <td style="text-align: right">{{ fuelStats['ppmEconomy'] }} </td>        
            <td style="text-align: right">{{ fuelStats['pmEconomy'] }} </td>
            <td style="text-align: right">{{ fuelStats['cmEconomy'] }} </td></tr>
                
            </table>  
 */