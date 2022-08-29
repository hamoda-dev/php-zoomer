<?php

namespace PhpZoomer\Apis\Webinars;

use PhpZoomer\Zoomer;

class DeleteWebinarRegistrant
{
    /**
     * @var array<string, mixed>
     */
    private array $response;

    private Zoomer $zoomer;

    private string $webinarId;

    private string $registrantId;

    public function __construct(Zoomer $zoomer, string $webinarId, string $registrantId)
    {
        $this->zoomer = $zoomer;
        $this->webinarId = $webinarId;
        $this->registrantId = $registrantId;

        $this->performTheRequest();
    }

    private function performTheRequest(): void
    {
        $response = $this->zoomer->getClient()
            ->request('DELETE', "v2/webinars/{$this->webinarId}/registrants/{$this->registrantId}", [
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
