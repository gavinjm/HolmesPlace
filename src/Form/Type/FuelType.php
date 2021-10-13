<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckBoxType;




class FuelType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options)
   {
       //$builder->add('id');
       $builder->add('date',DateType::class);
       $builder->add('vehicle', IntegerType::class);
       $builder->add('odometer',IntegerType::class);
       $builder->add('liters');
       $builder->add('amount');
       $builder->add('location',TextType::class);
       $builder->add('tankfull',CheckBoxType::class);
       $builder->add('Save',SubmitType::class);
   }
   
   public function getName()
   {
       return 'Fuel';
   }
   
   public function getDefaultOptions(array $options)
   {
       return array('data_class' => 'App\Entity\Fuel');
   }
   
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Fuel::class,
        ]);
    }

}
