<?php

namespace App\Repository;

use App\Entity\Province;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Province>
 *
 * @method Province|null find($id, $lockMode = null, $lockVersion = null)
 * @method Province|null findOneBy(array $criteria, array $orderBy = null)
 * @method Province[]    findAll()
 * @method Province[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProvinceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Province::class);
    }

    public function getProvincesPopulation($ids): float
    {
        //Se que se podría hacer en una sola consulta pero por sencillez y claridad lo he hecho en 2 diferentes
        $sql = $this->createQueryBuilder('s')
            ->select('SUM(s.population) AS total1')
            ->where('s.id IN (:IDS)')->setParameter('IDS', json_decode($ids))
            ->getQuery()->getResult();

        $sql2 = $this->createQueryBuilder('t')
            ->select('SUM(t.population) AS total2')
            ->getQuery()->getResult();

        $result = 100*$sql[0]['total1']/$sql2[0]['total2'];
        
        return $result;
    }
}
