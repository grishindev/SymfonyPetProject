<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", mappedBy="categories")
     */
    private $products;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Category", inversedBy="category", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Category", mappedBy="parent", cascade={"persist", "remove"})
     */
    private $category;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->removeCategory($this);
        }

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getCategory(): ?self
    {
        return $this->category;
    }

    public function setCategory(self $category): self
    {
        $this->category = $category;

        // set the owning side of the relation if necessary
        if ($this !== $category->getParent()) {
            $category->setParent($this);
        }

        return $this;
    }
}
