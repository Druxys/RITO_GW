<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use App\Entity\History;
use App\Entity\MatchHistory;
use Doctrine\ORM\EntityManagerInterface;

class PasserelleAPIController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/{region}/passerelle/getHistoryMatchList/{summonerName}", name="PasserelleGetHistoryMatchList", methods={"GET"} )
     */
    public function getHistoryMatchList($region, $summonerName) {
        $entityManager = $this->getDoctrine()->getManager();
        $history = $entityManager
        ->getRepository(History::class)
        ->findOneBy(array(
            'SummonerName'=> $summonerName,
            'Region'=> $region,
        ));

        if (!$history) {
            $history = ["code" => 404, "message" => "Not Found"];
        }else{
            $history = $history->getData();
        }
        return $this->serializeJSON($history);
    }

    /**
     * @Route("/{region}/passerelle/getHistoryMatch/{idMatch}", name="PasserelleGetHistoryMatch", methods={"GET"} )
     */
    public function getInfoMatchByID($region, $idMatch){
        $entityManager = $this->getDoctrine()->getManager();
        $match = $entityManager
        ->getRepository(MatchHistory::class)
        ->findOneBy(array(
            'IdMatch'=> $idMatch,
            'Region'=> $region,
        ));

        if (!$match) {
            $match = ["code" => 404, "message" => "Not Found"];
        }else{
            $match = $match->getData();
        }
        return $this->serializeJSON($match);
    }

    protected function serializeJSON($data){

        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);
        return JsonResponse::fromJsonString($serializer->serialize($data, 'json'));
    }

}