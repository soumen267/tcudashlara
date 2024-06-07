<?php

namespace App\Http\Controllers;

use Mailgun\Mailgun;
use Mailgun\Exception;
use App\Models\CrmOrder;
use App\Models\Dashboard;
use Illuminate\Http\Request;
use App\Models\ShopifyCustomer;
use Illuminate\Support\Facades\DB;

class EmailController extends Controller
{
    public function sendEmail(Request $request){
    $emailTemplate = NULL;
    $isForced = isset($request->isForced) ? $request->isForced : '0';
    $getShopifyData = CrmOrder::where('id', '=', $request->id)->first();
    $getAllData = Dashboard::with('shopify','crm','smtp')->where('id', '=', $getShopifyData->dashboard)->first();
    $shopifywebhookhash = $getAllData->shopify->shopifywebhookhash;
    $ShopifyCustomerRawData = '';
    $updateMailData = '';
    if($isForced == '0'){
        function verify_webhook($data, $hmac_header)
        {
        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, isset($shopifywebhookhash), true));
        return hash_equals($hmac_header, $calculated_hmac);
        }
        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        $ShopifyCustomerRawData = file_get_contents('php://input');
        $verified = verify_webhook($ShopifyCustomerRawData, $hmac_header);
        $ShopifyCustomerData = json_decode($ShopifyCustomerRawData,true);
    }else{
        $getOrderById = CrmOrder::where('id', '=', $request->id)->first();
        if($getOrderById){
            $dashboardId = $getOrderById->dashboard;
            $ShopifyCustomerData = $getOrderById->emailAddress;
        }
        if($ShopifyCustomerData){
            $getData = DB::table('crm_orders')
            ->join('shopify_customers', 'crm_orders.shopify_customers_id', '=', 'shopify_customers.id')
            ->select('crm_orders.id',
                    'crm_orders.shopify_customers_id',
                    'shopify_customers.name',
                    'shopify_customers.email_address',
                    'shopify_customers.password',
                    'shopify_customers.coupon_code',
                    'shopify_customers.discount_code_id',
                    'shopify_customers.balance',
                    'shopify_customers.mail_status',
            )->where('crm_orders.id', $request->id)->first();
            $CheckCustomer = $getData->mail_status;
            $customerEmail = $getData->email_address;
            $customerPassword = $getData->password;
            $discountCode = $getData->discount_code_id;
            $couponAmount = trim(str_replace('-','',$getData->balance));
            if($CheckCustomer == "Not Sent" || $isForced == '1'){
                    $smtpType = $getAllData->smtp->type;
                    $fromName = $getAllData->smtp->name;
                    $fromEmail = $getAllData->smtp->mailfrom;
                    $mailgunApi = $getAllData->smtp->api;
                    $domain = $getAllData->smtp->domain;
                if($smtpType == "mailgun"){                  
                    if($getAllData->id == '1'){
                        //cuttingedgegizmo
                        $emailTemplate = "email_template.cuttingedgegizmos.email";
                    }elseif($getAllData->id == '2'){
                        //imoderntrendsdash
                        $emailTemplate = "email_template.imoderntrendsdash.email";
                    }elseif($getAllData->id == '3'){
                        //jovprimewidgetpickdash
                        $emailTemplate = "email_template.jovprimewidgetpickdash.email";
                    }elseif($getAllData->id == '4'){
                        //tlhignitegeartech
                        $emailTemplate = "email_template.tlhignitegeartech.email";
                    }elseif($getAllData->id == '5'){
                        //egizmotrendsdash
                        $emailTemplate = "email_template.egizmotrendsdash.email-template-3";
                    }
                    $params = [
                        'from'	    => $fromEmail,
                        'to'	    => $getData->email_address,
                        'subject'   => 'Customer Welcome',
                        'html'	    =>  View($emailTemplate, compact('customerEmail','customerPassword','discountCode','couponAmount'))->render()
                    ];
                    try {
                        $mgClient = Mailgun::create($mailgunApi);
                        $result = $mgClient->messages()->send($domain, $params);
                        if($isForced == '0'){
                            $updateMailData = $ShopifyCustomerRawData;
                        }
                        $CheckShopifyCustomer = ShopifyCustomer::where('id', $getData->shopify_customers_id)->first();
                        if($CheckShopifyCustomer){
                            $saveCustomer = ShopifyCustomer::where('id', $getData->shopify_customers_id)->update([
                                'webhook_response' => json_encode($updateMailData,true),
                                'mail_status' => 'Sent'
                            ]);
                            if($saveCustomer){
                                return response()->json(
                                    [
                                        'msg' => "Mail Sent Successfully"
                                    ]
                                );
                            }
                        }
                    } catch (Exception $e) {
                        dd($e->getMessage());
                        if($isForced == '0'){
                            $updateMailData = $ShopifyCustomerRawData;
                        }
                        $CheckShopifyCustomer = ShopifyCustomer::where('id', $getData->shopify_customers_id)->first();
                        if($CheckShopifyCustomer){
                            $saveCustomer = ShopifyCustomer::where('id', $getData->shopify_customers_id)->update([
                                'webhook_response' => json_encode($updateMailData,true),
                                'mail_status' => 'Failed'
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
            }
        }
    }

            
        
    }
}
