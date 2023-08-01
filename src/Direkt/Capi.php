<?php
/**
 * @author Gizem Sever <gizemsever68@gmail.com>
 */

namespace Gizemsever\LaravelPaytr\Direkt;

use Gizemsever\LaravelPaytr\PaytrClient;
use Gizemsever\LaravelPaytr\PaytrResponse;

class Capi extends PaytrClient
{
    private ?string $uToken;

    /**
     * @return string|null
     */
    public function getUToken(): ?string
    {
        return $this->uToken;
    }

    /**
     * @param string|null $uToken
     * @return Capi
     */
    public function setUToken(?string $uToken): static
    {
        $this->uToken = $uToken;
        return $this;
    }

    public function getList()
    {
        $hash = $this->getUToken() . $this->credentials['merchant_salt'];
        $token = $this->generateToken($hash);

        $body = [
            'merchant_id' => $this->credentials['merchant_id'],
            'utoken' => $this->getUToken(),
            'paytr_token' => $token,
        ];
        $response = $this->callApi('POST', 'odeme/capi/list', $body);

        return new PaytrResponse(json_decode((string)$response->getBody(), true));
    }
}