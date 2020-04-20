<?php
class Payload
{
    private $merchantPaymentId = null;
    private $requestedAt = null;
    private $amount = null;
    private $codeType = null;
    private $orderItems = [];
    private $redirectType = null;
    private $redirectUrl = null;
    private $orderDescription = null;
    private $storeInfo = null;
    private $storeId = null;
    private $terminalId = null;
    private $isAuthorization = null;
    private $authorizationExpiry = null;


    function set_merchant_payment_id($merchant_payment_id)
    {
        $this->merchantPaymentId = $merchant_payment_id;
    }

    function get_merchant_payment_id()
    {
        return $this->merchantPaymentId;
    }

    function set_amount($amount)
    {
        $this->amount = $amount;
    }

    function get_amount()
    {
        return $this->amount;
    }
    function set_requested_at($requested_at = false)
    {
        $this->requestedAt = $requested_at ? $requested_at : time();
    }
    function get_requested_at()
    {
        return $this->requestedAt;
    }
    function set_code_type($code_type)
    {
        $this->codeType = $code_type;
    }
    function get_code_type()
    {
        return $this->codeType;
    }
    function set_order_items($order_items = [])
    {
        $this->orderItems = $order_items;
    }
    function get_order_items()
    {
        return $this->orderItems;
    }
    function set_order_description($order_description)
    {
        $this->orderDescription = $order_description;
    }
    function get_order_description()
    {
        return $this->orderDescription;
    }
    function set_store_id($store_id)
    {
        $this->storeId = $store_id;
    }
    function get_store_id()
    {
        return $this->storeId;
    }
    function set_redirect_type($redirect_type)
    {
        $this->redirectType = $redirect_type;
    }
    function get_redirect_type()
    {
        return $this->redirectType;
    }
    function set_redirect_url($redirect_url)
    {
        $this->redirectUrl = $redirect_url;
    }
    function get_redirect_url()
    {
        return $this->redirectUrl;
    }
}
