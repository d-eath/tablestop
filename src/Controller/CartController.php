<?php
// Fichier : CartController.php
// Date : 2021-03-07
// Auteur : Davis Eath
// But : Gérer les requêtes de la route Panier

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Service\CartSessionManager;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(Request $req, CartSessionManager $cart): Response
    {
        if ($req->query->has('action'))
        {
            return $this->action($req, $cart);
        }

        return $this->render('cart.html.twig', [
            'cart' => $cart->getItems(),
            'breakdown' => $cart->getPriceBreakdown()
        ]);
    }

    /**
     * Fonction qui gère la route Panier lorsque qu'il y a un paramètre Action dans le querystring
     */
    public function action(Request $req, CartSessionManager $cart): Response
    {
        $action = $req->query->get('action');
        $id = (int)$req->query->get('id');
        $value = (int)$req->query->get('value');
        $product = $this->fetchProduct($id);

        switch ($action)
        {
            case 'item_add':
                $success = $cart->addItem($product);
                $message = $success ? 'Article ajouté au panier' : 'Erreur lors de l\'ajout de l\'article au panier';
                break;

            case 'item_edit':
                $success = $cart->editItem($id, $value);
                $message = $success ? 'Quantité de l\'article au panier modifié' : 'Erreur lors de la modification de la quantité de l\'article au panier';
                break;

            case 'item_remove':
                $success = $cart->removeItem($id);
                $message = $success ? 'Article retiré du panier' : 'Erreur lors du retirement de l\'article au panier';
                break;

            case 'clear':
                $success = $cart->clearCart();
                $message = $success ? 'Panier vidé' : 'Erreur lors du vidage du panier';
                break;

            default:
                $message = 'Action invalide';
                $success = false;
        }

        // Action dans le panier = redirection au panier après l'action
        if (!$req->query->has('no-redirect'))
        {
            return $this->redirectToRoute('cart');
        }

        if (!is_null($product))
        {
            $title = $product->getTitle();
        }

        $data = [
            'itemCount' => $cart->countItems(),
            'message' => $message,
            'product' => $title,
            'success' => $success
        ];

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Obtient un produit par son ID
     */
    private function fetchProduct($id)
    {
        return $this->getDoctrine()->getRepository(Product::class)->find($id);
    }
}
