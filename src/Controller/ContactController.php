<?php
// Fichier : ContactController.php
// Date : 2021-02-04
// Auteur : Davis Eath
// But : Gérer les requêtes de la page Contact

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(): Response
    {
        return $this->render('contact.html.twig');
    }
}
