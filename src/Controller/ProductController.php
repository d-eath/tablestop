<?php
// Fichier : ProductController.php
// Date : 2021-02-04
// Auteur : Davis Eath
// But : Gérer les requêtes de la route Produit

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products/{id}", name="product")
     */
    public function index(Request $req, $id): Response
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        if (!$product)
        {
            throw new NotFoundHttpException('Le produit avec l\'identifiant donné n\'existe pas');
        }

        if (!$req->query->has('modal-render-from-catalog'))
        {
            return $this->forward('App\Controller\CatalogController::product', [
                'req' => $req,
                'product' => $id
            ]);
        }

        return $this->render('product.html.twig', [
            'product' => $product
        ]);
    }
}
