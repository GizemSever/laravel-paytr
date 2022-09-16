<?php
/**
 * @author Gizem Sever <gizemsever68@gmail.com>
 */

namespace Gizemsever\LaravelPaytr\Payment;

use Gizemsever\LaravelPaytr\PaytrClient;
use Gizemsever\LaravelPaytr\PaytrResponse;

class Payment extends PaytrClient
{
    /**
     * @var string
     */
    private $userIp;
    /**
     * @var string
     */
    private $merchantOid;
    /**
     * @var string
     */
    private $email;
    /**
     * @var float
     */
    private $paymentAmount;
    /**
     * @var int
     */
    private $noInstallment;
    /**
     * @var int
     */
    private $maxInstallment;
    /**
     * @var string
     */
    private $userName;
    /**
     * @var string
     */
    private $userAddress;
    /**
     * @var string
     */
    private $userPhone;
    /**
     * @var string
     */
    private $successUrl;
    /**
     * @var string
     */
    private $failUrl;
    /**
     * @var bool
     */
    private $debugOn = false;

    /**
     * @var Basket
     */
    private $basket;

    /**
     * @var string
     */
    private $currency = Currency::TRY;

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserIp(): string
    {
        return $this->userIp;
    }

    /**
     * @param string $userIp
     */
    public function setUserIp(string $userIp)
    {
        $this->userIp = $userIp;
        return $this;
    }

    /**
     * @return string
     */
    public function getMerchantOid(): string
    {
        return $this->merchantOid;
    }

    /**
     * @param string $merchantOid
     */
    public function setMerchantOid(string $merchantOid)
    {
        $this->merchantOid = $merchantOid;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return float
     */
    public function getPaymentAmount(): float
    {
        return $this->paymentAmount;
    }

    /**
     * @param float $paymentAmount
     */
    public function setPaymentAmount(float $paymentAmount)
    {
        $this->paymentAmount = $paymentAmount;
        return $this;
    }

    /**
     * @return int
     */
    public function getNoInstallment(): int
    {
        return $this->noInstallment;
    }

    /**
     * @param int $noInstallment
     */
    public function setNoInstallment(int $noInstallment)
    {
        $this->noInstallment = $noInstallment;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxInstallment(): int
    {
        return $this->maxInstallment;
    }

    /**
     * @param int $maxInstallment
     */
    public function setMaxInstallment(int $maxInstallment)
    {
        $this->maxInstallment = $maxInstallment;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName(string $userName)
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserAddress(): string
    {
        return $this->userAddress;
    }

    /**
     * @param string $userAddress
     */
    public function setUserAddress(string $userAddress)
    {
        $this->userAddress = $userAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserPhone(): string
    {
        return $this->userPhone;
    }

    /**
     * @param string $userPhone
     */
    public function setUserPhone(string $userPhone)
    {
        $this->userPhone = $userPhone;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuccessUrl(): string
    {
        return $this->successUrl;
    }

    /**
     * @param string $successUrl
     */
    public function setSuccessUrl(string $successUrl)
    {
        $this->successUrl = $successUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getFailUrl(): string
    {
        return $this->failUrl;
    }

    /**
     * @param string $failUrl
     */
    public function setFailUrl(string $failUrl)
    {
        $this->failUrl = $failUrl;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDebugOn(): bool
    {
        return $this->debugOn;
    }

    /**
     * @param bool $debugOn
     */
    public function setDebugOn(bool $debugOn)
    {
        $this->debugOn = $debugOn;
        return $this;
    }

    /**
     * @return Basket
     */
    public function getBasket(): Basket
    {
        return $this->basket;
    }

    /**
     * @param Basket $basket
     * @return Payment
     */
    public function setBasket(Basket $basket)
    {
        $this->basket = $basket;
        return $this;
    }

    private function getHash()
    {
        return '' .
            $this->credentials['merchant_id'] .
            $this->getUserIp() .
            $this->getMerchantOid() .
            $this->getEmail() .
            $this->formattedPaymentAmount() .
            $this->basket->formatted() .
            $this->getNoInstallment() .
            $this->getMaxInstallment() .
            $this->getCurrency() .
            $this->options['test_mode'];
    }

    private function createPaymentToken()
    {
        $hash = $this->getHash();
        return base64_encode(hash_hmac('sha256', $hash . $this->credentials['merchant_salt'], $this->credentials['merchant_key'], true));
    }

    private function formattedPaymentAmount()
    {
        return $this->getPaymentAmount() * 100;
    }

    private function getBody()
    {
        $paymentToken = $this->createPaymentToken();
        return [
            'merchant_id' => $this->credentials['merchant_id'],
            'user_ip' => $this->getUserIp(),
            'merchant_oid' => $this->getMerchantOid(),
            'email' => $this->getEmail(),
            'payment_amount' => $this->formattedPaymentAmount(),
            'paytr_token' => $paymentToken,
            'user_basket' => $this->basket->formatted(),
            'debug_on' => $this->isDebugOn(),
            'no_installment' => $this->getNoInstallment(),
            'max_installment' => $this->getMaxInstallment(),
            'user_name' => $this->getUserName(),
            'user_address' => $this->getUserAddress(),
            'user_phone' => $this->getUserPhone(),
            'merchant_ok_url' => $this->options['success_url'],
            'merchant_fail_url' => $this->options['fail_url'],
            'currency' => $this->getCurrency(),
            'test_mode' => $this->options['test_mode'],
        ];
    }

    public function create()
    {
        $requestBody = $this->getBody();
        $response = $this->callApi('POST', 'odeme/api/get-token', $requestBody);
//        return $response->getBody();
//        return json_decode((string) $response->getBody());
        return new PaytrResponse(json_decode((string)$response->getBody(), true));
    }
}