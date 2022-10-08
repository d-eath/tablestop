<?php
// Fichier : ProductRepository.php
// Date : 2021-02-04
// Auteur : Davis Eath
// But : Interfacer avec la base de données pour traiter les produits

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use RuntimeException;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Obtient tous les produits, optionnellement filtrés par recherche textuelle ou catégorie
     */
    public function findByFilter($search, $category)
    {
        $qb = $this->createQueryBuilder('p');

        if (!is_null($search))
        {
            $qb->andWhere('p.title LIKE :title')->setParameter('title', "%$search%")
                ->orWhere('p.description LIKE :desc')->setParameter('desc', "%$search%");
        }

        if (!is_null($category))
        {
            $qb->andWhere('p.category = :category')->setParameter('category', $category);
        }

        return $qb->getQuery()->execute();
    }

    /**
     * Obtient tous les produits étant en dessous du seuil minimum
     */
    public function findBelowThreshold()
    {
        return $this->createQueryBuilder('p')
            ->where('p.inventoryStock < p.minRestock')
            ->getQuery()
            ->execute();
    }
}
