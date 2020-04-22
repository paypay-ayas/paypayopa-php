<?php
/**
 * Makes Http Request calls
 *
 * @param String $method Type of HTTP request
 * @param String $url Request URL
 * @param boolean $URLparams URL/GET Parameters to added
 * @param boolean $body Request Body/POST Payload 
 * @param array $options Request options
 * @return void
 */
function HttpRequest($method, $url, $URLparams = false, $body = false, $options = [])
{
    $curl = curl_init();
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
    if ($URLparams) {
        $url .= "?".http_build_query($URLparams);
    }
    curl_setopt($curl, CURLOPT_URL, $url);
    foreach ($options as $OptName => $OptValue) {
        if ($OptName == 'HEADERS') {
            $headerArr = [];
            foreach ($OptValue as $HeaderKey => $HeaderValue) {
                $headerArr[] = "$HeaderKey: $HeaderValue";
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArr);
        } else {
            curl_setopt($curl, constant($OptName), $OptValue);
        }
    }


    $response = false;
    switch ($method) {
        case 'GET':
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            break;
        case 'POST':
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, count($body));
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
            $response = curl_exec($curl);
            break;
        case 'PATCH':
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, count($body));
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
            $response = curl_exec($curl);
            break;
        case 'PUT':
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, count($body));
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
            break;
        case 'DELETE':
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);            
            curl_setopt($curl, CURLOPT_POST, count($body));
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
            $response = curl_exec($curl);
            break;

        default:
            throw new Exception("Invalid HTTP Request");
            break;
    }
    if (!$response||curl_errno($curl)) {
        throw new Exception("Invalid response from Server " . curl_error($curl));
    }
    
    curl_close($curl);
    return $response;
}
function HttpGet($url, $params=false, $options = [])
{
    return HttpRequest('GET', $url, $params, false, $options);
}
function HttpPost($url, $data, $options = [])
{
    return HttpRequest('POST', $url, false, $data, $options);
}
function HttpPatch($url, $data, $options = [])
{
    return HttpRequest('PATCH', $url, false, $data, $options);
}
function HttpDelete($url, $data, $options = [])
{
    return HttpRequest('DELETE', $url, false, $data, $options);
}
function HttpPut($url, $data, $options = [])
{
    return HttpRequest('PUT', $url, false, $data, $options);
}


function HttpBasicAuthStr($user, $pass)
{
    $b64 = base64_encode("$user:$pass");
    $b64 = preg_replace("/\s+/", "", $b64);
    $basicAuthStr = "Basic " . $b64;
    return $basicAuthStr;
}
