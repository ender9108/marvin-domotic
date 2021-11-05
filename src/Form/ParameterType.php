<?php

namespace App\Form;

use App\Entity\Parameter;
use App\Services\FormCleanFieldName;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParameterType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setDataMapper($this)
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                /** @var Parameter $parameter */
                $parameter = $event->getData();
                $form = $event->getForm();

                $fieldName = FormCleanFieldName::cleanFieldname($parameter->getTag());
                $fieldType = null;
                $fieldOptions = [
                    'required' => $parameter->getIsRequiered(),
                    'label' => $parameter->getTitle()
                ];

                if (!empty($parameter->getHelp())) {
                    $fieldOptions['help'] = $parameter->getHelp();
                }

                $fieldOptions['attr']['data-restart'] = $parameter->getRestartRequired() ? 'true' : 'false';

                switch ($parameter->getValuetype()) {
                    case 'string':
                        $fieldType = TextType::class;
                        break;
                    case 'password':
                        $fieldType = PasswordType::class;
                        break;
                    case 'int':
                        $fieldType = NumberType::class;
                        $fieldOptions['html5'] = true;
                        break;
                    case 'binary':
                        $fieldType = CheckboxType::class;
                        break;
                    case 'float':
                        $fieldType = NumberType::class;
                        $fieldOptions['html5'] = true;
                        $fieldOptions['attr']['step'] = 'any';
                        break;
                    case 'datetime':
                        $fieldType = DateTimeType::class;
                        $fieldOptions['widget'] = 'single_text';
                        break;
                    case 'date':
                        $fieldType = DateType::class;
                        $fieldOptions['widget'] = 'single_text';
                        break;
                    case 'text':
                        $fieldType = TextareaType::class;
                        break;
                    case 'array':
                        $fieldType = ChoiceType::class;
                        $fieldOptions['choices'] = [];
                        $fieldOptions['placeholder'] = '';
                        $values = array_map('trim', explode(',', $parameter->getValue()));

                        foreach ($values as $value) {
                            $fieldOptions['choices'][$value] = $value;
                        }
                        break;
                }

                $form->add($fieldName, $fieldType, $fieldOptions);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parameter::class,
            'translation_domain' => 'messages'
        ]);
    }

    /**
     * @param Parameter $viewData
     * @param iterable $forms
     */
    public function mapDataToForms($viewData, iterable $forms)
    {
        // there is no data yet, so nothing to prepopulate
        if (null === $viewData) {
            return;
        }

        // invalid data type
        if (!$viewData instanceof Parameter) {
            throw new UnexpectedTypeException($viewData, Parameter::class);
        }

        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $fieldName = FormCleanFieldName::cleanFieldname($viewData->getTag());

        if ($viewData->getValuetype() == 'binary') {
            $forms[$fieldName]->setData((bool) $viewData->getValue());
        } else {
            $forms[$fieldName]->setData($viewData->getValue());
        }
    }

    /**
     * @param iterable $forms
     * @param Parameter $viewData
     */
    public function mapFormsToData(iterable $forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);
        $fieldName = FormCleanFieldName::cleanFieldname($viewData->getTag());

        $viewData->setValue($forms[$fieldName]->getData());
    }
}
