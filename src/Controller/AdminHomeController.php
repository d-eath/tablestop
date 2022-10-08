<?php
// Fichier : AdminHomeController.php
// Date : 2021-05-16
// Auteur : Davis Eath
// But : Gérer les requêtes de la page admin d'accueil

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminHomeController extends AbstractController
{
    /**
     * @Route("/admin/home", name="admin_home")
     */
    public function index(): Response
    {
        // vérification de connexion en tant qu'admin
        $loginRes = $this->forward('App\Controller\AdminController::generateLoginResponse');

        if (!$loginRes->isEmpty())
        {
            return $loginRes;
        }

        return $this->render('admin/home.html.twig');
    }
}
