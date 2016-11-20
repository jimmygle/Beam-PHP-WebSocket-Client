<?php

require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;

class Authentication
{

    protected $client;
    public $jwtToken;

    /**
     * Creates HTTP client object.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://beam.pro',
            'cookies' => true
        ]);
    }

    /**
     * Logs user in to get auth'd session cookie.
     * @param $username
     * @param $password
     */
    public function login($username, $password)
    {
        $this->client->request('POST', '/api/v1/users/login', [
            'headers' => [
                'Accept' => 'application/json',
                'Origin' => 'https://beam.pro',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36',
                'Content-Type' => 'application/json',
                'Referer' => 'https://beam.pro/users/login'
            ],
            'json' => [
                'username' => $username,
                'password' => $password
            ]
        ]);

        $this->jwtAuth();
    }

    /**
     * Authenticates with JWT.
     */
    public function jwtAuth()
    {
        $response = $this->client->request('POST', '/api/v1/jwt/authorize', [
            'headers' => [
                'Referer' => 'https://beam.pro/users/login'
            ]
        ]);

        $jwtHeader = $response->getHeader('x-jwt');
        $this->jwtToken = array_shift($jwtHeader);
    }

}