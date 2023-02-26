<?php

namespace App\Controller;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Municipality;
use App\Entity\Province;


#[Route('/api', name: 'api_')]
class DefaultController extends AbstractController
{
    private EntityManagerInterface $_em;

    public function __construct(private ManagerRegistry $registry)
    {
        $this->_em = $this->registry->getManager();
    }

    /**
     * @throws Exception
     */
    #[Route('/municipality/{cardinal}', name: 'nearestMunicipalityToCardinal', requirements: ['cardinal' => 'n|s|e|w'], methods: ['GET'])]
    public function getNearestMunicipalityToCardinal(Request $request): JsonResponse
    {
        /* Example: http://localhost:8080/api/municipality/n?municipalities=[1,2] */

        $result = $this->get('doctrine')->getRepository(Municipality::class)->getNearestMunicipality($request->get('cardinal'), $request->get('municipalities'));

        return $this->json($result);
    }

    /**
     * @throws Exception
     */
    #[Route('/municipality/getMunicipality/{id}', name: 'getMunicipality', requirements: ['id' => '[0-9]+'], methods: ['GET'])]
    public function getMunicipality(Request $request): JsonResponse
    {
        /* Example: http://localhost:8080/api/municipality/getMunicipality/1 */

        $municipality = $this->get('doctrine')->getRepository(Municipality::class)->getById($request->get('id'));

        return $this->json([
            'municipality' => $municipality,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/municipality/getMunicipalities', name: 'getMunicipalities', methods: ['GET'])]
    public function getMunicipalities(Request $request): JsonResponse
    {
        /* Example: http://localhost:8080/api/municipality/getMunicipalities?ids=[1,2] */

        $result = $this->get('doctrine')->getRepository(Municipality::class)->getMunicipalities($request->get('ids'));

        return $this->json($result);
    }

    /**
     * @throws Exception
     */
    #[Route('/municipality/addMunicipality', name: 'addMunicipality', methods: ['POST'])]
    public function addMunicipality(Request $request): JsonResponse
    {
        /* Example: http://localhost:8080/api/municipality/addMunicipality?province_id=49&slug=cascajosa-del-cebollo&name=Cascajosa del Cebollo&latitude=5.44442&longitude=4.555552 */

        $province = $this->get('doctrine')->getRepository(Province::class)->find($request->get('province_id'));

        $inserted_id = $this->get('doctrine')->getRepository(Municipality::class)->addMunicipality(
            $request->get('name'), 
            $request->get('slug'), 
            $request->get('latitude'), 
            $request->get('longitude'), 
            $province);

        return $this->json([
            'isError' => false,
            'responseMsg' => "Municipality inserted successfully",
            'responseData' => $inserted_id,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/municipality/saveMunicipality/{id}', name: 'saveMunicipality', requirements: ['id' => '[0-9]+'], methods: ['PUT'])]
    public function saveMunicipality(Request $request): JsonResponse
    {
        /* Example: http://localhost:8080/api/municipality/saveMunicipality/8120?province_id=49&slug=cascajosa-del-cebollo&name=Cascajosa del Cebollo&latitude=5.44442&longitude=4.555552 */

        $province = $this->get('doctrine')->getRepository(Province::class)->find($request->get('province_id'));

        $updated_id = $this->get('doctrine')->getRepository(Municipality::class)->saveMunicipality(
            $request->get('id'),
            $request->get('name'), 
            $request->get('slug'), 
            $request->get('latitude'), 
            $request->get('longitude'), 
            $province);

        return $this->json([
            'isError' => false,
            'responseMsg' => "Municipality updated successfully",
            'responseData' => $updated_id,
        ]);  
    }

    
    /**
     * @throws Exception
     */
    #[Route('/municipality/deleteMunicipality/{id}', name: 'deleteMunicipality', requirements: ['id' => '[0-9]+'], methods: ['DELETE'])]
    public function deleteMunicipality(Request $request): JsonResponse
    {
        /* Example: http://localhost:8080/api/municipality/deleteMunicipality/8120 */

        $result = $this->get('doctrine')->getRepository(Municipality::class)->deleteMunicipality($request->get('id'));

        if($result)
        {
            return $this->json([
                'isError' => false,
                'responseMsg' => "Municipality deleted successfully",
            ]);
        }
        else
        {
            return $this->json([
                'isError' => true,
                'responseMsg' => "Municipality delete failed",
            ]);
        }
    }

    /**
     * @throws Exception
     */
    #[Route('/province/getProvincesPopulation', name: 'getProvincesPopulation', methods: ['GET'])]
    public function getProvincesPopulation(Request $request): JsonResponse
    {
        /* Example: http://localhost:8080/api/province/getProvincesPopulation?ids=[1,2] */

        $population = $this->get('doctrine')->getRepository(Province::class)->getProvincesPopulation($request->get('ids'));

        return $this->json([
            'population' => number_format($population, 2, '.', '').'%',
        ]);
    }

}
