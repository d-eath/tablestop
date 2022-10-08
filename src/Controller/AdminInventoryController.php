<?php
// Fichier : AdminInventoryController.php
// Date : 2021-05-16
// Auteur : Davis Eath
// But : Gérer les requêtes des pages admin d'inventaire

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminInventoryController extends AbstractController
{
    /**
     * @Route("/admin/inventory", name="admin_inventory")
     */
    public function index(): Response
    {
        // vérification de connexion en tant qu'admin
        $loginRes = $this->forward('App\Controller\AdminController::generateLoginResponse');

        if (!$loginRes->isEmpty())
        {
            return $loginRes;
        }

        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('admin/inventory.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/admin/inventory/restock", name="admin_inventory_restock")
     */
    public function restock(): Response
    {
        // vérification de connexion en tant qu'admin
        $loginRes = $this->forward('App\Controller\AdminController::generateLoginResponse');

        if (!$loginRes->isEmpty())
        {
            return $loginRes;
        }

        $products = $this->getDoctrine()->getRepository(Product::class)->findBelowThreshold();

        return $this->render('admin/inventory_restock.html.twig', [
            'products' => $products
        ]);
    }
}
