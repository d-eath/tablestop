<?php
// Fichier : CatalogController.php
// Date : 2021-02-04
// Auteur : Davis Eath
// But : Gérer les requêtes de la route Catalogue (Index)

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CatalogController extends AbstractController
{
    /**
     * @Route("/", name="catalog")
     */
    public function index(Request $req): Response
    {
        $search_query = $req->query->get('q');
        $category_query = $req->query->get('c');

        return $this->render('catalog.html.twig', [
            'products' => $this->fetchProducts($search_query, $category_query),
            'categories' => $this->fetchCategories(),
            'search_query' => $search_query,
            'category_query' => $category_query
        ]);
    }

    /**
     * Route appelé par le contrôleur Produit par un transfert
     */
    public function product(Request $req, $product): Response
    {
        $search_query = $req->query->get('q');
        $category_query = $req->query->get('c');

        return $this->render('catalog.html.twig', [
            'products' => $this->fetchProducts($search_query, $category_query),
            'categories' => $this->fetchCategories(),
            'search_query' => $search_query,
            'category_query' => $category_query,
            'selected_product' => $product
        ]);
    }

    /**
     * Obtient tous les produits, optionnellement filtrés par recherche textuelle ou catégorie
     */
    private function fetchProducts($search, $category)
    {
        return $this->getDoctrine()->getRepository(Product::class)->findByFilter($search, $category);
    }

    /**
     * Obtient tous les catégories
     */
    private function fetchCategories()
    {
        return $this->getDoctrine()->getRepository(Category::class)->findAll();
    }
}
