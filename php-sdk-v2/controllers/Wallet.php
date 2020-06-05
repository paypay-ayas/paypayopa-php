<?php

use PaypaySdk\Controller;

class Wallet extends Controller
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
     * Check if user has enough balance to make a payment
     *
     * @param array $dataOverride Payload request array.
     * @return mixed
     */
    public function checkWalletBalance($dataOverride=[])
    {
        $data = [];
        $data = array_merge($data,$dataOverride);
        $url = $this->api_url . $this->main()->GetEndpoint('WALLET_BALANCE');
        $endpoint = '/v2' . $this->main()->GetEndpoint('WALLET_BALANCE');
        $options = $this->HmacCallOpts('GET', $endpoint, 'application/json;charset=UTF-8;', $data);
        return json_decode(HttpGet($url, $data, $options), true);
    }
}
