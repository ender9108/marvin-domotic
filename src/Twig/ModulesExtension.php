<?php
namespace App\Twig;

use App\Entity\ModuleCommand;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ModulesExtension extends AbstractExtension
{
    public function __construct(
        private TranslatorInterface $translator
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('displayModuleValue', [$this, 'displayModuleValue']),
        ];
    }

    public function displayModuleValue($data, ModuleCommand $moduleCommand): string
    {
        $result = '';

        if (isset($data[$moduleCommand->getProperty()])) {
            switch ($moduleCommand->getParameters()['type']) {
                case 'numeric':
                    $result = strtr($data[$moduleCommand->getProperty()], ['.' => ',']);
                    break;
                case 'binary':
                    $result = (
                        true == $data[$moduleCommand->getProperty()] || 'on' == strtolower($data[$moduleCommand->getProperty()]) ?
                        $this->translator->trans('true', [], 'modules') :
                        $this->translator->trans('false', [], 'modules')
                    );
                    break;
                default:
                    $result = $this->translator->trans($data[$moduleCommand->getProperty()], [], 'modules');
                    break;
            }

            if (isset($moduleCommand->getParameters()['unit'])) {
                $result .= ' '.$moduleCommand->getParameters()['unit'];
            }
        } else {
            $result = $this->translator->trans('general.no_data');
        }

        return $result;
    }
}
