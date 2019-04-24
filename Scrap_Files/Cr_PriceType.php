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