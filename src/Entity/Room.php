<?php

namespace App\Entity;

use App\Entity\Interfaces\FileInterface;
use App\Entity\Traits\FileUploadEntity;
use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 * @Vich\Uploadable()
 */
#[Broadcast]
class Room extends AbstractEntity implements FileInterface
{
    use FileUploadEntity;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="room_image", fileNameProperty="path")
     * @Assert\File(
     *     maxSize="5M",
     *     mimeTypes={"image/png", "image/jpeg", "image/gif"}
     * )
     */
    protected ?File $uploadedFile = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $areasize;

    /**
     * @ORM\OneToMany(targetEntity=Module::class, mappedBy="room")
     */
    private $modules;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAreasize(): ?float
    {
        return $this->areasize;
    }

    public function setAreasize(?float $areasize): self
    {
        $this->areasize = $areasize;

        return $this;
    }

    /**
     * @return Collection|Module[]
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function addModule(Module $module): self
    {
        if (!$this->modules->contains($module)) {
            $this->modules[] = $module;
            $module->setRoom($this);
        }

        return $this;
    }

    public function removeModule(Module $module): self
    {
        if ($this->modules->removeElement($module)) {
            // set the owning side to null (unless already changed)
            if ($module->getRoom() === $this) {
                $module->setRoom(null);
            }
        }

        return $this;
    }
}
