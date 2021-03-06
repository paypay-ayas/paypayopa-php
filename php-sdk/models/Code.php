<?php

class Code
{
    private $api_url;
    private $MainInst;
    private $auth;
    private $basePostOptions;
    /**
     * Initializes Code class to manage creation and deletion of data for QR Code generation
     *
     * @param Client $MainInstance Instance of invoking client class
     * @param Array $auth API credentials
     */
    public function __construct($MainInstance, $auth)
    {
        $this->MainInst = $MainInstance;
        $this->api_url = $this->MainInst->getConfig('API_URL');
        $this->auth = $auth;
        $basicAuthStr = HttpBasicAuthStr($this->auth['API_KEY'], $this->auth['API_SECRET']);
        $this->basePostOptions = [
            'CURLOPT_TIMEOUT' => 15,
            'HEADERS' => [
                'Content-Type' => 'application/json',
                'Authorization' => $basicAuthStr
            ]
        ];
    }

    /**
     * Create a QR Code to receive payments.
     *
     * @param array $dataOverride Payload request array.
     * @return mixed
     */
    public function create($dataOverride = [])
    {
        $main = $this->MainInst;
        $url = $this->api_url . $main->GetEndpoint('CODE');
        $options = $this->basePostOptions;
        $options['CURLOPT_TIMEOUT'] = 30;
        $data = [];
        $data['merchantPaymentId'] = $this->MainInst->payload->get_merchant_payment_id();
        $data['codeType'] = $this->MainInst->payload->get_code_type();
        $data['amount'] = $this->MainInst->payload->get_amount();
        $data['orderItems'] = $this->MainInst->payload->get_order_items();
        $data['redirectType'] = $this->MainInst->payload->get_redirect_type();
        $data['redirectUrl'] = $this->MainInst->payload->get_redirect_url();
        $data['requestedAt'] = $this->MainInst->payload->get_requested_at() ? $this->MainInst->payload->get_requested_at() : time();
        $data = array_merge($data, $dataOverride);
        return json_decode(HttpPost($url, $data, $options), true);
    }

    /**
     * Invalidates QR Code for payment
     *
     * @param String $codeId
     * @return mixed
     */
    public function deleteCode($codeId)
    {
        $main = $this->MainInst;
        return json_decode(HttpDelete($this->api_url . $main->GetEndpoint('CODE') . "/$codeId", [], $this->basePostOptions), true);
    }

    /**
     * Fetches Payment details
     *
     * @param String $merchantPaymentId The unique payment transaction id provided by merchant
     * @return mixed
     */
    public function getPaymentDetails($merchantPaymentId)
    {
        $main = $this->MainInst;
        $url = $this->api_url . $main->GetEndpoint('CODE') . $main->GetEndpoint('PAYMENT') . "/$merchantPaymentId";
        return json_decode(HttpGet($url, [], $this->basePostOptions), true);
    }

    /**
     * Cancel a payment:
     * This method is used in case, while creating a payment, the client can not determine the status of the payment.
     * For example, client get timeout or the response cannot contain the information to indicate the exact payment status.
     * By calling this api, if accepted, the OPA will guarantee the money eventually goes back to user's account.
     * Note: The Cancel API can be used until 00:14:59 AM the day after the Payment has happened.
     *       For 00:15 AM or later, please call the refund method to refund the payment.
     * @param String $merchantPaymentId The unique payment transaction id provided by merchant
     * @return void
     */
    public function cancelPayment($merchantPaymentId)
    {
        $main = $this->MainInst;
        $url = $this->api_url . $main->GetEndpoint("PAYMENT") . "/$merchantPaymentId";
        return json_decode(HttpDelete($url, [], $this->basePostOptions), true);
    }

    /**
     * For payments to be updated with amount after creation, 
     * this method is used to capture the payment authorization 
     * for a payment
     *
     * @param array $dataOverride Payload request array.
     * @return void
     */
    public function capturePayment($dataOverride = [])
    {
        $main = $this->MainInst;
        $url = $main->GetConfig('API_URL') . $main->GetEndpoint('PAYMENT') . "/capture";
        $options = $this->basePostOptions;
        $options['CURLOPT_TIMEOUT'] = 30;
        $data = [];
        $data["merchantPaymentId"] = $main->payload->get_merchant_payment_id();
        $data["amount"] = $main->payload->get_amount();
        $data["merchantCaptureId"] = $main->payload->get_merchant_capture_id();
        $data["requestedAt"] = $main->payload->get_requested_at();
        $data["orderDescription"] = $main->payload->get_order_description();
        $data = array_merge($data, $dataOverride);
        return json_decode(HttpPost($url, $data, $options), true);
    }

    /**
     * For payments to be updated with amount after creation,
     * This api is used in case, the merchant wants to cancel 
     * the payment authorization because of cancellation of 
     * the order by the user.
     *
     * @param array $dataOverride Payload request array.
     * @return mixed
     */
    public function revertPayment($dataOverride = [])
    {
        $main = $this->MainInst;
        $url = $main->GetConfig('API_URL') . $main->GetEndpoint('PAYMENT') . "/capture";
        $options = $this->basePostOptions;
        $options['CURLOPT_TIMEOUT'] = 30;
        $data = [];
        $data["merchantRevertId"] = $main->payload->get_merchant_revert_id();
        $data["paymentId"] = $main->payload->get_merchant_payment_id();
        $data["requestedAt"] = $main->payload->get_requested_at();
        $data["reason"] = $main->payload->get_reason();
        $data = array_merge($data, $dataOverride);
        return json_decode(HttpPost($url, $data, $options), true);
    }

    /**
     * Refund a payment
     *
     * @param array $dataOverride Payload request array.
     * @return void
     */
    public function refundPayment($dataOverride = [])
    {
        $main = $this->MainInst;
        $url = $main->GetConfig('API_URL') . $main->GetEndpoint('REFUND');
        $options = $this->basePostOptions;
        $options['CURLOPT_TIMEOUT'] = 30;
        $data = [];
        $data["merchantRefundId"] = $main->payload->get_merchant_revert_id();
        $data["paymentId"] = $main->payload->get_merchant_payment_id();
        $data["amount"] = $main->payload->get_amount();
        $data["requestedAt"] = $main->payload->get_requested_at();
        $data["reason"] = $main->payload->get_reason();
        $data = array_merge($data, $dataOverride);
        return json_decode(HttpPost($url, $data, $options), true);
    }

    /**
     * Get refund details.
     * @param String $merchantRefundId The unique refund transaction id provided by merchant
     * @return void
     */
    public function refundDetails($merchantRefundId)
    {
        $main = $this->MainInst;
        $url = $main->GetConfig('API_URL') . $main->GetEndpoint('REFUND') . "/$merchantRefundId";
        return json_decode(HttpGet($url, [], $this->basePostOptions), true);
    }
}
