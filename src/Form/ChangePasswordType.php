<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Current Password',
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'New Password',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Change Password',
            ]);
    }
}