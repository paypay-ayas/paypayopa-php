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

    public function create($data = [])
    {
        $url = $this->api_url . $this->MainInst->GetEndpoint('CODE');
        $options = $this->basePostOptions;
        $options['CURLOPT_TIMEOUT'] = 30;
        $data['merchantPaymentId'] = $this->MainInst->payload->get_merchant_payment_id();
        $data['codeType'] = $this->MainInst->payload->get_code_type();
        $data['amount'] = $this->MainInst->payload->get_amount();
        $data['orderItems'] = $this->MainInst->payload->get_order_items();
        $data['redirectType'] = $this->MainInst->payload->get_redirect_type();
        $data['redirectUrl'] = $this->MainInst->payload->get_redirect_url();
        $data['requestedAt'] = $this->MainInst->payload->get_requested_at() ? $this->MainInst->payload->get_requested_at() : time();

        return HttpPost($url, $data, $options);
    }

    /**
     * Invalidates QR Code for payment
     *
     * @param String $codeId
     * @return mixed
     */
    public function deleteCode($codeId)
    {
        return HttpDelete($this->api_url . $this->MainInst->GetEndpoint('CODE') . "/$codeId", [], $this->basePostOptions);
    }

    /**
     * Fetches Payment details
     *
     * @param String $merchantPaymentId The unique payment transaction id provided by merchant
     * @return mixed
     */
    public function getPaymentDetails($merchantPaymentId)
    {
        $url = $this->api_url . $this->MainInst->GetEndpoint('CODE') . $this->MainInst->GetEndpoint('PAYMENT') . "/$merchantPaymentId";
        return HttpGet($url, [], $this->basePostOptions);
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
        $url=$this->api_url.$this->MainInst->GetEndpoint("PAYMENT")."/$merchantPaymentId";
        return HttpDelete($url, [], $this->basePostOptions);
    }

    public function capturePayment($data = [], $kwargs)
    {
        /*
        $api_url = $this->api_url;
        $url = "$api_url/payments/capture";
        $data['requestedAt'] = datetime . datetime . now() . second;
        return $this->post_url($url, $data, $kwargs);
         */
    }

    public function revertPayment($data = [], $kwargs)
    {
        /*
        $url = "{}/{}/{}" . format('payments', 'preauthorize', 'revert');
        $data['requestedAt'] = datetime . datetime . now() . second;
        return $this->post_url($url, $data, $kwargs);
         */
    }

    public function refundPayment($data = [], $kwargs)
    {
        /*         $url = "/{}" . format('refunds');
        $data['requestedAt'] = datetime . datetime . now() . second;
        return $this->post_url($url, $data, $kwargs);
 */
    }

    public function refundDetails($data = [], $kwargs)
    {
        /*   $url = "/{}" . format('refunds');
        return $this->fetch($url, $data, $kwargs); */
    }
}
