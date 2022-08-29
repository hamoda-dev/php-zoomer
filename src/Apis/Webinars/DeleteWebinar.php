<?php

namespace PhpZoomer\Apis\Webinars;

use PhpZoomer\Zoomer;

class DeleteWebinar
{
    /**
     * @var array<string, mixed>
     */
    private array $response;

    private Zoomer $zoomer;

    private string $webinarId;

    public function __construct(Zoomer $zoomer, string $webinarId)
    {
        $this->zoomer = $zoomer;
        $this->webinarId = $webinarId;

        $this->performTheRequest();
    }

    private function performTheRequest(): void
    {
        $response = $this->zoomer->getClient()->request('DELETE', "v2/webinars/{$this->webinarId}", [
            'headers' => [
                'Authorization' => "Bearer {$this->zoomer->getToken()}",
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
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
