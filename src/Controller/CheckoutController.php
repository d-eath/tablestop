<?php
// Fichier : CheckoutController.php
// Date : 2021-04-23
// Auteur : Davis Eath
// But : Gérer les requêtes de passage de commande

namespace App\Controller;

use App\Entity\Customer;
use App\Service\CartSessionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout", name="checkout")
     */
    public function index(Request $req, CartSessionManager $cart): Response
    {
        if ($cart->countItems() == 0)
        {
            return $this->redirectToRoute('cart');
        }

        if (!$req->getSession()->has('loggedInCustomer'))
        {
            $this->addFlash('warning', 'Vous devez être connecté pour passer la commande.');

            return $this->redirectToRoute('login', [ 'a' => 'checkout' ]);
        }

        return $this->render('checkout.html.twig', [
            'cart' => $cart->getItems(),
            'breakdown' => $cart->getPriceBreakdown()
        ]);
    }

    /**
     * @Route("/checkout/success", name="checkout_success")
     */
    public function success(Request $req): Response
    {
        if (!$req->getSession()->has('loggedInCustomer'))
        {
            $this->addFlash('warning', 'Vous devez être connecté pour passer la commande.');

            return $this->redirectToRoute('login', [ 'a' => 'checkout' ]);
        }

        if (!$req->getSession()->has('orderSuccess'))
        {
            return $this->redirectToRoute('orders');
        }

        $customerId = $req->getSession()->get('loggedInCustomer');
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($customerId);

        $order = $req->getSession()->get('orderSuccess');

        $hasBackorder = false;

        foreach ($order->getItems() as $item)
        {
            if ($item->getBackorderQuantity() > 0)
            {
                $hasBackorder = true;
                break;
            }
        }

        $req->getSession()->remove('orderSuccess');

        return $this->render('checkout_success.html.twig', [
            'customer' => $customer,
            'order' => $order,
            'hasBackorder' => $hasBackorder
        ]);
    }
}
