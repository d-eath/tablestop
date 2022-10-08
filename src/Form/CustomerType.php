<?php
// Fichier : CustomerType.php
// Date : 2021-04-01
// Auteur : Davis Eath
// But : Gérer la création des formulaires des comptes-client

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['context'] == 'account_password')
        {
            $builder
                ->add('oldPassword', PasswordType::class, [
                    'mapped' => false
                ])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe ne sont pas identiques.'
                ])
                ->add('submit', SubmitType::class);

            return;
        }

        $builder
            ->add('username', TextType::class)
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('gender', ChoiceType::class, [
                'placeholder' => 'Choisissez...',
                'choices' => [
                    'Neutre' => 'X',
                    'Féminin' => 'F',
                    'Masculin' => 'M'
                ]
            ])
            ->add('address', TextType::class)
            ->add('city', TextType::class)
            ->add('province', ChoiceType::class, [
                'placeholder' => 'Choisissez...',
                'choices' => [
                    'Alberta' => 'AB',
                    'Colombie-Britanique' => 'BC',
                    'Manitoba' => 'MB',
                    'Nouveau-Brunswick' => 'NB',
                    'Terre-Neuve-et-Labrador' => 'NL',
                    'Nouvelle-Écosse' => 'NS',
                    'Territoires du Nord-Ouest' => 'NT',
                    'Nunavut' => 'NU',
                    'Ontario' => 'ON',
                    'Île-du-Prince-Édouard' => 'MB',
                    'Québec' => 'QC',
                    'Saskatchewan' => 'SK',
                    'Yukon' => 'YT'
                ]
            ])
            ->add('postalCode', TextType::class)
            ->add('phoneNumber', TextType::class)
            ->add('email', TextType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne sont pas identiques.'
            ])
            ->add('submit', SubmitType::class);

        if ($options['context'] == 'account_edit')
        {
            $builder
                ->remove('username')
                ->remove('password');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
            'context' => 'none'
        ]);
    }
}
