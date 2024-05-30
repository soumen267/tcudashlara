<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

trait ShopifyTrait {


    public function apiCall(array $data = [], $apiurl, $method){

        try {
            $client = new Client();
            $request = $client->request($method, $apiurl, [
                'verify' => false,
                'http_errors' => true,
                'body' => json_encode($data),
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Shopify-Access-Token' => env("SHOPIFY_ACCESS_TOKEN")
                ]
            ]); // Url of your choosing
            $msg = $request->getBody()->getContents();
            $statusCode = $request->getStatusCode();
            $results = json_decode($msg, true);
            return ['code' => $statusCode, 'msg' => $results];
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                // Log the status code and error message
                $statusCode = $response->getStatusCode();
                $msg = $response->getBody()->getContents();
                return ['code' => $statusCode, 'msg' => $msg];
            } else {
                // Handle the case where there's no response
                $errorMessage = $e->getMessage();
                return $errorMessage;
            }
        }
    }

    public function createCustomer(array $data = [])
    {
        $apiurl = "https://aj-store-demo.myshopify.com/admin/api/2024-04/customers/";   
        $response = $this->apiCall($data,$apiurl,'POST');
        return $response;
    }

    public function updateCustomer(array $data = [], $customerId)
    {
        $apiurl = "https://aj-store-demo.myshopify.com/admin/api/2024-04/customers/$customerId.json";
        $response = $this->apiCall($data,$apiurl,'PUT');
        return $response;
    }

    public function createPriceRule(array $data = [])
    {
        if(empty($data)){
            return;
        }

        $apiurl = "https://aj-store-demo.myshopify.com/admin/api/2024-04/price_rules.json/";
        $response = $this->apiCall($data,$apiurl,'POST');
        return $response;
    }

    public function updatePriceRule(array $data = [],$priceRuleId)
    {
        if(empty($data)){
            return;
        }
        $apiurl = "https://aj-store-demo.myshopify.com/admin/api/2024-04/price_rules/$priceRuleId.json/";
        $response = $this->apiCall($data,$apiurl,'POST');
        return $response;
    }

    public function createDiscountCode(array $data = [],$priceRuleId)
    {
        if(empty($data)){
            return;
        }

        $apiurl = "https://aj-store-demo.myshopify.com/admin/api/2024-04/price_rules/$priceRuleId/discount_codes.json/";
        $response = $this->apiCall($data,$apiurl,'POST');
        return $response;
    }

    public function generatePassword($length = 8,$type = '')
    {
        switch ($type) {
            case 'password':
                $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
                break;
            case 'discount':
                $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            
            default:
                $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
                break;
        }
        return substr(str_shuffle($data), 0, $length);

    }

}