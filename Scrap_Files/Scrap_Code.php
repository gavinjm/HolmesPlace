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