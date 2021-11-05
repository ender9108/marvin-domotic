<?php

namespace App\Entity;

use App\Entity\Traits\FileUploadEntity;
use App\Repository\ProtocolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ProtocolRepository::class)
 * @Vich\Uploadable()
 */
#[Broadcast]
class Protocol extends AbstractEntity
{
    use FileUploadEntity;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="protocol_image", fileNameProperty="path")
     * @Assert\File(
     *     maxSize="5M",
     *     mimeTypes={"image/png", "image/jpeg", "image/gif"}
     * )
     */
    protected ?File $uploadedFile = null;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="json")
     */
    private array $configuration = [];

    /**
     * @ORM\OneToMany(targetEntity=Module::class, mappedBy="protocol")
     */
    private Collection $modules;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private ?string $state;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $addingModuleAllowed = false;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private ?string $tag;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $actionEditPath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $actionDeletePath;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $service;

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

    public function getConfiguration(): ?array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): self
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function addModule(Module $module): self
    {
        if (!$this->modules->contains($module)) {
            $this->modules[] = $module;
            $module->setProtocol($this);
        }

        return $this;
    }

    public function removeModule(Module $module): self
    {
        if ($this->modules->removeElement($module)) {
            // set the owning side to null (unless already changed)
            if ($module->getProtocol() === $this) {
                $module->setProtocol(null);
            }
        }

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getAddingModuleAllowed(): ?bool
    {
        return $this->addingModuleAllowed;
    }

    public function setAddingModuleAllowed(bool $addingModuleAllowed): self
    {
        $this->addingModuleAllowed = $addingModuleAllowed;

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getActionEditPath(): ?string
    {
        return $this->actionEditPath;
    }

    public function setActionEditPath(string $actionEditPath): self
    {
        $this->actionEditPath = $actionEditPath;

        return $this;
    }

    public function getActionDeletePath(): ?string
    {
        return $this->actionDeletePath;
    }

    public function setActionDeletePath(string $actionDeletePath): self
    {
        $this->actionDeletePath = $actionDeletePath;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(string $service): self
    {
        $this->service = $service;

        return $this;
    }
}
