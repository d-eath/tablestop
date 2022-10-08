<?php
// Fichier : CustomerRepository.php
// Date : 2021-04-01
// Auteur : Davis Eath
// But : Interfacer avec la base de données pour traiter les comptes

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use RuntimeException;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /**
     * Obtient un client par son nom d'utilisateur
     */
    public function findByUsername($username)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.username = :username')->setParameter('username', $username);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
