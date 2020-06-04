<?php

use PaypaySdk\Controller;

class Payment extends Controller
{

    /**
     * Initializes Code class to manage creation and deletion of data for QR Code generation
     *
     * @param Client $MainInstance Instance of invoking client class
     * @param Array $auth API credentials
     */
    public function __construct($MainInstance, $auth)
    {
        parent::__construct($MainInstance, $auth);
    }

    /**
     * Fetches Payment details
     *
     * @param String $merchantPaymentId The unique payment transaction id provided by merchant
     * @return mixed
     */
    public function getDetails($merchantPaymentId)
    {
        $main = $this->MainInst;
        $endpoint = '/v2' . $this->main()->GetEndpoint('CODE') . $main->GetEndpoint('PAYMENT') . "/$merchantPaymentId";
        $url = $this->api_url . $main->GetEndpoint('CODE') . $main->GetEndpoint('PAYMENT') . "/$merchantPaymentId";
        $opts = $this->HmacCallOpts('GET', $endpoint);
        return json_decode(HttpGet($url, [], $opts), true);
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
    public function cancel($merchantPaymentId)
    {
        $endpoint = '/v2' . $this->main()->GetEndpoint('PAYMENT') . "/$merchantPaymentId";
        $url = $this->api_url . $this->main()->GetEndpoint('PAYMENT') . "/$merchantPaymentId";
        $opts = $this->HmacCallOpts('DELETE', $endpoint);
        return json_decode(HttpDelete($url, [], $opts), true);
    }

    /**
     * For payments to be updated with amount after creation, 
     * this method is used to capture the payment authorization 
     * for a payment
     *
     * @param array $dataOverride Payload request array.
     * @return void
     */
    public function capture($dataOverride = [])
    {
        $main = $this->MainInst;
        $data = [];
        $data["merchantPaymentId"] = $main->payload->get_merchant_payment_id();
        $data["amount"] = $main->payload->get_amount();
        $data["merchantCaptureId"] = $main->payload->get_merchant_capture_id();
        $data["requestedAt"] = $main->payload->get_requested_at();
        $data["orderDescription"] = $main->payload->get_order_description();
        $data = array_merge($data, $dataOverride);
        $url = $main->GetConfig('API_URL') . $main->GetEndpoint('PAYMENT') . "/capture";
        $endpoint = '/v2' . $this->main()->GetEndpoint('PAYMENT') . "/capture";
        $options = $this->HmacCallOpts('POST', $endpoint, 'application/json;charset=UTF-8;', $data);
        $options['CURLOPT_TIMEOUT'] = 30;
        return json_decode(HttpPost($url, $data, $options), true);
    }

    /**
     * For payments to be updated with amount after creation,
     * This api is used in case the merchant wants to cancel 
     * the payment authorization because of cancellation of 
     * the order by the user.
     *
     * @param array $dataOverride Payload request array.
     * @return mixed
     */
    public function revert($dataOverride = [])
    {
        $main = $this->MainInst;
        $data = [];
        $data["merchantRevertId"] = $main->payload->get_merchant_revert_id();
        $data["paymentId"] = $main->payload->get_merchant_payment_id();
        $data["requestedAt"] = $main->payload->get_requested_at();
        $data["reason"] = $main->payload->get_reason();
        $data = array_merge($data, $dataOverride);
        $url = $main->GetConfig('API_URL') . $main->GetEndpoint('PAYMENT') . "preauthorize/revert";
        $endpoint = '/v2' . $this->main()->GetEndpoint('PAYMENT') . "preauthorize/revert";
        $options = $this->HmacCallOpts('POST', $endpoint, 'application/json;charset=UTF-8;', $data);
        $options['CURLOPT_TIMEOUT'] = 30;
        return json_decode(HttpPost($url, $data, $options), true);
    }
}
