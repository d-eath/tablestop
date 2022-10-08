<?php
// Fichier : AccountController.php
// Date : 2021-04-01
// Auteur : Davis Eath
// But : Gérer les requêtes de la route Compte

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function index(Request $req): Response
    {
        // prévention des "injections" url
        if (!$req->getSession()->has('loggedInCustomer'))
        {
            return $this->redirectToRoute('catalog');
        }

        $customerId = $req->getSession()->get('loggedInCustomer');
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($customerId);
        $oldPassword = $customer->getPassword();

        // formulaire pour info du compte
        $editForm = $this->createForm(CustomerType::class, $customer, [
            'context' => 'account_edit',
            'method' => 'PATCH'
        ]);

        // formulaire pour mot de passe du compte
        $passwdForm = $this->get('form.factory')->createNamed('password', CustomerType::class, $customer, [
            'context' => 'account_password',
            'method' => 'PATCH'
        ]);

        $editForm->handleRequest($req);
        $passwdForm->handleRequest($req);

        // soumission dans le formulaire des infos
        if ($editForm->isSubmitted())
        {
            if ($editForm->isValid())
            {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Les informations de votre compte ont été mises à jour.');
                $req->getSession()->set('customerName', $customer->getFirstName() . ' ' . $customer->getLastName());

                return $this->redirectToRoute('account');
            }
            else
            {
                $this->addFlash('error', 'Une ou plusieurs erreurs se sont produites lors de la modification des informations.');
            }
        }
        // soumission dans le formulaire de mot de passe
        else if ($passwdForm->isSubmitted())
        {
            if ($passwdForm->isValid())
            {
                // vérification du mot de passe actuel
                if (!password_verify($req->request->get('password')['old_password'], $oldPassword))
                {
                    $this->addFlash('error', 'Votre mot de passe actuel est incorrect.');

                    return $this->redirectToRoute('account');
                }

                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Le mot de passe de votre compte a été mis à jour.');

                return $this->redirectToRoute('account');
            }
            else
            {
                $this->addFlash('error', 'Une ou plusieurs erreurs se sont produites lors de la modification du mot de passe.');
            }
        }

        return $this->render('account.html.twig', [
            'form' => $editForm->createView(),
            'form2' => $passwdForm->createView()
        ]);
    }
}
