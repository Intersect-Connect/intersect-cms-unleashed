<?php

namespace App\Repository;

use App\Entity\CmsNews;
use App\Entity\Hdv;
use App\Entity\ItemsGames;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HdvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hdv::class);
    }



    public function findAllItems()
    {
        // Permet la connexion à la base de données
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM hdv INNER JOIN items_games ON hdv.item_id = items_games.id";
        $query = $conn->prepare($sql);
        $query->execute();

        return $query->fetchAllAssociative();
    }
}
