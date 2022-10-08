<?php
// Fichier : AdminProductsController.php
// Date : 2021-05-16
// Auteur : Davis Eath
// But : Gérer les requêtes des pages admin de produits

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductImageType;
use App\Form\ProductType;
use App\Util\ProductImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductsController extends AbstractController
{
    /**
     * @Route("/admin/products", name="admin_products")
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

        return $this->render('admin/products.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/admin/products/new", name="admin_products_new")
     */
    public function new(Request $req): Response
    {
        // vérification de connexion en tant qu'admin
        $loginRes = $this->forward('App\Controller\AdminController::generateLoginResponse');

        if (!$loginRes->isEmpty())
        {
            return $loginRes;
        }

        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        if ($req->isMethod('POST'))
        {
            $form->handleRequest($req);

            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                    
                $em->persist($product);
                $em->flush();

                $this->addFlash('success', 'Le produit a été ajouté avec succès.');

                return $this->redirectToRoute('admin_products');
            }
            else
            {
                $this->addFlash('error', 'Une ou plusieurs erreurs se sont produites. Veuillez réessayer.');
            }
        }

        return $this->render('admin/products_new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/products/edit/{id}", name="admin_products_edit")
     */
    public function edit(Request $req, $id): Response
    {
        // vérification de connexion en tant qu'admin
        $loginRes = $this->forward('App\Controller\AdminController::generateLoginResponse');

        if (!$loginRes->isEmpty())
        {
            return $loginRes;
        }

        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        if (!$product)
        {
            throw new NotFoundHttpException('Le produit avec l\'identifiant donné n\'existe pas');
        }

        $form = $this->createForm(ProductType::class, $product);

        if ($req->isMethod('POST'))
        {
            $form->handleRequest($req);

            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                    
                $em->persist($product);
                $em->flush();

                $this->addFlash('success', 'Le produit a été modifié avec succès.');

                return $this->redirectToRoute('admin_products');
            }
            else
            {
                $this->addFlash('error', 'Une ou plusieurs erreurs se sont produites. Veuillez réessayer.');
            }
        }

        return $this->render('admin/products_edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/products/edit/{id}/images", name="admin_products_edit_images")
     */
    public function editImages(Request $req, $id): Response
    {
        // vérification de connexion en tant qu'admin
        $loginRes = $this->forward('App\Controller\AdminController::generateLoginResponse');

        if (!$loginRes->isEmpty())
        {
            return $loginRes;
        }

        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        if (!$product)
        {
            throw new NotFoundHttpException('Le produit avec l\'identifiant donné n\'existe pas');
        }

        $images = new ProductImageManager($product->getId(), $this->getParameter('kernel.project_dir'));

        $form = $this->createForm(ProductImageType::class, $images);

        if ($req->isMethod('POST'))
        {
            $form->handleRequest($req);

            if ($form->isValid())
            {
                $images->updateImages();

                $this->addFlash('success', 'Les images du produit ont été mises à jour.');

                return $this->redirectToRoute('admin_products');
            }
            else
            {
                $this->addFlash('error', 'Une ou plusieurs erreurs se sont produites. Veuillez réessayer.');
            }
        }

        return $this->render('admin/products_edit_images.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
