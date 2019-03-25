<?php
namespace App\Form\Type;

use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\IntType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DoubleType;
use Symfony\Component\Form\Extension\Core\Type\BooleanType;

class FuelType extends AbstractType
{
   public function buildForm(FormBuilder $builder, array $options)
   {
       $builder->add('id','IntType');
       $builder->add('date', null, array('widget' => 'single_text'));
       $builder->add('odometer','IntType');
       $builder->add('liters', 'DoubleType');
       $builder->add('Amount','DoubleType');
       $builder->add('location', 'TextType');
       $builder->add('tankfull', 'BooleanType');
   }
   
   public function getName()
   {
       return 'FuelLog';
   }
   
   public function getDefaultOptions(array $options)
   {
       return array('data_class' => 'App\Entity\FuelLog');
   }

}
