<?php

include('models/Payload.php');
include('models/Code.php');
include('helpers/http.php');
class Client
{
    private $auth, $config, $endpoints;
    public $payload, $code;

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
