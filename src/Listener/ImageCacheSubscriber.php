<?php
namespace App\Listener;

use App\Entity\Interfaces\FileInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageCacheSubscriber implements EventSubscriber
{
    public function __construct(
        private CacheManager $cacheManager,
        private UploaderHelper $uploaderHelper
    ) {}

    public function getSubscribedEvents(): array
    {
        return ['preRemove', 'preUpdate'];
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof FileInterface) {
            $this->cacheManager->remove($this->uploaderHelper->asset($args->getEntity(), 'uploadedFile'));
        }
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        if ($args->getEntity() instanceof FileInterface) {
            $this->cacheManager->remove($this->uploaderHelper->asset($args->getEntity(), 'uploadedFile'));
        }
    }
}