<?php

namespace App\Service;

use App\Entity\Website;
use App\Entity\WebsiteCheck;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WebsiteChecker
{
    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $em
    ) {}

    public function checkWebsite(Website $website): void
    {
        try {
            $response = $this->client->request('GET', $website->getUrl(), [
                'timeout' => 5,
                'verify_peer' => false,
    'verify_host' => false,
            ]);
            $statusCode = $response->getStatusCode();
            $isUp = ($statusCode >= 200 && $statusCode < 300);
        } catch (\Exception $e) {
            $statusCode = 0;
            $isUp = false;
        }

        $check = new WebsiteCheck();
        $check->setWebsite($website);
        $check->setStatus($statusCode);
        $check->setIsUp($isUp);
        $check->setCheckedAt(new \DateTime());
        $this->em->persist($check);

        $website->setIsUp($isUp);
        $website->setLastStatus($statusCode);

        $this->em->flush();
    }

}
