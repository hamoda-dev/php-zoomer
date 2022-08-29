<?php

namespace PhpZoomer;

use GuzzleHttp\Client;
use Carbon\Carbon;
use PhpZoomer\Exceptions\CredenialException;
use PhpZoomer\Exceptions\SaveCredenialException;
use PhpZoomer\Traits\ZoomerFileManger;

class Zoomer
{
    use ZoomerFileManger;

    /**
     * Zoom CLIENT_ID
     *
     * @var string
     */
    protected string $clientID;

    /**
     * Zoom CLIENT_SECRET
     *
     * @var string
     */
    protected string $clientSecret;

    /**
     * Redirect URI
     *
     * @var string
     */
    protected string $redirectUri = '';

    /**
     * Path for the credenial file
     *
     * @var string
     */
    protected string $credenialPath;

    /**
     * account ID
     *
     * @var string
     */
    protected string $accountID = '';

    /**
     * HTTP Client
     *
     * @var Client $client
     */
    protected Client $client;

    /**
     * Constructiong Zoom Client
     *
     * @param string $clientID
     * @param string $clientSecret
     * @param string $credenialPath
     */
    public function __construct(string $clientID, string $clientSecret, string $credenialPath)
    {
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
        $this->credenialPath = $credenialPath;
        $this->client = new Client(['base_uri' => 'https://api.zoom.us']);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setRedirectUri(string $uri): void
    {
        $this->redirectUri = $uri;
    }

    public function setAccountID(string $id): void
    {
        $this->accountID = $id;
    }

    /**
     * Genrate OAuth URL
     *
     * @return string
     */
    public function getOAuthUrl(): string
    {
        return "https://zoom.us/oauth/authorize?response_type=code&client_id=
                    {$this->clientID}&redirect_uri={$this->redirectUri}";
    }

    public function genrateToken(string $code): mixed
    {
        if ($this->redirectUri == '') {
            throw new CredenialException('Invalid redirect uri you need to set it: use set setRedirectUri($uri)');
        }

        $response = $this->client->request('POST', '/oauth/token', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode("$this->clientID:$this->clientSecret"),
            ],
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $this->redirectUri,
            ],
        ]);

        $response_token = $this->addCreatedAt($response);

        $this->storeCredenialInFile($response_token, $this->credenialPath);

        $this->validateCredenial($response_token);

        return $response_token;
    }

    public function refreshToken(): mixed
    {
        try {
            $response = $this->client->request('POST', '/oauth/token', [
                "headers" => [
                    "Authorization" => "Basic " . base64_encode($this->clientID . ':' . $this->clientSecret)
                ],
                'form_params' => [
                    "grant_type" => "refresh_token",
                    "refresh_token" => $this->getCredenialFromFile($this->credenialPath)['refresh_token']
                ],
            ]);

            $response_token = $this->addCreatedAt($response);

            $this->storeCredenialInFile($response_token, $this->credenialPath);

            $this->validateCredenial($response_token);

            return $response_token;
        } catch (\Exception $e) {
            throw new CredenialException("Can't refresh token.");
        }
    }

    public function serverToServerOAuth(): mixed
    {
        if ($this->accountID == '') {
            throw new CredenialException('Invalid accountID you need to set it: use set setAccountID($id)');
        }

        $response = $this->client->request('POST', "/oauth/token", [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode("$this->clientID:$this->clientSecret"),
            ],
            'form_params' => [
                'grant_type' => 'account_credentials',
                'account_id' => $this->accountID,
            ],
        ]);

        $response_token = $this->addCreatedAt($response);

        $this->storeCredenialInFile($response_token, $this->credenialPath);

        $this->validateCredenial($response_token);

        return $response_token;
    }

    public function getToken(): string
    {
        if (!$this->checkCredenialFileExists($this->credenialPath)) {
            throw new CredenialException("Credenial file not found");
        }

        $credenials = $this->getCredenialFromFile($this->credenialPath);

        if (!array_key_exists('access_token', $credenials) && !array_key_exists('created_at', $credenials)) {
            throw new CredenialException("Invalid Credenial Format");
        }

        $token_time = Carbon::create($credenials['created_at']);
        $time_now = Carbon::now();

        if (array_key_exists('refresh_token', $credenials)) { // normal oauth
            if ($time_now->diffInMinutes($token_time) >= 50) {
                $credenials = $this->refreshToken();
            }
        } else { // server-to-server oauth
            if ($time_now->diffInMinutes($token_time) >= 50) {
                $credenials = $this->serverToServerOAuth();
            }
        }

        return $credenials['access_token'];
    }

    /**
     * Validate credenials
     *
     * @param array <string, mixed> $response_token
     * @return void
     */
    private function validateCredenial(array $response_token): void
    {
        if (!$this->checkCredenialFileExists($this->credenialPath)) {
            throw new SaveCredenialException("Can't save credenial in this path: $this->credenialPath");
        }

        $savedToken = $this->getCredenialFromFile($this->credenialPath);

        if (!empty(array_diff($savedToken, $response_token))) { // checking reponse token and saved tokends are same
            throw new SaveCredenialException("Credenial dos't saved correctley");
        }
    }

    /**
     * add created at to credenials
     *
     * @param mixed $response
     * @return mixed
     */
    private function addCreatedAt(mixed $response): mixed
    {
        $response_token = json_decode($response->getBody()->getContents(), true);
        $response_token['created_at'] = Carbon::now()->toDateTimeString();

        return $response_token;
    }
}
