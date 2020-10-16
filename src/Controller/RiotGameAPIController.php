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

class RiotGameAPIController extends AbstractController
{
    private $client;
    private $https = "https://";
    private $baseurl = ".api.riotgames.com/lol/";
    private $endPointGetUserInfoBySummonerName = "summoner/v4/summoners/by-name/";
    private $endPointGetMatchList = "match/v4/matchlists/by-account/";
    private $endPointGetMatchInfo = "match/v4/matches/";
    private $token = "";

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->token = $_ENV['RIOT_API_KEY'];
    }

    /**
     * @Route("/{region}/riot/getHistoryMatchList/{summonerName}", name="getHistoryMatchList", methods={"GET"} )
     */
    public function getHistoryMatchList($region, $summonerName) {
        $accountID = $this->getAccountID($region, $summonerName);
        $url = $this->https.$region.$this->baseurl.$this->endPointGetMatchList.$accountID;
        $cb = $this->client->request(
            'GET',
            $url,
            [
                'headers' => [
                    'X-Riot-Token' => $this->token
                ]
            ]
        );
        $lastMatch = json_decode($cb->getContent(),true);
        $result = array_slice($lastMatch["matches"],0,20);

        $entityManager = $this->getDoctrine()->getManager();

        $history = $entityManager
        ->getRepository(History::class)
        ->findOneBy(array(
            'SummonerName'=> $summonerName,
            'Region'=> $region,
        ));

        if (!$history) {
            $history = new History();
        }

        $history->setData($lastMatch);
        $history->setSummonerName($summonerName);
        $history->setRegion($region);
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($history);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $response = new JsonResponse();
        $response->setContent(json_encode($result));
        return $response;
    }

    // public function getLast20Match($region, $summonerName) {
    //     $lastMatch = $this->getHistoryMatchList($region, $summonerName);
    //     $lastMatch = json_decode($lastMatch->getContent(),true);
    //     $result = array_slice($lastMatch["matches"],0,20);

    //     $response = new JsonResponse();
    //     $response->setContent($lastMatch);
    //     return $response;
    //     //construction JSON
    //     $tmatch = [];
    //     $totalMatch = [
    //         "matches" => $tmatch
    //     ];

    //     //TO DO GET MATCH
    //     foreach ($result as $match) {
    //         $match = $this->getInfoMatchByID($region, $match["gameId"]);
    //         $match = json_decode($match->getContent(),true);
    //         array_push($totalMatch["matches"],$match);
    //     }
    //     $normalizer = new ObjectNormalizer();
    //     $serializer = new Serializer([$normalizer], [new JsonEncoder()]);

    //     $entityManager = $this->getDoctrine()->getManager();

    //     $history = $entityManager
    //     ->getRepository(History::class)
    //     ->findOneBy(array(
    //         'SummonerName'=> $summonerName,
    //         'Region'=> $region,
    //     ));

    //     if (!$history) {
    //         $history = new History();
    //     }

    //     $history->setData($totalMatch);
    //     $history->setSummonerName($summonerName);
    //     $history->setRegion($region);
    //     // tell Doctrine you want to (eventually) save the Product (no queries yet)
    //     $entityManager->persist($history);

    //     // actually executes the queries (i.e. the INSERT query)
    //     $entityManager->flush();
    //     return JsonResponse::fromJsonString($serializer->serialize($totalMatch, 'json'));
    // }

    /**
     * @Route("/{region}/riot/getHistoryMatch/{idMatch}", name="getHistoryMatch", methods={"GET"} )
     */
    public function getInfoMatchByID($region, $idMatch){
        $url = $this->https.$region. $this->baseurl.$this->endPointGetMatchInfo.$idMatch;
        $cb = $this->client->request(
            'GET',
            $url,
            [
                'headers' => [
                    'X-Riot-Token' => $this->token
                ]
            ]
        );


        $entityManager = $this->getDoctrine()->getManager();

        $match = $entityManager
        ->getRepository(MatchHistory::class)
        ->findOneBy(array(
            'IdMatch'=> $idMatch,
            'Region'=> $region,
        ));

        if (!$match) {
            $match = new MatchHistory();
        }

        $matchData = json_decode($cb->getContent(),true);

        $match->setData($matchData);
        $match->setIdMatch($idMatch);
        $match->setRegion($region);
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($match);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $response = new JsonResponse();
        $response->setContent($cb->getContent());
        return $response;
    }

    private function getAccountID($region, $summonerName) {
        $url = $this->https.$region. $this->baseurl.$this->endPointGetUserInfoBySummonerName.$summonerName;
        $response = $this->client->request(
            'GET',
            $url,
            [
                'headers' => [
                    'X-Riot-Token' => $this->token
                ]
            ]
        );
        $responseContent =json_decode($response->getContent());
        return $responseContent->accountId;
    }
}