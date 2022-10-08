<?php
// Fichier : PriceBreakdown.php
// Date : 2021-03-07
// Auteur : Davis Eath
// But : Calculer les prix additionnels d'un panier (frais de livraisons, taxes, total, etc.)

namespace App\Util;

class PriceBreakdown
{
    private $cart;
    private $tax1;
    private $tax2;

    public function __construct($cart)
    {
        $this->cart = $cart;

        $this->tax1 = new Tax('TPS', '0.05');
        $this->tax2 = new Tax('TVQ', '0.09975');
    }

    /**
     * Retourne le prix des produits dans le panier
     * @return string Le prix des produits
     */
    public function getItemsPrice(): string
    {
        $price = "0.00";

        foreach ($this->cart as $item)
        {
            $price += $item->getPrice();
        }

        return $price;
    }

    /**
     * Retourne les frais de livraison pour le panier de l'utilisateur
     * @return string Les frais de livraison
     */
    public function getShippingFee(): string
    {
        if ($this->getItemsPrice() >= 100)
        {
            return "0.00";
        }

        return "5.00";
    }

    /**
     * Retourne le prix total avant les taxes
     * @return string Le prix total avant les taxes
     */
    public function getSubTotal(): string
    {
        return $this->getItemsPrice() + $this->getShippingFee();
    }

    /**
     * Retourne l'objet de la taxe 1 (TPS)
     * @return Tax L'objet de la taxe 1 
     */
    public function getTax1(): Tax
    {
        return $this->tax1;
    }

    /**
     * Retourne l'objet de la taxe 2 (TVQ)
     * @return Tax L'objet de la taxe 2 
     */
    public function getTax2(): Tax
    {
        return $this->tax2;
    }

    /**
     * Retourne la taxe 1 appliquée pour le panier (TPS)
     * @return string La taxe 1 appliquée
     */
    public function getTax1Value(): string
    {
        return $this->tax1->applyTax($this->getSubTotal());
    }

    /**
     * Retourne la taxe 2 appliquée pour le panier (TVQ)
     * @return string La taxe 2 appliquée
     */
    public function getTax2Value(): string
    {
        return $this->tax2->applyTax($this->getSubTotal());
    }

    /**
     * Retourne le prix total du panier 
     * @return string Le prix total du panier
     */
    public function getTotal(): string
    {
        return $this->getSubTotal() + $this->getTax1Value() + $this->getTax2Value();
    }
}
