<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType {

  const LABEL_CLASS = 'form-control-label';

  const INPUT_CLASS = 'form-control';

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $styles = [
      'label_attr' => ['class' => self::LABEL_CLASS],
      'attr' => ['class' => self::INPUT_CLASS],
    ];

    $builder
      ->add('email', EmailType::class, $styles)
      ->add('username', TextType::class, $styles)
      ->add('plainPassword', RepeatedType::class, [
        'type' => PasswordType::class,
        'first_options' => [
            'label' => 'Password',
          ] + $styles,
        'second_options' => [
            'label' => 'Repeat Password',
          ] + $styles,
      ]);
  }

  /**
   * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
   */
  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}