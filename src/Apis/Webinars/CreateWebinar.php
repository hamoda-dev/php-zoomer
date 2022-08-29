<?php

namespace PhpZoomer\Apis\Webinars;

use PhpZoomer\Zoomer;

class CreateWebinar
{
    /**
     * @var array<string, mixed>
     */
    private array $response;

    /**
     * @var array<string, mixed>
     */
    private array $params;

    private Zoomer $zoomer;

    private string $userId;

    /**
     * @param Zoomer $zoomer
     * @param array<string, mixed> $params
     * @param string $userId
     */
    public function __construct(Zoomer $zoomer, array $params, string $userId = 'me')
    {
        $this->zoomer = $zoomer;
        $this->params = $params;
        $this->userId = $userId;

        $this->performTheRequest();
    }

    private function performTheRequest(): void
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

    /**
     * @return array<string, mixed>
     */
    public function getResponse(): array
    {
        return $this->response ?? [];
    }
}
