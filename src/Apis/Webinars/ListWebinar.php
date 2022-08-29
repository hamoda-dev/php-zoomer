<?php

namespace PhpZoomer\Apis\Webinars;

use PhpZoomer\Zoomer;

class ListWebinar
{
    /**
     * @var array<string, mixed>
     */
    private array $response;

    private Zoomer $zoomer;

    private string $userId;

    public function __construct(Zoomer $zoomer, string $userId = 'me')
    {
        $this->zoomer = $zoomer;
        $this->userId = $userId;

        $this->performTheRequest();
    }

    private function performTheRequest(): void
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

    /**
     * @return array<string, mixed>
     */
    public function getResponse(): array
    {
        return $this->response ?? [];
    }
}
