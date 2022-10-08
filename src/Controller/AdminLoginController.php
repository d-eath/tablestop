<?php
// Fichier : AdminLoginController.php
// Date : 2021-05-16
// Auteur : Davis Eath
// But : Gérer les requêtes de la page admin de connexion

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminLoginController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function login(Request $req): Response
    {
        $session = $req->getSession();

        if ($session->has('loggedInCustomer') && $session->get('loggedInCustomer') == 0)
        {
            return $this->redirectToRoute('admin_home');
        }

        if ($req->isMethod('POST'))
        {
            // déconnexion de l'utilisateur (non admin) présentement connecté
            $req->getSession()->remove('loggedInCustomer');
            $req->getSession()->remove('customerName');

            $username = $req->request->get('username');
            $password = $req->request->get('password');

            if (empty($username) || empty($password))
            {
                $this->addFlash('error', 'Le nom d\'utilisateur ou le mot de passe ne peut pas être vide.');
                return $this->redirectToRoute('admin_login');
            }

            $customer = $this->getDoctrine()->getRepository(Customer::class)->findByUsername($username);

            if (is_null($customer) || !$customer->verifyPassword($password))
            {
                $this->addFlash('error', 'Le nom d\'utilisateur ou le mot de passe est invalide.');
                return $this->redirectToRoute('admin_login');
            }

            // client ID 0 = admin
            if ($customer->getId() != 0)
            {
                $this->addFlash('error', 'Le compte « ' . $customer->getUsername() . ' » n\'est pas autorisé à accéder au panneau d\'administration.');
                return $this->redirectToRoute('admin_login');
            }

            $req->getSession()->remove('newCustomer');
            $req->getSession()->set('loggedInCustomer', $customer->getId());
            $req->getSession()->set('customerName', $customer->getFirstName() . ' ' . $customer->getLastName());

            $this->addFlash('success', 'Bon retour, ' . $customer->getFirstName() . ' !');

            return $this->redirectToRoute('admin_home');
        }

        if ($session->has('loggedInCustomer'))
        {
            $this->addFlash('warning', 'Le compte actuellement connecté n\'est pas autorisé à accéder au panneau d\'administration. '
                . 'Veuillez vous connecter à un compte administrateur pour continuer.');
        }

        return $this->render('admin/login.html.twig');
    }

    /**
     * @Route("/admin/logout", name="admin_logout")
     */
    public function logout(Request $req): Response
    {
        if (!$req->getSession()->has('loggedInCustomer'))
        {
            return $this->redirectToRoute('admin_login');
        }

        $customerId = $req->getSession()->get('loggedInCustomer');
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($customerId);

        $this->addFlash('success', 'Au revoir, ' . $customer->getFirstName() . ' !');
        $req->getSession()->remove('loggedInCustomer');
        $req->getSession()->remove('customerName');

        return $this->redirectToRoute('admin_login');
    }
}
