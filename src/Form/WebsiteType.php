<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Website;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebsiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
    'attr' => [
        'class' => 'w-full p-2 rounded text-black'
    ]
])
->add('url', null, [
    'attr' => [
        'class' => 'w-full p-2 rounded text-black'
    ]
])
           # ->add('lastStatus')
           # ->add('isUp')
           # ->add('user', EntityType::class, [
              #  'class' => User::class,
               # 'choice_label' => 'id',
           # ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Website::class,
        ]);
    }
}
