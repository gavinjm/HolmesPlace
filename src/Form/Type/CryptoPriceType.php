<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CryptoPriceType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options)
   {
       //$builder->add('id');
       $builder->add('date', DateType::class, array('widget' => 'single_text'));
       $builder->add('btc_price');
       $builder->add('eth_price');
       $builder->add('Save',SubmitType::class);
   }
   
   public function getName()
   {
       return 'CryptoPrices';
   }
   
   public function getDefaultOptions(array $options)
   {
       return array('data_class' => 'App\Entity\CryptoPrices');
   }
}
