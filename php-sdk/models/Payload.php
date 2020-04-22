<?php
class Payload
{
    private $merchantPaymentId;
    private $merchantCaptureId;
    private $merchantRevertId;
    private $merchantRefundId;
    private $requestedAt;
    private $amount;
    private $codeType;
    private $orderItems = [];
    private $redirectType;
    private $redirectUrl;
    private $orderDescription;
    private $storeInfo;
    private $storeId;
    private $terminalId;
    private $isAuthorization;
    private $authorizationExpiry;
    private $reason;



    function set_merchant_payment_id($merchant_payment_id)
    {
        $this->merchantPaymentId = $merchant_payment_id;
    }

    function get_merchant_payment_id()
    {
        return $this->merchantPaymentId;
    }

    function set_merchant_capture_id($merchant_capture_id)
    {
        $this->merchantCaptureId = $merchant_capture_id;
    }

    function get_merchant_capture_id()
    {
        return $this->merchantCaptureId;
    }


    function set_merchant_revert_id($merchant_revert_id)
    {
        $this->merchantRevertId = $merchant_revert_id;
    }

    function get_merchant_revert_id()
    {
        return $this->merchantRevertId;
    }

    function set_merchant_refund_id($merchant_refund_id)
    {
        $this->merchantRefundId = $merchant_refund_id;
    }

    function get_merchant_refund_id()
    {
        return $this->merchantRefundId;
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
    function set_reason($reason)
    {
        $this->reason = $reason;
    }
    function get_reason()
    {
        return $this->reason;
    }
}
