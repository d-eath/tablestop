<?php
// Fichier : SignupController.php
// Date : 2021-04-01
// Auteur : Davis Eath
// But : Gérer les requêtes de la route Inscription (création de compte)

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignupController extends AbstractController
{
    /**
     * @Route("/signup", name="signup")
     */
    public function index(Request $req): Response
    {
        // prévention des "injections" url
        if ($req->getSession()->has('loggedInCustomer'))
        {
            return $this->redirectToRoute('catalog');
        }

        if ($req->getSession()->has('newCustomer'))
        {
            $customer = $req->getSession()->get('newCustomer');
            $req->getSession()->remove('newCustomer');
        }
        else
        {
            $customer = new Customer();
        }

        $form = $this->createForm(CustomerType::class, $customer, ['context' => 'signup']);

        if ($req->isMethod('POST'))
        {
            $form->handleRequest($req);

            if ($form->isValid())
            {
                $req->getSession()->set('newCustomer', $customer);
                return $this->redirectToRoute('signup_confirm');
            }
            else
            {
                $this->addFlash('error', 'Une ou plusieurs erreurs se sont produites. Veuillez réessayer.');
            }
        }

        return $this->render('signup.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/signup/confirm", name="signup_confirm")
     */
    public function confirm(Request $req): Response
    {
        // prévention des "injections" url
        if ($req->getSession()->has('loggedInCustomer'))
        {
            return $this->redirectToRoute('catalog');
        }

        // redirection vers le formulaire s'il n'y a pas de client candidat à confirmer
        if (!$req->getSession()->has('newCustomer'))
        {
            return $this->redirectToRoute('signup');
        }

        $customer = $req->getSession()->get('newCustomer');

        if ($req->isMethod('POST'))
        {
            $action = $req->request->get('action');

            if ($action == 'confirm')
            {
                try
                {
                    $em = $this->getDoctrine()->getManager();
                    
                    $em->persist($customer);
                    $em->flush();
                }
                catch (UniqueConstraintViolationException $e)
                {
                    $this->addFlash('error', 'Un compte avec ce nom d\'utilisateur existe déjà.');

                    return $this->redirectToRoute('signup');
                }

                $req->getSession()->remove('newCustomer');
                $req->getSession()->set('loggedInCustomer', $customer->getId());
                $req->getSession()->set('customerName', $customer->getFirstName() . ' ' . $customer->getLastName());
                $this->addFlash('success', 'Bienvenue, ' . $customer->getFirstName() . ' !');

                return $this->redirectToRoute('catalog');
            }
            else if ($action == 'cancel')
            {
                return $this->redirectToRoute('signup');
            }
        }

        return $this->render('signup_confirm.html.twig', [
            'customer' => $customer
        ]);
    }
}
