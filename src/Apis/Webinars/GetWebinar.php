<?php

namespace PhpZoomer\Apis\Webinars;

use Exception;
use PhpZoomer\Zoomer;

class GetWebinar
{
    /**
     * @var array<string, mixed>
     */
    private array $response = [];

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
        try {
            $response = $this->zoomer->getClient()->request('GET', "v2/webinars/{$this->webinarId}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->zoomer->getToken()}",
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);
    
            $this->response = json_decode($response->getBody()->getContents(), true);
        } catch (Exception $e) {
            $this->response = [];
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    public function isExist()
    {
        return ($this->response != []);
    }
}
