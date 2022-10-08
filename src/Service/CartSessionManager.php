<?php
// Fichier : CartSessionManager.php
// Date : 2021-03-07
// Auteur : Davis Eath
// But : Gérer les données du panier dans la session

namespace App\Service;

use App\Entity\Product;
use App\Util\CartItem;
use App\Util\PriceBreakdown;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartSessionManager
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;

        if (is_null($session->get('cart')))
        {
            $session->set('cart', []);
        }
    }

    /**
     * Obtient la liste de tous les articles dans le panier
     * @return CartItem[] La liste d'articles dans le panier
     */
    public function getItems(): array
    {
        return $this->session->get('cart');
    }

    /**
     * Ajoute un article dans le panier, ou augmente sa quantité si l'article est déjà dans le panier
     * @param Product $product L'objet produit
     * @param int $quantity La quantité à ajouter
     * @return bool Vrai si l'action était un succès
     */
    public function addItem(Product $product, int $quantity = 1): bool
    {
        if (is_null($product) || $quantity < 1)
        {
            return false;
        }

        $cart = $this->session->get('cart');
        $index = $this->indexOf($product->getId());

        if ($index > -1)
        {
            $cart[$index]->addQuantity($quantity);
        }
        else
        {
            $cart[] = new CartItem($product, $quantity);
        }

        $this->updateSession($cart);

        return true;
    }

    /**
     * Modifie la quantité d'un article dans le panier
     * @param int $productId L'ID du produit
     * @return bool Vrai si l'action était un succès
     */
    public function editItem(int $productId, int $quantity): bool
    {
        $cart = $this->session->get('cart');
        $index = $this->indexOf($productId);

        if ($index === -1 || $quantity < 1 || $quantity > 20)
        {
            return false;
        }

        $cart[$index]->setQuantity($quantity);

        $this->updateSession($cart);

        return true;
    }

    /**
     * Retire un article dans le panier
     * @param int $productId L'ID du produit
     * @return bool Vrai si l'action était un succès
     */
    public function removeItem(int $productId): bool
    {
        $cart = $this->session->get('cart');
        $index = $this->indexOf($productId);

        if ($index === -1)
        {
            return false;
        }

        array_splice($cart, $index, 1);

        $this->updateSession($cart);

        return true;
    }

    /**
     * Retire tous les article du panier
     * @return bool Vrai si l'action était un succès
     */
    public function clearCart(): bool
    {
        $this->updateSession([]);

        return true;
    }

    /**
     * Compte le nombre d'items dans le panier
     * @return int Le nombre d'items
     */
    public function countItems(): int
    {
        $cart = $this->session->get('cart');
        $itemCount = 0;

        foreach ($cart as $item)
        {
            $itemCount += $item->getQuantity();
        }

        return $itemCount;
    }

    /**
     * Retorune le sommaire des prix pour le panier (frais de livraison, taxes, total, etc.)
     * @return PriceBreakdown Le sommaire des prix
     */
    public function getPriceBreakdown(): PriceBreakdown
    {
        return new PriceBreakdown($this->session->get('cart'));
    }

    /**
     * Retourne l'index d'un produit dans le tableau du panier
     * (parce que PHP est de la grosse merde et n'offre pas de méthodes d'itération de tableau pour trouver un élément)
     * @param int $productId L'ID du produit
     * @return int L'index du produit, ou -1 s'il n'est pas dans le tableau
     */
    private function indexOf(int $productId): int
    {
        $cart = $this->session->get('cart');

        foreach ($cart as $index => $item)
        {
            if ($productId == $item->getProduct()->getId())
            {
                return $index;
            }
        }

        return -1;
    }

    /**
     * Met à jour les sessions de variable en lien avec le panier
     * @param CartItem[] $cart Le panier à jour
     * @return void 
     */
    private function updateSession(array $cart)
    {
        $this->session->set('cart', $cart);
        $this->session->set('cartItemCount', $this->countItems($cart));
    }
}
