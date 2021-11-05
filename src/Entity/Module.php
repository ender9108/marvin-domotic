<?php

namespace App\Entity;

use App\Entity\Traits\FileUploadEntity;
use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ModuleRepository::class)
 * @Vich\Uploadable()
 */
#[Broadcast]
class Module extends AbstractEntity
{
    use FileUploadEntity;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="module_image", fileNameProperty="path")
     * @Assert\File(
     *     maxSize="5M",
     *     mimeTypes={"image/png", "image/jpeg", "image/gif"}
     * )
     */
    protected ?File $uploadedFile = null;

    public const STATE_ONLINE = 1;
    public const STATE_LEAVE = 2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\ManyToOne(targetEntity=Protocol::class, inversedBy="modules")
     */
    private ?Protocol $protocol;

    /**
     * @ORM\Column(type="json")
     */
    private array $parameters = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $uniqueIdentifier;

    /**
     * @ORM\ManyToOne(targetEntity=Vendor::class, inversedBy="modules", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private ?Vendor $vendor;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $state = self::STATE_ONLINE;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="modules")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private ?Room $room;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $data = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $dataParameters = [];

    /**
     * @ORM\OneToMany(targetEntity=ModuleCommand::class, mappedBy="module", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $moduleCommands;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isDisplayOnDashboard = false;

    public function __construct()
    {
        $this->moduleCommands = new ArrayCollection();
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

    public function getProtocol(): ?Protocol
    {
        return $this->protocol;
    }

    public function setProtocol(?Protocol $protocol): self
    {
        $this->protocol = $protocol;

        return $this;
    }

    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getUniqueIdentifier(): ?string
    {
        return $this->uniqueIdentifier;
    }

    public function setUniqueIdentifier(string $uniqueIdentifier): self
    {
        $this->uniqueIdentifier = $uniqueIdentifier;

        return $this;
    }

    public function getVendor(): ?Vendor
    {
        return $this->vendor;
    }

    public function setVendor(?Vendor $vendor): self
    {
        $this->vendor = $vendor;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getDataParameters(): ?array
    {
        return $this->dataParameters;
    }

    public function setDataParameters(?array $dataParameters): self
    {
        $this->dataParameters = $dataParameters;

        return $this;
    }

    /**
     * @return Collection|ModuleCommand[]
     */
    public function getModuleCommands(): Collection
    {
        return $this->moduleCommands;
    }

    public function addModuleCommand(ModuleCommand $moduleCommand): self
    {
        if (!$this->moduleCommands->contains($moduleCommand)) {
            $this->moduleCommands[] = $moduleCommand;
            $moduleCommand->setModule($this);
        }

        return $this;
    }

    public function removeModuleCommand(ModuleCommand $moduleCommand): self
    {
        if ($this->moduleCommands->removeElement($moduleCommand)) {
            // set the owning side to null (unless already changed)
            if ($moduleCommand->getModule() === $this) {
                $moduleCommand->setModule(null);
            }
        }

        return $this;
    }

    public function getIsDisplayOnDashboard(): ?bool
    {
        return $this->isDisplayOnDashboard;
    }

    public function setIsDisplayOnDashboard(bool $isDisplayOnDashboard): self
    {
        $this->isDisplayOnDashboard = $isDisplayOnDashboard;

        return $this;
    }
}
