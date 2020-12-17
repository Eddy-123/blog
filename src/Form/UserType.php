<?php

namespace App\Form;

use App\DataTransferObject\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
                ->add("email", EmailType::class, [
                    "label" => "Email"
                ])
                ->add("password", RepeatedType::class, [
                    "type" => PasswordType::class,
                    "first_options" => [
                        "label" => "Mot de passe"
                    ],
                    "second_options" => [
                        "label" => "Confirmer votre mot de passe"
                    ],
                    "invalid_message" => "Tapez le même mot de passe pour confirmer",
                    "constraints" => [
                        new NotBlank(),
                        new Length(["min" => 8])
                    ]
                ])
                ->add("pseudo", TextareaType::class)
                ;
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            "data_class" => User::class
        ]);
    }
}
