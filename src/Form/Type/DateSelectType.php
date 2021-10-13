<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class FuelType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options)
   {
       //$builder->add('id');
       $builder->add('date', DateType::class, array('widget' => 'single_text'));
       $builder->add('odometer',IntegerType::class);
       $builder->add('liters');
       $builder->add('Amount');
       $builder->add('location');
       $builder->add('tankfull');
       $builder->add('Save',SubmitType::class);
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
