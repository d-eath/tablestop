<?php
// Fichier : AdminController.php
// Date : 2021-05-16
// Auteur : Davis Eath
// But : Gérer les requêtes d'accès au panneau d'administration

namespace App\Controller;

use BadMethodCallException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(Request $req): Response
    {
        // vérification de connexion en tant qu'admin
        $loginRes = $this->generateLoginResponse($req);

        if (!$loginRes->isEmpty())
        {
            return $loginRes;
        }

        return $this->redirectToRoute('admin_home');
    }

    /**
     * Génère une réponse de redirection si l'utilisateur n'est pas connecté en tant qu'admin
     */
    public function generateLoginResponse(Request $req): Response
    {
        $session = $req->getSession();

        // client ID 0 = admin
        if (!$session->has('loggedInCustomer') || $session->get('loggedInCustomer') != 0)
        {
            return $this->redirectToRoute('admin_login');
        }
        
        // réponse 204 = pas de redirection
        return new Response('', 204);
    }
}
