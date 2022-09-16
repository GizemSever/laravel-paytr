<?php
/**
 * @author Gizem Sever <gizemsever68@gmail.com>
 */

namespace Gizemsever\LaravelPaytr;

use Gizemsever\LaravelPaytr\Payment\Basket;
use Gizemsever\LaravelPaytr\Payment\Payment;
use Gizemsever\LaravelPaytr\Payment\PaymentVerification;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class Paytr
{
    private $client;
    private $credentials;
    private $options;

    public function __construct(Client $client, array $credentials = [], array $options = [])
    {
        $this->client = $client;
        $this->credentials = $credentials;
        $this->options = $options;
    }

    public function createPayment(Payment $payment)
    {
        return $payment->setClient($this->client)
            ->setCredentials($this->credentials)
            ->setOptions($this->options)
            ->create();
    }

    public function payment()
    {
        return new Payment();
    }

    public function basket()
    {
        return new Basket();
    }

    public function paymentVerification(Request $request)
    {
        return new PaymentVerification($request);
    }
}