<?php
/**
 * @author Gizem Sever <gizemsever68@gmail.com>
 */

namespace Gizemsever\LaravelPaytr\Direkt;

use Gizemsever\LaravelPaytr\PaytrClient;

class DPaymentVerification extends PaytrClient
{
    private $request;
    private string $merchantOid;
    private string $status;
    private float $totalAmount;
    private string $hash;
    private ?int $failedReasonCode;
    private ?string $failedReasonMessage;
    private ?int $testMode;
    private ?string $paymentType;
    private ?string $currency;
    private ?int $paymentAmount;
    private ?string $uToken;

    public function __construct($request)
    {
        $this->request = $request;
        $this->setup();
    }

    private function setup()
    {
        $this->merchantOid = $this->request->input('merchant_oid');
        $this->status = $this->request->input('status');
        $this->totalAmount = $this->request->input('total_amount');
        $this->hash = $this->request->input('hash');
        $this->failedReasonCode = $this->request->input('failed_reason_code', null);
        $this->failedReasonMessage = $this->request->input('failed_reason_message', null);
        $this->testMode = $this->request->input('test_mode', null);
        $this->paymentType = $this->request->input('payment_type');
        $this->currency = $this->request->input('currency');
        $this->paymentAmount = $this->request->input('payment_amount');
        $this->uToken = $this->request->input('utoken', null);
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getMerchantOid(): string
    {
        return $this->merchantOid;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return float
     */
    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return int|null
     */
    public function getFailedReasonCode(): ?int
    {
        return $this->failedReasonCode;
    }

    /**
     * @return string|null
     */
    public function getFailedReasonMessage(): ?string
    {
        return $this->failedReasonMessage;
    }

    /**
     * @return int|null
     */
    public function getTestMode(): ?int
    {
        return $this->testMode;
    }

    /**
     * @return string|null
     */
    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @return int
     */
    public function getPaymentAmount(): int
    {
        return $this->paymentAmount;
    }

    /**
     * @return string|null
     */
    public function getUToken(): ?string
    {
        return $this->uToken;
    }

    private function generateHash(): string
    {
        return $this->getMerchantOid() .
            $this->credentials['merchant_salt'] .
            $this->getStatus() .
            $this->getTotalAmount();
    }

    public function verifyRequest(): bool
    {
        $hash = $this->generateHash();
        $token = $this->generateToken($hash);
        return ($token === $this->getHash());
    }

    public function isSuccess(): bool
    {
        return ($this->status === 'success');
    }

    public function getProcessedResponse()
    {
        return response('OK', 200);
    }

}