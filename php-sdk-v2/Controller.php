<?php

namespace PaypaySdk;

class Controller
{
    protected $api_url;
    protected $MainInst;
    private $auth;
    protected $basePostOptions;
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
    protected function HmacCallOpts($HttpMethod, $PaypayEndpoint, $ContentType = 'empty', $RequestData = NULL)
    {

        $AuthStr = PayPayEncryptHeader(
            $this->auth['API_KEY'], $this->auth['API_SECRET'], 
            $HttpMethod, 
            $PaypayEndpoint, 
            $ContentType, 
            $RequestData
        );

        $PostOpts = [
            'CURLOPT_TIMEOUT' => 15,
            'HEADERS' => [
                'Content-Type' => $ContentType,
                'Authorization' => $AuthStr
            ]
        ];
        return $PostOpts;
    }
    
    protected function main(){
        return $this->MainInst;
    }
}
