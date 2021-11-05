<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */
class Menu extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $icon;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $menuOrder;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isEnable = false;

    /**
     * @ORM\ManyToOne(targetEntity=MenuCategory::class, inversedBy="menus")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?MenuCategory $menuCategory;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $activeToken;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getMenuOrder(): ?int
    {
        return $this->menuOrder;
    }

    public function setMenuOrder(int $menuOrder): self
    {
        $this->menuOrder = $menuOrder;

        return $this;
    }

    public function getIsEnable(): ?bool
    {
        return $this->isEnable;
    }

    public function setIsEnable(bool $isEnable): self
    {
        $this->isEnable = $isEnable;

        return $this;
    }

    public function getMenuCategory(): ?MenuCategory
    {
        return $this->menuCategory;
    }

    public function setMenuCategory(?MenuCategory $menuCategory): self
    {
        $this->menuCategory = $menuCategory;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getActiveToken(): ?string
    {
        return $this->activeToken;
    }

    public function setActiveToken(string $activeToken): self
    {
        $this->activeToken = $activeToken;

        return $this;
    }
}
