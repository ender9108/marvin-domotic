<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Room;
use App\Entity\Vendor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'general.name',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'general.name',
            ])
            ->add('uniqueIdentifier', null, [
                'required' => true,
                'disabled' => true,
                'label' => 'modules.unique_identifier'
            ])
            ->add('isDisplayOnDashboard', CheckboxType::class, [
                'required' => false,
                'label' => 'modules.display_on_dashboard',
            ])
            ->add('vendor', EntityType::class, [
                'required' => false,
                'class' => Vendor::class,
                'choice_label' => 'name',
                'label' => 'modules.vendor',
            ])
            ->add('room', EntityType::class, [
                'required' => false,
                'class' => Room::class,
                'choice_label' => 'name',
                'label' => 'rooms.room',
            ])
            ->add('uploadedFile', VichFileType::class, [
                'required' => false,
                'label' => false,
                'allow_delete' => true,
                'delete_label' => false,
                'asset_helper' => true,
                'download_label' => false,
                'download_uri' => false,
                'attr' => [
                    'accept' => 'image/jpeg,image/gif,image/png'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Module::class,
            'translation_domain' => 'messages'
        ]);
    }
}
