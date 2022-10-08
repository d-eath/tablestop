<?php
// Fichier : AdminCategoriesController.php
// Date : 2021-05-16
// Auteur : Davis Eath
// But : Gérer les requêtes des pages admin de catégories

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoriesController extends AbstractController
{
    /**
     * @Route("/admin/categories", name="admin_categories")
     */
    public function index(Request $req): Response
    {
        // vérification de connexion en tant qu'admin
        $loginRes = $this->forward('App\Controller\AdminController::generateLoginResponse');

        if (!$loginRes->isEmpty())
        {
            return $loginRes;
        }

        if ($req->isMethod('POST'))
        {
            return $this->action($req);
        }

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('admin/categories.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/categories/new", name="admin_categories_new")
     */
    public function new(Request $req): Response
    {
        // vérification de connexion en tant qu'admin
        $loginRes = $this->forward('App\Controller\AdminController::generateLoginResponse');

        if (!$loginRes->isEmpty())
        {
            return $loginRes;
        }

        // POST = utilisateur à créé une nouvelle catégorie
        if ($req->isMethod('POST'))
        {
            $name = $req->request->get('name');

            if (strlen($name) == 0 || strlen($name) > 50)
            {
                $this->addFlash('error', 'Le nom de la catégorie doit être entre 1 et 50 caractères.');

                return $this->redirectToRoute('admin_categories_new');
            }
            
            $category = new Category();

            $category->setName($name);

            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'La catégorie a été ajoutée avec succès.');

            return $this->redirectToRoute('admin_categories');
        }

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('admin/categories_new.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * Fonction qui gère la modification des catégories
     */
    private function action(Request $req): Response
    {
        $action = $req->request->get('action');
        $id = $req->request->get('id');
        $name = $req->request->get('name');

        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        if (is_null($category))
        {
            throw new NotFoundHttpException('La catégorie avec l\'identifiant donné n\'existe pas');
        }

        $em = $this->getDoctrine()->getManager();

        if ($action == 'edit')
        {
            if (strlen($name) == 0 || strlen($name) > 50)
            {
                throw new BadRequestException('Longueur du nom trop courte ou trop longue');
            }

            $category->setName($name);

            $this->addFlash('success', 'La catégorie a été modifiée avec succès.');
        }
        else if ($action == 'delete')
        {
            if (count($category->getProducts()) > 0)
            {
                throw new BadRequestException('Impossible de supprimer une catégorie ayant des produits');
            }

            $em->remove($category);

            $this->addFlash('success', 'La catégorie a été supprimée avec succès.');
        }
        else
        {
            throw new BadRequestException('Action invalide');
        }

        $em->flush();

        return $this->redirectToRoute('admin_categories');
    }
}
