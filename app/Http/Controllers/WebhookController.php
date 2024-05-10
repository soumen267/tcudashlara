<?php

namespace App\Http\Controllers;

use App\Models\ShopifyCustomer;
use App\Models\ShopifyOrder;
use Illuminate\Http\Request;
use App\Traits\ShopifyTrait;

class WebhookController extends Controller
{
    use ShopifyTrait;
    public function index(){
        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        $ShopifyOrderRawData = file_get_contents('php://input');
        $verified = $this->verify_webhook($ShopifyOrderRawData, $hmac_header);

        $ShopifyOrderData = json_decode($ShopifyOrderRawData,true);
        
        $dbdata = new ShopifyOrder();
        $dbdata->shopify_customer_id = $ShopifyOrderData['customer']['id'];
        $dbdata->customer_email = $ShopifyOrderData['customer']['email'];
        $dbdata->shopify_orderid = $ShopifyOrderData['id'];
        $dbdata->shopify_ordername = $ShopifyOrderData['name'];
        $dbdata->total_price = $ShopifyOrderData['total_price'];
        $dbdata->subtotal_price = $ShopifyOrderData['subtotal_price'];
        $dbdata->total_discounts = $ShopifyOrderData['total_discounts'];
        // $dbdata->order_created_at = date_format(date_create($ShopifyOrderData['created_at']),'Y-m-d H:i:s');
        $dbdata->raw_data = $ShopifyOrderRawData;
        $id = $dbdata->save();

        if($ShopifyOrderData['financial_status'] == "paid" ){
            $CheckCustomer = ShopifyCustomer::where('email_address','=',$ShopifyOrderData['email'])->first();
            $priceRuleId = $CheckCustomer['price_rule_id'] ? $CheckCustomer['price_rule_id'] : "";
        
            settype($priceRuleId, "integer");
            
            $balance = $CheckCustomer['balance'];
            $discount = $ShopifyOrderData['total_discounts'];
    
            $balance = ($balance) + ($discount);
            $priceRuleData = [
                'id' => $priceRuleId,
                'value' => $balance
            ];
            $priceuleresponse = $this->updatePriceRule($priceRuleData);
            
            $upd = ShopifyCustomer::where('id', $CheckCustomer['id'])->firstOrFail();
            if($upd){
                $upd->update(['balance' => $balance]);
                echo "Price Rule Updated<br><br>";
            }else{
                echo "Order is not paid<br><br>";
            }
        }
    }

    public function verify_webhook($data, $hmac_header)
    {
    $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
    return hash_equals($hmac_header, $calculated_hmac);
    }


}
