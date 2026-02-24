<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WebsiteChecker
{
    public function __construct(
        private HttpClientInterface $client
    ) {}

    public function check(string $url): array
    {
        try {
            $response = $this->client->request('GET', $url);

            $statusCode = $response->getStatusCode();
            $isUp = $statusCode === 200;

            return [
                'status' => $statusCode,
                'isUp' => $isUp
            ];
        } catch (\Exception $e) {
            return [
                'status' => null,
                'isUp' => false
            ];
        }
    }
}
