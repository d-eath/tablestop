<?php
// Fichier : LoginController.php
// Date : 2021-04-01
// Auteur : Davis Eath
// But : Gérer les requêtes de la route Connexion (comptes)

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $req): Response
    {
        // prévention des "injections" url
        if ($req->getSession()->has('loggedInCustomer'))
        {
            return $this->redirectToRoute('catalog');
        }

        if ($req->isMethod('POST'))
        {
            $username = $req->request->get('username');
            $password = $req->request->get('password');

            if (empty($username) || empty($password))
            {
                $this->addFlash('error', 'Le nom d\'utilisateur ou le mot de passe ne peut pas être vide.');
                return $this->redirectToRoute('login', $req->query->all());
            }

            $customer = $this->getDoctrine()->getRepository(Customer::class)->findByUsername($username);

            if (is_null($customer) || !$customer->verifyPassword($password))
            {
                $this->addFlash('error', 'Le nom d\'utilisateur ou le mot de passe est invalide.');
                return $this->redirectToRoute('login', $req->query->all());
            }

            $req->getSession()->remove('newCustomer');
            $req->getSession()->set('loggedInCustomer', $customer->getId());
            $req->getSession()->set('customerName', $customer->getFirstName() . ' ' . $customer->getLastName());

            // redirection à la route donné si présent
            if ($req->query->has('a'))
            {
                try
                {
                    $route = $req->query->get('a');
                    $url = $this->generateUrl($route);

                    return $this->redirect($url);
                }
                catch (RouteNotFoundException $e)
                {
                }
            }

            $this->addFlash('success', 'Bon retour, ' . $customer->getFirstName() . ' !');

            return $this->redirectToRoute('catalog');
        }

        return $this->render('login.html.twig');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $req): Response
    {
        // prévention des "injections" url
        if (!$req->getSession()->has('loggedInCustomer'))
        {
            return $this->redirectToRoute('catalog');
        }

        $customerId = $req->getSession()->get('loggedInCustomer');
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($customerId);

        $this->addFlash('success', 'Au revoir, ' . $customer->getFirstName() . ' !');
        $req->getSession()->remove('loggedInCustomer');
        $req->getSession()->remove('customerName');

        return $this->redirectToRoute('catalog');
    }
}
