<?php
// Fichier : ProductImageType.php
// Date : 2021-05-16
// Auteur : Davis Eath
// But : Gérer la création des formulaires des images de produits

namespace App\Form;

use App\Util\ProductImageManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coverImage', FileType::class, [
                'required' => false
            ])
            ->add('removeCoverImage', CheckboxType::class, [
                'required' => false
            ])
            ->add('detailImage', FileType::class, [
                'required' => false
            ])
            ->add('removeDetailImage', CheckboxType::class, [
                'required' => false
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductImageManager::class,
        ]);
    }
}
