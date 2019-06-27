<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class TollType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options)
   {
       //$builder->add('id');
       $builder->add('date', DateType::class, array('widget' => 'single_text'));
       $builder->add('plaza');
       $builder->add('amount');
       $builder->add('Save',SubmitType::class);
   }
   
   public function getName()
   {
       return 'Toll';
   }
   
   public function getDefaultOptions(array $options)
   {
       return array('data_class' => 'App\Entity\TollSlip');
   }

}
