<?php

namespace App\Repository;

use App\Entity\Municipality;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Municipality>
 *
 * @method Municipality|null find($id, $lockMode = null, $lockVersion = null)
 * @method Municipality|null findOneBy(array $criteria, array $orderBy = null)
 * @method Municipality[]    findAll()
 * @method Municipality[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MunicipalityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Municipality::class);
    }

    public function getById($id): array
    {
        $municipality = $this->find($id);
        $result = array();

        $result['name'] = $municipality->getName();
        $result['slug'] = $municipality->getSlug();
        $result['latitude'] = $municipality->getLatitude();
        $result['longitude'] = $municipality->getLongitude();
        $result['province'] = $municipality->getProvince();

        return $result;
    }

    public function getNearestMunicipality($cardinal, $municipalities): array
    {
        switch($cardinal)
        {
            case 'n': $cardinalName = 'north'; break;
            case 's': $cardinalName = 'south'; break;
            case 'e': $cardinalName = 'east';  break;
            case 'w': $cardinalName = 'west';  break;
        }

        $nearestMunicipality = null;
        $municipalityIds = json_decode($municipalities);

        foreach ($municipalityIds as $municipalityId) 
        {
            $municipality = $this->find($municipalityId);

            if(is_null($nearestMunicipality)) 
            {
                $nearestMunicipality = $municipality;
            } 
            else 
            {
                switch($cardinal)
                {
                    case 'n': 
                    {
                        if ($nearestMunicipality->getLatitude() < $municipality->getLatitude()) 
                        {
                            $nearestMunicipality = $municipality;
                        }
                        break;
                    }

                    case 's': 
                    {
                        if ($nearestMunicipality->getLatitude() > $municipality->getLatitude()) 
                        {
                            $nearestMunicipality = $municipality;
                        }
                        break;
                    }

                    case 'e': 
                    {
                        if ($nearestMunicipality->getLongitude() > $municipality->getLongitude()) 
                        {
                            $nearestMunicipality = $municipality;
                        }
                        break;
                    }

                    case 'w': 
                    {
                        if ($nearestMunicipality->getLongitude() < $municipality->getLongitude()) 
                        {
                            $nearestMunicipality = $municipality;
                        }
                        break;
                    }
                }
            }
        }

        $result = array();

        $result['id'] = $nearestMunicipality->getId();
        $result['name'] = $nearestMunicipality->getName();
        $result['slug'] = $nearestMunicipality->getSlug();
        $result['latitude'] = $nearestMunicipality->getLatitude();
        $result['longitude'] = $nearestMunicipality->getLongitude();
        $result['province'] = $nearestMunicipality->getProvince();

        return [
            'cardinalPoint' => $cardinalName,
            'municipality' => $result,
        ];
    }

    public function getMunicipalities($municipalities): array
    {
        $municipalityIds = json_decode($municipalities);
        $result = array();

        foreach ($municipalityIds as $municipalityId) 
        {
            $municipality = $this->find($municipalityId);

            $result[$municipalityId]['name'] =      $municipality->getName();
            $result[$municipalityId]['slug'] =      $municipality->getSlug();
            $result[$municipalityId]['latitude'] =  $municipality->getLatitude();
            $result[$municipalityId]['longitude'] = $municipality->getLongitude();
            $result[$municipalityId]['province'] =  $municipality->getProvince();
        }
        
        return $result;
    }

    public function addMunicipality($name, $slug, $latitude, $longitude, $province): int
    {
        $municipality = new Municipality();

        $municipality->setName($name);
        $municipality->setSlug($slug);
        $municipality->setLatitude($latitude);
        $municipality->setLongitude($longitude);
        $municipality->setProvince($province);

        $this->_em->persist($municipality);
        $this->_em->flush();
    
        return $municipality->getId();
    }

    public function saveMunicipality($id, $name, $slug, $latitude, $longitude, $province): int
    {
        $municipality = $this->find($id);

        $municipality->setName($name);
        $municipality->setSlug($slug);
        $municipality->setLatitude($latitude);
        $municipality->setLongitude($longitude);
        $municipality->setProvince($province);

        $this->_em->persist($municipality);
        $this->_em->flush();
    
        return $municipality->getId();
    }

    public function deleteMunicipality($id): bool
    {
        $municipality = $this->find($id);

        $this->_em->remove($municipality);
        $this->_em->flush();
    
        return true;
    }
}
