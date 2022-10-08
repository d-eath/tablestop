<?php
// Fichier : OrderRepository.php
// Date : 2021-04-23
// Auteur : Davis Eath
// But : Interfacer avec la base de données pour traiter les commandes

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use RuntimeException;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * Obtient tous les commandes d'un client en ordre chronologique inverse
     */
    public function findByCustomerId($customerId)
    {
        return $this->createQueryBuilder('o')
            ->where('o.customer = :customerId')
            ->setParameter('customerId', $customerId)
            ->orderBy('o.date', 'DESC')
            ->getQuery()
            ->execute();
    }

    /**
     * Obtient tous les commandes de la boutique en ordre chronologique inverse
     */
    public function findAllDesc()
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.date', 'DESC')
            ->getQuery()
            ->execute();
    }
}
