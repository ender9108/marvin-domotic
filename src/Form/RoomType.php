<?php

namespace App\Form;

use App\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'general.name',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'general.description',
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
            ->add('areasize', NumberType::class, [
                'required' => false,
                'label' => 'rooms.areasize',
                'html5' => true,
                'attr' => [
                    'step' => 'any'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
            'translation_domain' => 'messages'
        ]);
    }
}
