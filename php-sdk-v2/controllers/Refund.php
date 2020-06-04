<?php

use PaypaySdk\Controller;

class Refund extends Controller
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
        $endpoint = '/v2' . $main->GetEndpoint('PAYMENT') . "/$merchantRefundId";
        $opts = $this->HmacCallOpts('GET', $endpoint);
        return json_decode(HttpGet($url, [], $opts), true);
    }
}
