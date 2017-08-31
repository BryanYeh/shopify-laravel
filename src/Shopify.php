<?php

namespace Bryanyeh\Shopify;

use GuzzleHttp\Client;
use Bryanyeh\Shopify\Exceptions\InvalidMethodRequestException;

class Shopify
{
    protected $key;
    protected $secret;
    protected $scopes;
    protected $shop;
    protected $client;
    protected $token;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->key = config('shopify.key');
        $this->secret = config('shopify.secret');
        $this->scopes = config('shopify.scopes');
    }

    /**
     * Initialize the shopify client
     *
     * @param string $url
     * @param string $access_token
     * @return void
     */
    public function init(string $url,string $access_token=null)
    {
        $shopUrl = parse_url($url);
        $shopUrl = $shopUrl['host'] ?? $shopUrl['path'];

        $this->shop = $shopUrl;
        $this->token = $access_token;

        return $this;
    }

    /**
     * Get the shopify oauth link
     *
     * @param string $redirect_url
     * @return string
     */
    public function install(string $redirect_url=''): string
    {
        $scope = implode(",", $this->scopes);
        $nonce = str_random(20);
        session(['nonce'=> $nonce]);
        return "https://{$this->shop}/admin/oauth/authorize?client_id={$this->key}&scope={$scope}&redirect_uri={$redirect_url}&state={$nonce}";
    }

    /**
     * Exchange the temp code for a perm access token
     *
     * @param string $code
     * @return void
     */
    public function getAccessToken(string $code)
    {
        $uri = "/admin/oauth/access_token";
        $payload = ["client_id" => $this->key, 'client_secret' => $this->secret, 'code' => $code];
        return $this->request('POST', $uri,$payload) ?? '';
    }


    public function __call($method, $args)
    {
        $method = strtoupper($method);
        $allowedMethods = ['POST','GET','PUT','DELETE'];

        if(!in_array($method,$allowedMethods)){
            throw new InvalidMethodRequestException();
        }
        return $this->request($method,trim($args[0]),$args[1] ?? []);
    }

    /**
     * Do request with Guzzle
     *
     * @param string $method
     * @param string $uri
     * @param array $payload
     * @return array
     */
    private function request(string $method, string $uri, array $payload): array
    {
        $response = $this->client->request(
            $method, 
            "https://{$this->shop}{$uri}",
            [
                'exceptions' => false,
                'json' => $payload,
                'headers' => $this->token ? ['X-Shopify-Access-Token' =>$this->token, 'Content-Type' => 'application/json'] : []
            ]
        );

        return array_merge([
            'statusCode' => $response->getStatusCode(),
            'reasonPhrase' => $response->getReasonPhrase(),
            'callLimit' => $response->hasHeader('HTTP_X_SHOPIFY_SHOP_API_CALL_LIMIT') ? $response->getHeaders()['HTTP_X_SHOPIFY_SHOP_API_CALL_LIMIT'][0] : '',
        ],json_decode($response->getBody(),true));
    }
}