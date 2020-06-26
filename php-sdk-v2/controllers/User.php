<?php

use PaypaySdk\Controller;

class User extends Controller
{
    private $userAuthorizationId;
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
     * Sets user authorization for this controller
     *
     * @param string $userAuthorizationId
     * @return void
     */
    public function setUserAuthorizationId($userAuthorizationId)
    {
        $this->userAuthorizationId = $userAuthorizationId;
    }

    /**
     * Unlink a user from the client
     *
     * @param string $userAuthorizationId User authorization id. Leave empty if already set.
     * @return mixed
     */
    public function unlinkUser($userAuthorizationId = false)
    {
        if (!$userAuthorizationId) {
            $userAuthorizationId = $this->userAuthorizationId;
        }
        $url = $this->api_url . $this->main()->GetEndpoint('USER_AUTH') . "/$userAuthorizationId";
        $endpoint = '/v2' . $this->main()->GetEndpoint('USER_AUTH') . "/$userAuthorizationId";
        $opts = $this->HmacCallOpts('DELETE', $endpoint);
        $response = HttpDelete($url, [], $opts);
        return json_decode($response, true);
    }

    /**
     * Get the authorization status of a user
     *
     * @param string $userAuthorizationId
     * @return mixed
     */
    public function getUserAuthorizationStatus($userAuthorizationId)
    {
        if (!$userAuthorizationId) {
            $userAuthorizationId = $this->userAuthorizationId;
        }
        $url = $this->api_url . $this->main()->GetEndpoint('USER_AUTH');
        $endpoint = '/v2' . $this->main()->GetEndpoint('USER_AUTH');
        $opts = $this->HmacCallOpts('GET', $endpoint);
        $response = HttpGet($url, ['userAuthorizationId' => $userAuthorizationId], $opts);
        return json_decode($response, true);
    }

    /**
     * Get the masked phone number of the user
     *
     * @param string $userAuthorizationId
     * @return void
     */
    public function getMaskedUserProfile($userAuthorizationId)
    {
        if (!$userAuthorizationId) {
            $userAuthorizationId = $this->userAuthorizationId;
        }
        $url = $this->api_url . $this->main()->GetEndpoint('USER_PROFILE_SECURE');
        $endpoint = '/v2' . $this->main()->GetEndpoint('USER_PROFILE_SECURE');
        $opts = $this->HmacCallOpts('GET', $endpoint);
        $response = HttpGet($url, ['userAuthorizationId' => $userAuthorizationId], $opts);
        return json_decode($response, true);
    }
}
