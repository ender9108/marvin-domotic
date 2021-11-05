<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ToolsExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('fullname', [$this, 'getFullname']),
            new TwigFunction('basename', [$this, 'getBasename']),
            new TwigFunction('booleanIconCheck', [$this, 'getBooleanIconCheck'], ['is_safe' => ['html']]),
        ];
    }

    public function getBooleanIconCheck(bool $value): string
    {
        return (true === $value) ? '<i class="fas fa-check text-success"></i>' : '';
    }

    public function getFullname(mixed $entity): String
    {
        if (
            !method_exists($entity, 'getFirstname') &&
            !method_exists($entity, 'getLastname')
        ) {
            return '';
        }

        return $entity->getFirstname() . ' ' . $entity->getLastname();
    }

    public function getBasename(string $path): string
    {
        return basename($path);
    }
}
