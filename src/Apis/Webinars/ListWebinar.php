<?php

namespace PhpZoomer\Apis\Webinars;

use PhpZoomer\Zoomer;

class ListWebinar
{
    private $response;

    private Zoomer $zoomer;

    private $userId;

    public function __construct(Zoomer $zoomer, $userId = 'me')
    {
        $this->zoomer = $zoomer;
        $this->userId = $userId;

        $this->performTheRequest();
    }

    private function performTheRequest()
    {
        $response = $this->zoomer->getClient()->request('GET', "v2/users/{$this->userId}/webinars", [
            'headers' => [
                'Authorization' => "Bearer {$this->zoomer->getToken()}",
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);

        $this->response = json_decode($response->getBody()->getContents(), true);
    }

    public function getResponse()
    {
        return $this->response ?? [];
    }
}