<?php
/**
 * @author Gizem Sever <gizemsever68@gmail.com>
 */

namespace Gizemsever\LaravelPaytr;

use GuzzleHttp\ClientInterface;

class PaytrClient
{
    /**
     * @var ClientInterface
     */
    protected $client;
    protected $credentials = [];
    protected $options = [];

    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
        return $this;
    }

    public function setCredentials(array $credentials)
    {
        $this->credentials = $credentials;
        return $this;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    protected function generateToken(string $hash): string
    {
        return base64_encode(hash_hmac('sha256', $hash, $this->credentials['merchant_key'], true));
    }

    protected function callApi(string $method, string $url, $params = null, $headers = null)
    {
        $options = [];
        if ($headers) {
            $options['headers'] = $headers;
        }
        if ($params) {
            $requestParams = collect(array_keys($params));
            $options['multipart'] = $requestParams->map(function ($key) use ($params) {
                return [
                    'name' => $key,
                    'contents' => $params[$key]
                ];
            })->toArray();
        }
        $options['timeout'] = $this->options['timeout'];
        return $this->client->request($method, $this->options['base_uri'] . '/' . $url, $options);
    }
}