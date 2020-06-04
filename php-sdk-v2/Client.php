<?php

include(__DIR__.'/Controller.php');
include(__DIR__.'/models/Payload.php');
include(__DIR__.'/controllers/Code.php');
include(__DIR__.'/controllers/Payment.php');
include(__DIR__.'/controllers/Refund.php');
include(__DIR__.'/controllers/User.php');
include(__DIR__.'/helpers/http.php');
include(__DIR__.'/helpers/utility_hmac.php');
class Client
{
    private $auth, $config, $endpoints;
    public $payload; 
    public $code,$payment,$refund,$user;

    /**
     * Initialize a Client object with session,
     * optional auth handler, and options      *
     * @param [Array] $auth API credentials
     * @param [Array] $options 
     */
    function __construct($auth = Null, $options = [])
    {
        session_start();
        $_SESSION['options'] = $options;
        $this->auth = $auth;
        require_once('conf/config.php');
        $this->config = $config;
        require('../conf/endpoints.php');
        $this->endpoints = $endpoint;
        $this->payload = new Payload();
        $this->code = new Code($this, $auth);
        $this->payment = new Payment($this, $auth);
        $this->refund = new Refund($this, $auth);
        $this->user = new User($this, $auth);
    }

    /**
     * Returns relevant config value
     *
     * @param [type] $configName Name of configuration
     * @return mixed
     */
    public function GetConfig($configName)
    {
        return $this->config[$configName];
    }

    /**
     * Returns relevant endpoint path
     *
     * @param String $endpointName Name of Endpoint
     * @return String
     */
    public function GetEndpoint($endpointName)
    {
        return $this->endpoints[$endpointName];
    }
}
