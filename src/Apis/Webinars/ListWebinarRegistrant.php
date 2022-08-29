<?php

namespace PhpZoomer\Apis\Webinars;

use PhpZoomer\Zoomer;

class ListWebinarRegistrant
{
    private $response;

    private Zoomer $zoomer;

    private $webinarId;

    public function __construct(Zoomer $zoomer, $webinarId)
    {
        $this->zoomer = $zoomer;
        $this->webinarId = $webinarId;

        $this->performTheRequest();
    }

    private function performTheRequest()
    {
        $response = $this->zoomer->getClient()->request('GET', "v2/webinars/{$this->webinarId}/registrants", [
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