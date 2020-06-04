<?php

use PaypaySdk\Controller;

class Code extends Controller
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
     * Create a QR Code to receive payments.
     *
     * @param array $dataOverride Payload request array.
     * @return mixed
     */
    public function create($dataOverride = [])
    {
        $url = $this->api_url . $this->main()->GetEndpoint('CODE');
        $data = [];
        $data['merchantPaymentId'] = $this->MainInst->payload->get_merchant_payment_id();
        $data['codeType'] = $this->MainInst->payload->get_code_type();
        $data['amount'] = $this->MainInst->payload->get_amount();
        $data['orderItems'] = $this->MainInst->payload->get_order_items();
        $data['redirectType'] = $this->MainInst->payload->get_redirect_type();
        $data['redirectUrl'] = $this->MainInst->payload->get_redirect_url();
        $data['requestedAt'] = $this->MainInst->payload->get_requested_at() ? $this->MainInst->payload->get_requested_at() : time();
        $data = array_merge($data, $dataOverride);
        $endpoint = '/v2' . $this->main()->GetEndpoint('CODE');
        $options = $this->HmacCallOpts('POST', $endpoint, 'application/json;charset=UTF-8;', $data);
        $options['CURLOPT_TIMEOUT'] = 30;
        return json_decode(HttpPost($url, $data, $options), true);
    }

    /**
     * Invalidates QR Code for payment
     *
     * @param String $codeId
     * @return mixed
     */
    public function delete($codeId)
    {
        $endpoint = '/v2' . $this->main()->GetEndpoint('CODE'). "/$codeId";
        
        return json_decode(
            HttpDelete(
                $this->api_url . $this->main()->GetEndpoint('CODE') . "/$codeId",
                [],
                $this->HmacCallOpts('DELETE',$endpoint)
            ),
            true
        );
    }
}
