<?php

namespace PhpZoomer\Apis\Webinars;

use PhpZoomer\Zoomer;

class DeleteWebinarRegistrant
{
    private $response;

    private Zoomer $zoomer;

    private $webinarId;

    private $registrantId;

    public function __construct(Zoomer $zoomer, $webinarId, $registrantId)
    {
        $this->zoomer = $zoomer;
        $this->webinarId = $webinarId;
        $this->registrantId = $registrantId;

        $this->performTheRequest();
    }

    private function performTheRequest()
    {
        $response = $this->zoomer->getClient()->request('DELETE', "v2/webinars/{$this->webinarId}/registrants/{$this->registrantId}", [
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