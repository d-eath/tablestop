<?php
// Fichier : Product.php
// Date : 2021-02-04
// Auteur : Davis Eath
// But : Représenter un produit dans la base de données

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\Length(min=2, minMessage="Le titre doit être d'au moins 2 caractères.",
     *                max=500, maxMessage="Le titre doit être d'au plus 500 caractères."))
     * @Assert\NotNull(message="Le titre ne peut pas être vide.")
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Un produit doit avoir une catégorie.")
     */
    private $category;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     * @Assert\NotNull(message="Le prix ne peut pas être vide.")
     * @Assert\GreaterThanOrEqual(value="0", message="Le prix ne peut pas être négatif.")
     * @Assert\LessThanOrEqual(value="10000", message="Le prix ne doit pas dépasser 10000 $.")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=2, minMessage="La description doit être d'au moins 2 caractères.")
     * @Assert\NotNull(message="La description ne peut pas être vide.")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="Le nombre en stock ne peut pas être vide.")
     * @Assert\GreaterThanOrEqual(value="0", message="Le nombre en stock ne peut pas être négatif.")
     * @Assert\LessThanOrEqual(value="10000", message="Le nombre en stock ne doit pas dépasser 10000.")
     */
    private $inventoryStock;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="Le seuil minimum ne peut pas être vide.")
     * @Assert\GreaterThanOrEqual(value="0", message="Le seuil minimum ne peut pas être négatif.")
     * @Assert\LessThanOrEqual(value="10000", message="Le seuil minimum ne doit pas dépasser 10000.")
     */
    private $minRestock;

    /**
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="product")
     */
    private $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getInventoryStock(): ?int
    {
        return $this->inventoryStock;
    }

    public function setInventoryStock(int $inventoryStock): self
    {
        $this->inventoryStock = $inventoryStock;

        return $this;
    }

    public function getMinRestock(): ?int
    {
        return $this->minRestock;
    }

    public function setMinRestock(int $minRestock): self
    {
        $this->minRestock = $minRestock;

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem))
        {
            $this->orderItems[] = $orderItem;
            $orderItem->setProduct($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem))
        {
            // set the owning side to null (unless already changed)
            if ($orderItem->getProduct() === $this)
            {
                $orderItem->setProduct(null);
            }
        }

        return $this;
    }
}
