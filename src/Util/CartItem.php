<?php
// Fichier : CartItem.php
// Date : 2021-03-07
// Auteur : Davis Eath
// But : Représenter un article dans le panier

namespace App\Util;

use App\Entity\Product;

class CartItem
{
    private $product;
    private $quantity;

    public function __construct(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * Retourne l'objet produit
     * @return Product L'objet produit
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * Retourne la quantité du produit demandée dans le panier
     * @return int La quantité du produit
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Ajoute une quantité de produit dans le panier
     * @param int $quantity La quantité à ajouter
     * @return CartItem 
     */
    public function addQuantity(int $quantity): self
    {
        $this->quantity += $quantity;

        return $this;
    }

    /**
     * Modifie une quantité de produit dans le panier
     * @param int $quantity La quantité à mettre en place
     * @return CartItem 
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Retourne le prix unitaire multiplié par la quantité dans le panier
     * @return string Le prix de l'article
     */
    public function getPrice(): string
    {
        return $this->product->getPrice() * $this->quantity;
    }
}
