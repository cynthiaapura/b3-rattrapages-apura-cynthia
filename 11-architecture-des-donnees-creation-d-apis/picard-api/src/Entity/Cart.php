<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: CartRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $status;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartItem::class, cascade: ['persist', 'remove'])]
    private Collection $cartItems;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
        $this->status = 'pending';
    }

    // getters et setters 
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): static
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems->add($cartItem);
            $cartItem->setCart($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): static
    {
        if ($this->cartItems->removeElement($cartItem)) {
            if ($cartItem->getCart() === $this) {
                $cartItem->setCart(null);
            }
        }

        return $this;
    }

    public function addProduct(Product $product, int $quantity = 1): static
    {
        foreach ($this->cartItems as $item) {
            if ($item->getProduct() === $product) {
                $item->setQuantity($item->getQuantity() + $quantity);
                return $this;
            }
        }

        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cartItem->setQuantity($quantity);
        $this->addCartItem($cartItem);

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        foreach ($this->cartItems as $item) {
            if ($item->getProduct() === $product) {
                $this->removeCartItem($item);
                break;
            }
        }

        return $this;
    }

    public function getTotal(): float
    {
        $total = 0.0;
        foreach ($this->cartItems as $item) {
            $total += $item->getProduct()->getPrice() * $item->getQuantity();
        }
        return $total;
    }

    public function getTotalQuantity(): int
    {
        $totalQuantity = 0;
        foreach ($this->cartItems as $item) {
            $totalQuantity += $item->getQuantity();
        }
        return $totalQuantity;
    }

    /**
     * Valide le panier et change son statut
     * @throws \LogicException leve une excepetion si le panier est vide
     */
    public function validate(): static
    {
        if ($this->cartItems->isEmpty()) {
            throw new \LogicException('Le panier est vide, impossible de valider.');
        }

        $this->setStatus('validated');
        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }
    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
