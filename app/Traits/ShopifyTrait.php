<?php

namespace App\Traits;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

trait ShopifyTrait {

    public $token = 'env("SHOPIFY_ACCESS_TOKEN")';

    public function createCustomer($CustomerData, $key,$pwd,$dmn)
    {
        $client = new Client();
        $apiurl = "https://aj-store-demo.myshopify.com/admin/api/2024-04/customers/";
        $request = $client->request('POST', $apiurl, [
            'verify' => false,
            'body' => $CustomerData,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Shopify-Access-Token' => $this->token
            ]
        ]); // Url of your choosing
        $res_body = $request->getBody()->getContents();
        $results = json_decode($res_body, true);
        return $results;
           
    }

    public function createPriceRule($priceRule)
    {
        if(empty($data)){
            return;
        }

        $client = new Client();
        $apiurl = "https://aj-store-demo.myshopify.com/admin/api/2024-04/price_rules.json/";
        $request = $client->request('POST', $apiurl, [
            'verify' => false,
            'body' => $priceRule,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Shopify-Access-Token' => $this->token
            ]
        ]); // Url of your choosing
        $res_body = $request->getBody()->getContents();
        $results = json_decode($res_body, true);
        return $results;
    }

    public function updatePriceRule($data,$priceRuleId)
    {
        if(empty($data)){
            return;
        }

        $client = new Client();
        $apiurl = "https://aj-store-demo.myshopify.com/admin/api/2024-04/price_rules/$priceRuleId.json/";
        $request = $client->request('POST', $apiurl, [
            'verify' => false,
            'body' => $data,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Shopify-Access-Token' => $this->token
            ]
        ]); // Url of your choosing
        $res_body = $request->getBody()->getContents();
        $results = json_decode($res_body, true);
        return $results;
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

    public function createDiscountCode($data,$priceRuleId)
    {
        if(empty($data)){
            return;
        }

        $client = new Client();
        $apiurl = "https://aj-store-demo.myshopify.com/admin/api/2024-04/price_rules/$priceRuleId/discount_codes.json/";
        $request = $client->request('POST', $apiurl, [
            'verify' => false,
            'body' => $data,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Shopify-Access-Token' => $this->token
            ]
        ]); // Url of your choosing
        $res_body = $request->getBody()->getContents();
        $results = json_decode($res_body, true);
        return $results;
    }

}