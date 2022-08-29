<?php

namespace PhpZoomer\Apis\Webinars;

use PhpZoomer\Zoomer;

class CreateWebinar
{
    private $response;

    private array $params;

    private Zoomer $zoomer;

    private $userId;

    public function __construct(Zoomer $zoomer, array $params, $userId = 'me')
    {
        $this->zoomer = $zoomer;
        $this->params = $params;
        $this->userId = $userId;

        $this->performTheRequest();
    }

    private function performTheRequest()
    {
        $response = $this->zoomer->getClient()->request('POST', "v2/users/{$this->userId}/webinars", [
            'headers' => [
                'Authorization' => "Bearer {$this->zoomer->getToken()}",
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => $this->params,
        ]);

        $this->response = json_decode($response->getBody()->getContents(), true);
    }

    public function getResponse()
    {
        return $this->response ?? [];
    }
}