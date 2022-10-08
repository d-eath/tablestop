<?php
// Fichier : PaymentController.php
// Date : 2021-04-23
// Auteur : Davis Eath
// But : Gérer les requêtes de paiement de la commande

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\OrderItem;
use App\Service\CartSessionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    /**
     * @Route("/checkout/pay", name="payment")
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

            return $this->redirectToRoute('login', ['a' => 'checkout']);
        }

        $req->getSession()->remove('orderSuccess');

        // POST = "paiement" effectué
        if ($req->isMethod('POST'))
        {
            return $this->success($req, $cart);
        }

        return $this->render('payment.html.twig');
    }

    /**
     * Cette fonction est appelée lorsqu'un "paiement" est réussi
     */
    private function success(Request $req, CartSessionManager $cart): Response
    {
        $em = $this->getDoctrine()->getManager();

        $customerId = $req->getSession()->get('loggedInCustomer');
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($customerId);

        $order = new Order();

        $order->setCustomer($customer);
        $order->setDate(new \DateTime());
        $order->setTotal($cart->getPriceBreakdown()->getTotal());

        foreach ($cart->getItems() as $item)
        {
            $orderItem = new OrderItem();

            $product = $this->getDoctrine()->getRepository(Product::class)->find($item->getProduct()->getId());
            $quantity = $item->getQuantity();
            $stock = $product->getInventoryStock();

            $orderItem->setProduct($product);

            // rupture de stock présent pour ce produit
            if ($quantity > $stock)
            {
                $product->setInventoryStock(0);

                $orderItem->setQuantity($stock);
                $orderItem->setBackorderQuantity($quantity - $stock);
            }
            // pas de rupture de stock pour ce produit
            else
            {
                $product->setInventoryStock($stock - $quantity);

                $orderItem->setQuantity($quantity);
                $orderItem->setBackorderQuantity(0);
            }

            $order->addItem($orderItem);

            $em->persist($orderItem);
        }

        $em->persist($order);
        $em->flush();

        $cart->clearCart();

        $req->getSession()->set('orderSuccess', $order);

        return new Response('ok');
    }
}
