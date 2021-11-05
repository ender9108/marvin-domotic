<?php

namespace App\Entity;

use App\Repository\ParameterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

/**
 * @ORM\Entity(repositoryClass=ParameterRepository::class)
 */
#[Broadcast]
class Parameter extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $type;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private string $subtype;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $value;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $valuetype;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isRequiered = false;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $tag;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $help;

    /**
     * @ORM\Column(type="integer")
     */
    private int $paramOrder = 99;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $granted = 'ROLE_USER';

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $restartRequired = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isAccessibleToTemplate = false;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSubtype(): ?string
    {
        return $this->subtype;
    }

    public function setSubtype(string $subtype): self
    {
        $this->subtype = $subtype;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValuetype(): ?string
    {
        return $this->valuetype;
    }

    public function setValuetype(string $valuetype): self
    {
        $this->valuetype = $valuetype;

        return $this;
    }

    public function getIsRequiered(): ?bool
    {
        return $this->isRequiered;
    }

    public function setIsRequiered(bool $isRequiered): self
    {
        $this->isRequiered = $isRequiered;

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

    public function getHelp(): ?string
    {
        return $this->help;
    }

    public function setHelp(?string $help): self
    {
        $this->help = $help;

        return $this;
    }

    public function getParamOrder(): ?int
    {
        return $this->paramOrder;
    }

    public function setParamOrder(int $paramOrder): self
    {
        $this->paramOrder = $paramOrder;

        return $this;
    }

    public function getGranted(): ?string
    {
        return $this->granted;
    }

    public function setGranted(?string $granted): self
    {
        $this->granted = $granted;

        return $this;
    }

    public function getRestartRequired(): ?bool
    {
        return $this->restartRequired;
    }

    public function setRestartRequired(bool $restartRequired): self
    {
        $this->restartRequired = $restartRequired;

        return $this;
    }

    public function getIsAccessibleToTemplate(): ?bool
    {
        return $this->isAccessibleToTemplate;
    }

    public function setIsAccessibleToTemplate(bool $isAccessibleToTemplate): self
    {
        $this->isAccessibleToTemplate = $isAccessibleToTemplate;

        return $this;
    }
}
