<?php
/**
 * @author Gizem Sever <gizemsever68@gmail.com>
 */

namespace Gizemsever\LaravelPaytr\Payment;

use Gizemsever\LaravelPaytr\PaytrClient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentVerification extends PaytrClient
{
    private $request;
    private $merchantOid;
    private $status;
    private $totalAmount;
    private $hash;
    private $failedReasonCode;
    private $failedReasonMessage;
    private $testMode;
    private $paymentType;
    private $currency;
    private $paymentAmount;

    public function __construct(Request $request)
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
    }

    /**
     * @return mixed
     */
    public function getMerchantOid()
    {
        return $this->merchantOid;
    }

    /**
     * @return mixed
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return mixed
     */
    public function getFailedReasonCode()
    {
        return $this->failedReasonCode;
    }

    /**
     * @return mixed
     */
    public function getFailedReasonMessage()
    {
        return $this->failedReasonMessage;
    }

    /**
     * @return mixed
     */
    public function getTestMode()
    {
        return $this->testMode;
    }

    /**
     * @return mixed
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return mixed
     */
    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }

    public function verifyRequest()
    {
        $generatedHash = $this->generateHash();
        return ($generatedHash === $this->hash);
    }

    public function isSuccess()
    {
        return ($this->status === 'success');
    }

    public function getProcessedResponse()
    {
        return response('OK', Response::HTTP_OK);
    }

    private function generateHash()
    {
        $hashStr = '' .
            $this->merchantOid .
            $this->credentials['merchant_salt'] .
            $this->status .
            $this->totalAmount;
        return base64_encode(hash_hmac('sha256', $hashStr, $this->credentials['merchant_key'], true));
    }
}
