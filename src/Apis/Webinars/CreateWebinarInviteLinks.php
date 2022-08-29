<?php

namespace PhpZoomer\Apis\Webinars;

use PhpZoomer\Zoomer;

class CreateWebinarInviteLinks
{
    private $response;

    private Zoomer $zoomer;

    private $webinarId;

    private array $params;

    public function __construct(Zoomer $zoomer, $webinarId, array $params)
    {
        $this->zoomer = $zoomer;
        $this->webinarId = $webinarId;
        $this->params = $params;

        $this->performTheRequest();
    }

    private function performTheRequest()
    {
        $response = $this->zoomer->getClient()->request('POST', "v2/webinars/{$this->webinarId}/invite_links", [
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