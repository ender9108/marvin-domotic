<?php

namespace App\Twig;

use App\Entity\Parameter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class TwigSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Environment $twig
    ) {}

    public function onKernelResponse(ControllerEvent $event) {
        /** @var Parameter[] $parameters */
        $parameters = $this->em->getRepository(Parameter::class)->findBy([
            'isAccessibleToTemplate' => true
        ], [
            'type' => 'ASC',
            'subtype' => 'ASC',
            'paramOrder' => 'ASC',
        ]);

        $values = [];

        foreach ($parameters as $parameter) {
            $tagParts = explode('.', $parameter->getTag());
            $value = null;
            $name = null;
            $key = null;

            foreach ($tagParts as $key => $part) {
                if ($key === 1) {
                    $name = $part;
                }

                if ($key === 2) {
                    $key = $part;
                    $value = $parameter->getValue();
                }
            }

            $values[$name][$key] = $value;
        }

        foreach ($values as $name => $value) {
            $this->twig->addGlobal($name, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [KernelEvents::CONTROLLER =>  'onKernelResponse' ];
    }
}