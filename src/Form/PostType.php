<?php

namespace App\Form;

use App\Entity\Post;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'attr' => [
                    'placeholder'=> 'Введите эл.адрес',
                    'class'=>'custom_class'
                ]
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder'=> 'Введите имя',
                    'class'=>'custom_class'
                ]
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'placeholder'=> 'Ваше сообщение',
                    'class'=>'custom_class'
                ]
            ])
            ->add('phone', NumberType::class, [
                'attr' => [
                    'placeholder'=> '89000000000',
                    'class'=>'custom_class'
                ]
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class'=>'btn btn-success',
                    'label'=>'Отправить'
                ]
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'post_form',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
