<?php
/**
 * @author Gizem Sever <gizemsever68@gmail.com>
 */

namespace Gizemsever\LaravelPaytr\Direkt;

use Gizemsever\LaravelPaytr\PaytrClient;
use Gizemsever\LaravelPaytr\PaytrResponse;

class Installment extends PaytrClient
{
    private ?string $requestId;

    /**
     * @return string|null
     */
    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    /**
     * @param string|null $requestId
     * @return Installment
     */
    public function setRequestId(?string $requestId): static
    {
        $this->requestId = $requestId;
        return $this;
    }

    private function getHash(): string
    {
        return '' .
            $this->credentials['merchant_id'] .
            $this->getRequestId() .
            $this->credentials['merchant_salt'];
    }

    public function get(): PaytrResponse
    {
        $hash = $this->getHash();
        $token = $this->generateToken($hash);
        $body = [
            'merchant_id' => $this->credentials['merchant_id'],
            'request_id' => $this->getRequestId(),
            'paytr_token' => $token,
        ];

        $response = $this->callApi('POST', 'odeme/taksit-oranlari', $body);
        return new PaytrResponse(json_decode((string)$response->getBody(), true));

    }
}