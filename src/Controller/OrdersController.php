<?php
// Fichier : OrdersController.php
// Date : 2021-04-23
// Auteur : Davis Eath
// But : Gérer les requêtes de la route Commandes

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends AbstractController
{
    /**
     * @Route("/orders", name="orders")
     */
    public function index(Request $req): Response
    {
        if (!$req->getSession()->has('loggedInCustomer'))
        {
            return $this->redirectToRoute('catalog');
        }

        $customerId = $req->getSession()->get('loggedInCustomer');

        $orders = $this->getDoctrine()->getRepository(Order::class)->findByCustomerId($customerId);

        return $this->render('orders.html.twig', [
            'orders' => $orders,
            'now' => new \DateTime()
        ]);
    }

    /**
     * @Route("/orders/cancel/{id}", name="orders_cancel")
     */
    public function cancel(Request $req, $id): Response
    {
        if (!$req->getSession()->has('loggedInCustomer'))
        {
            return $this->redirectToRoute('catalog');
        }

        $customerId = $req->getSession()->get('loggedInCustomer');

        $order = $this->getDoctrine()->getRepository(Order::class)->find($id);

        if (is_null($order) || $order->getCustomer()->getId() != $customerId)
        {
            throw new BadRequestException('La commande avec l\'identifiant donné n\'existe pas, ou vous n\'êtes pas autorisé à consulter cette commande');
        }

        $now = (new \DateTime())->getTimestamp();
        $cancelWindowEnd = $order->getDate()->modify('+48 hours')->getTimestamp();

        if ($now > $cancelWindowEnd)
        {
            $this->addFlash('error', "L'annulation de la commande № $id n'est plus disponible. " 
                . 'Veuillez nous contacter pour obtenir de l\'aide concernant votre commande.');

            return $this->redirectToRoute('orders');
        }

        // l'annulation est confirmé par l'utilisateur à l'intérieur de ce bloc
        if ($req->isMethod('POST'))
        {
            $em = $this->getDoctrine()->getManager();

            // remise des produits de la commande annulée en inventaire de la boutique
            foreach ($order->getItems() as $item)
            {
                $item->getProduct()->setInventoryStock($item->getProduct()->getInventoryStock() + $item->getQuantity());
            }

            $em->remove($order);
            $em->flush();

            $this->addFlash('success', "La commande № $id a été annulée avec succès.");

            return $this->redirectToRoute('orders');
        }

        return $this->render('orders_cancel.html.twig', [
            'order' => $order,
        ]);
    }
}
