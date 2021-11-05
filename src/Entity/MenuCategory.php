<?php

namespace App\Entity;

use App\Repository\MenuCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

/**
 * @ORM\Entity(repositoryClass=MenuCategoryRepository::class)
 */
#[Broadcast]
class MenuCategory extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $title;

    /**
     * @ORM\OneToMany(targetEntity=Menu::class, mappedBy="menuCategory", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $menus;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $menuCategoryOrder = 99;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isEnable = true;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
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

    /**
     * @return Collection
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->setMenuCategory($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getMenuCategory() === $this) {
                $menu->setMenuCategory(null);
            }
        }

        return $this;
    }

    public function getMenuCategoryOrder(): ?int
    {
        return $this->menuCategoryOrder;
    }

    public function setMenuCategoryOrder(int $menuCategoryOrder): self
    {
        $this->menuCategoryOrder = $menuCategoryOrder;

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
}
