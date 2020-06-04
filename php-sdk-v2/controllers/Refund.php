<?php

class Refund
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
        $AuthStr = HttpBasicAuthStr($this->auth['API_KEY'], $this->auth['API_SECRET']);
        $this->basePostOptions = [
            'CURLOPT_TIMEOUT' => 15,
            'HEADERS' => [
                'Content-Type' => 'application/json',
                'Authorization' => $AuthStr
            ]
        ];
    }
    /**
     * Refund a payment
     *
     * @param array $dataOverride Payload request array.
     * @return void
     */
    public function create($dataOverride = [])
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
    public function getDetails($merchantRefundId)
    {
        $main = $this->MainInst;
        $url = $main->GetConfig('API_URL') . $main->GetEndpoint('REFUND') . "/$merchantRefundId";
        return json_decode(HttpGet($url, [], $this->basePostOptions), true);
    }
}