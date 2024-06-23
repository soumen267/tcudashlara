<?php

namespace App\Traits;

use Mailgun\Mailgun;
use Mailgun\Exception;
use GuzzleHttp\Client;
use App\Models\Dashboard;
use App\Models\ShopifyCustomer;
use GuzzleHttp\Exception\RequestException;

trait EmailTrait {

    public function sendGiftEmail($dashID, $shopifyCustomerID){

        $getAllData = Dashboard::with('shopify','crm','smtp')->where('id', '=', $dashID)->first();
        $ShopifyCustomerRawData = '';
        $getData = ShopifyCustomer::where('id', $shopifyCustomerID)->first();
        if($getData){
            $CheckCustomer = $getData->mail_status;
            $customerEmail = $getData->email_address;
            $customerPassword = $getData->password;
            $discountCode = $getData->discount_code_id;
            $couponAmount = trim(str_replace('-','',$getData->balance));
            $smtpType = $getAllData->smtp->type;
            $fromName = $getAllData->smtp->email;
            $fromEmail = $getAllData->smtp->mailfrom;
            $mailgunApi = $getAllData->smtp->api;
            $domain = $getAllData->smtp->domain;
            $emailTemplate = $getAllData->smtp->emailtemplatepath;
            if($smtpType == "mailgun" || $smtpType == "MAILGUN"){
                $params = [
                    'from'	    => $fromEmail,
                    'to'	    => $getData->email_address,
                    'subject'   => 'Customer Welcome',
                    'html'	    =>  View($emailTemplate, compact('customerEmail','customerPassword','discountCode','couponAmount'))->render()
                ];
                try {
                    $mgClient = Mailgun::create($mailgunApi);
                    $result = $mgClient->messages()->send($domain, $params);
                    $CheckShopifyCustomer = ShopifyCustomer::where('id', $getData->shopify_customers_id)->first();
                    if($CheckShopifyCustomer){
                        global $webhookResponse;
                        $webhookResponse = $result;
                        return response()->json(
                            [
                                'msg' => "Mail Sent Successfully"
                            ]
                        );
                    }
                } catch (Exception $e) {
                    return response()->json(
                        [
                            'msg' => $e->getMessage()
                        ]
                    );
                    
                    $CheckShopifyCustomer = ShopifyCustomer::where('id', $getData->shopify_customers_id)->first();
                    if($CheckShopifyCustomer){
                        $saveCustomer = ShopifyCustomer::where('id', $getData->shopify_customers_id)->update([
                            'webhook_response' => json_encode($webhookResponse,true),
                            'mail_status' => 'sent'
                        ]);
                        if($saveCustomer){
                            return response()->json(
                                [
                                    'msg' => "We Are Unable To Send The Mail. Try Again."
                                ]
                            );
                        }
                    }
                }
            }
        }else{
        return response()->json(
            [
                'msg' => "Something went wrong!"
            ]
        );
    }
    }

}