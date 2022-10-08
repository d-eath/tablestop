<?php
// Fichier : AdminSalesController.php
// Date : 2021-05-16
// Auteur : Davis Eath
// But : Gérer les requêtes de la page admin de ventes

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminSalesController extends AbstractController
{
    /**
     * @Route("/admin/sales", name="admin_sales")
     */
    public function index(): Response
    {
        // vérification de connexion en tant qu'admin
        $loginRes = $this->forward('App\Controller\AdminController::generateLoginResponse');

        if (!$loginRes->isEmpty())
        {
            return $loginRes;
        }

        $orders = $this->getDoctrine()->getRepository(Order::class)->findAllDesc();

        return $this->render('admin/sales.html.twig', [
            'orders' => $orders
        ]);
    }
}
