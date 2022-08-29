<?php

namespace PhpZoomer\Apis\Webinars;

use PhpZoomer\Zoomer;

class AddWebinarRegistrant
{
    private $response;

    private array $params;

    private Zoomer $zoomer;

    private $webinarId;

    public function __construct(Zoomer $zoomer, $webinarId, array $params)
    {
        $this->zoomer = $zoomer;
        $this->params = $params;
        $this->webinarId = $webinarId;

        $this->performTheRequest();
    }

    private function performTheRequest()
    {
        $response = $this->zoomer->getClient()->request('POST', "v2/webinars/{$this->webinarId}/registrants", [
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