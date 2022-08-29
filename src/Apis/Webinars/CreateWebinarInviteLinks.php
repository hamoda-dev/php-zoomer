<?php

namespace PhpZoomer\Apis\Webinars;

use PhpZoomer\Zoomer;

class CreateWebinarInviteLinks
{
    /**
     * @var array<string, mixed>
     */
    private array $response;

    private Zoomer $zoomer;

    private string $webinarId;

    /**
     * @var array<string, mixed>
     */
    private array $params;

    /**
     * @param Zoomer $zoomer
     * @param string $webinarId
     * @param array<string, mixed> $params
     */
    public function __construct(Zoomer $zoomer, string $webinarId, array $params)
    {
        $this->zoomer = $zoomer;
        $this->webinarId = $webinarId;
        $this->params = $params;

        $this->performTheRequest();
    }

    private function performTheRequest(): void
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

    /**
     * @return array<string, mixed>
     */
    public function getResponse(): array
    {
        return $this->response ?? [];
    }
}
