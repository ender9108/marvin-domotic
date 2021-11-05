<?php

namespace App\Plugins\Zigbee2Mqtt\Form;

use App\Entity\Protocol;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ProtocolZigbee2MqttType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('name', null, [
                'required' => true,
                'label' => 'zigbee2mqtt.name',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'zigbee2mqtt.description',
            ])
            ->add('addingModuleAllowed', CheckboxType::class, [
                'required' => false,
                'label' => 'zigbee2mqtt.adding_module_allowed',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Protocol::class,
            'translation_domain' => 'zigbee2mqtt.messages'
        ]);
    }
}
