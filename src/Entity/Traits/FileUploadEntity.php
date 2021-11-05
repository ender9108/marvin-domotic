<?php

namespace App\Entity\Traits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

trait FileUploadEntity
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected ?string $path = '';

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param File|null $uploadedFile
     */
    public function setUploadedFile(?File $uploadedFile = null): void
    {
        $this->uploadedFile = $uploadedFile;

        if (null !== $uploadedFile) {
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function getUploadedFile(): ?File
    {
        return $this->uploadedFile;
    }
}