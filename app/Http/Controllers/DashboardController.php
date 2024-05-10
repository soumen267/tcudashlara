<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Crm;
use App\Models\Product;
use App\Models\CrmOrder;
use Illuminate\Http\Request;
use App\Models\ShopifyCustomer;
use App\Models\ShopifyNotregData;
use App\Traits\ShopifyTrait;
use App\Traits\StickyTrait;

class DashboardController extends Controller
{
    use ShopifyTrait, StickyTrait;
    public function accountCreate(Request $request)
    {
        $orderId = ($request->order_id ? $request->order_id : '');
        $CheckOrders = CrmOrder::where('orderId', '=', $orderId)->first();
        if ($CheckOrders) {
            echo "Hi";
        } else {
            //3290266
            $sticky = Crm::where('status', '=', '1')->first();
            $apiurl = $sticky->apiendpoint . "/api/v1/order_view";
            $DataQuery = [
                'order_id' => $orderId,
            ];
            try {
                $getProducts = Product::get()->pluck('products')->toArray();
                // dd($getProducts);
                $response = $this->orderView($apiurl, $DataQuery, $sticky->apiusername, $sticky->apipassword);
                //dd($response);
                $CheckAllowedProduct = [];
                $ProductPriceArr = [];
                if ($response['response_code'] == "100") {
                    foreach ($response["products"] as $key => $order_offer) {
                        $ordersProduct[] = $order_offer["product_id"];
                        $ProductPriceArr[$order_offer["product_id"]] = $order_offer["price"];
                    }

                    $CheckAllowedProduct = array_intersect(
                        $ordersProduct,
                        $getProducts
                    );
                    // dd($CheckAllowedProduct);
                }
                if (sizeof($CheckAllowedProduct) > 0) {
                    $TotalAllowedOrderPrice = 0;
                    foreach ($CheckAllowedProduct as $pkey => $pid) {
                        $TotalAllowedOrderPrice =
                            $TotalAllowedOrderPrice + $ProductPriceArr[$pid];
                    }
                    $couponAmount = env("COUPON_AMOUNT");
                    if (env("COUPON_TYPE") === "PERCENTAGE") {
                        $couponAmount = round($TotalAllowedOrderPrice * ($couponAmount / 100));
                    } elseif (env("COUPON_TYPE") === "STATIC") {
                        $__COUPON_AMOUNT__ = round($couponAmount);
                    } else {
                        $__COUPON_AMOUNT__ = round($couponAmount);
                    }
                }
                if (sizeof($CheckAllowedProduct) > 0) {
                    $CheckOrders = CrmOrder::where('emailAddress', '=', $response['email_address'])->first();
                    $CheckCustomer = ShopifyCustomer::where('status', '=', 1)->get();
                    if (sizeof($CheckCustomer) > 0) {
                        $ExistsCustomer = $CheckCustomer[0];
                        $responseArr["CustomerStatus"] = "Customer already exists";
                        $responseArr["CustomerId"] = $ExistsCustomer["shopify_customer_id"];
                        $responseArr["CustomerUsername"] = $ExistsCustomer["email_address"];
                        $responseArr["CustomerPassword"] = $ExistsCustomer["password"];
                    } else {
                        $password = $this->generatePassword(12, "password");
                        $CustomerData = '{
                            "customer":{
                                    "address1" : "' . $response["shipping_street_address"] . '",
                                    "city" : "' . $response["shipping_city"] . '",
                                    "state" : "' . $response["shipping_state"] . '",
                                    "phone" : "' . $response["customers_telephone"] . '",
                                    "zip" : "' . $response["shipping_street_address"] . '",
                                    "last_name" : "' . $response["last_name"] . '",
                                    "first_name" : "' . $response["first_name"] . '",
                                    "email" : "' . $response["email_address"] . '",
                                    "verified_email" : "true",
                                    "password" : "' . $password . '",
                                    "password_confirmation" : "' . $password . '",
                                    "send_email_welcome" : "false"
                                }
                        }';
                        $getProd = Product::with('dashb.shopify')->where('products', '=', $CheckAllowedProduct)->first();
                        $apiKey = $getProd->dashb->shopify['shopifyapikey'];
                        $apiPassword = $getProd->dashb->shopify['shopifyapipassword'];
                        $domain = $getProd->dashb->shopify['shopifydomainname'];
                        $dashID = $getProd->dashboard_id;

                        $response1 = $this->createCustomer($CustomerData, $apiKey, $apiPassword, $domain);
                        // dd($response1);
                        if (!is_array($response1[0])) {
                            //print_r($response[0]->getResponse()->getStatusCode());

                            // print_r($response[0]->getResponse()->getBody()->getContents());
                            $json = $response1[0]
                                ->getResponse()
                                ->getBody()
                                ->getContents();
                            $error = json_decode($json, true);
                            $responseArr["error_code"] = $response1[0]
                                ->getResponse()
                                ->getStatusCode();
                            $responseArr["error_message"] = $error;

                            $error_reason = "";
                            foreach ($error["errors"] as $key => $value) {
                                $error_reason .=
                                    $key . " " . $error["errors"][$key][0] . " & ";
                            }
                            $error_reason = substr($error_reason, 0, -2);

                            $mail_response = [];
                            //$mail_response = sendMail($response['firstName'],$response['lastName'],$response['emailAddress'],$response['phoneNumber'],$_REQUEST['order_id'], $error_reason);

                            // print_r($mail_response);
                            // print_r($err_data);
                            $saveData = new ShopifyNotregData();
                            $saveData->order_id = $_REQUEST["order_id"];
                            $saveData->email = $response["email_address"];
                            $saveData->error_msg = $error_reason;
                            $saveData->mail_response = "";
                            $saveData->save();
                            echo $html =
                                '<tr><td colspan="2"><span class="text-danger">' .
                                $error_reason .
                                "</span></td></tr>";
                            die();
                        }

                        $saveShopify = new ShopifyCustomer();
                        $saveShopify->name = $response1[0]["first_name"] . " " . $response1[0]["last_name"];
                        $saveShopify->shopify_customer_id = $response1[0]["id"];
                        $saveShopify->email_address = $response1[0]["email"];
                        $saveShopify->password = $password;
                        $saveShopify->phone = $response1[0]["phone"];
                        $saveShopify->crm_response = json_encode($response, true);
                        $saveShopify->status = "Active";
                        $saveShopify->save();


                        $responseArr["CustomerStatus"] = "Customer Created";
                        $responseArr["CustomerId"] = $saveShopify["shopify_customer_id"];
                        $responseArr["CustomerUsername"] = $saveShopify["email_address"];
                        $responseArr["CustomerPassword"] = $saveShopify["password"];
                    }
                }
            } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                return $e->getResponse()->getBody()->getContents();
            }
        }
        $CheckAllowedProductForGift = array_intersect(
            $ordersProduct,
            $getProducts
        );
        if (sizeof($CheckAllowedProductForGift) > 0) {
            $CheckCustomer = ShopifyCustomer::where('email_address', '=', $response['email_address'])->first();
            $generateCode = strtoupper(
                $this->generatePassword(8, "discount") .
                    "CC" .
                    $CheckCustomer["id"]
            );
            $balance = $CheckCustomer["balance"];
            $balance = $balance - $__COUPON_AMOUNT__;
            $couponCode = $CheckCustomer["coupon_code"] ? $CheckCustomer["coupon_code"] : $generateCode;
            $priceRuleId = $CheckCustomer["price_rule_id"] ? $CheckCustomer["price_rule_id"] : "";
            if ($CheckCustomer["price_rule_id"] == null || $CheckCustomer["price_rule_id"] == "") {
                $dateNow = Carbon::now()->format('Y-m-d');
                $priceRuleData = '{
                    "price_rule": {
                        "title":"' . $couponCode . '",
                        "target_type":"line_item",
                        "target_selection":"all",
                        "allocation_method":"across",
                        "value_type":"fixed_amount",
                        "value":(int) "' . $balance . '",
                        "customer_selection":"prerequisite",
                        "prerequisite_customer_ids": [
                            "' . $CheckCustomer["shopify_customer_id"] . '"
                        ],
                        "starts_at": "' . $dateNow . '"
                        }
                }';
                $priceuleresponse = $this->createPriceRule($priceRuleData);
                $updatepriceRuleData = [
                    "coupon_code" => $couponCode,
                    "balance" => $balance,
                    "price_rule_id" => $priceuleresponse[0]["id"],
                ];
                $priceRuleId = $priceuleresponse[0]["id"];
                ShopifyCustomer::where('id', $CheckCustomer["id"])->update([
                    "coupon_code" => $couponCode,
                    "balance" => $balance,
                    "price_rule_id" => $priceuleresponse[0]["id"],
                ]);
                $responseArr["PriceRuleStatus"] =
                    "Price Rule Created. Id: " . $priceRuleId;
            } else {
                settype($priceRuleId, "integer");
                $priceRuleData = '{
                   "price_rule":{
                        "id":"' . $priceRuleId . '",
                        "value":"' . $balance . '"
                   }
                }';
                $priceuleresponse = $this->updatePriceRule($priceRuleData, $priceRuleId);
                ShopifyCustomer::where('id', $CheckCustomer["id"])->update([
                    "balance" => $balance,
                ]);
                $responseArr["PriceRuleStatus"] = "Price Rule Updated. Id: " . $priceRuleId;
            }

            if ($CheckCustomer["discount_code_id"] == null || $CheckCustomer["discount_code_id"] == "") {
                settype($priceRuleId, "integer");
                $couponData = '{
                    "discount_code":{
                        "id":"' . $priceRuleId . '",
                        "code":"couponCode"
                        }
                    }';
                $couponresponse = $this->createDiscountCode($couponData, $priceRuleId);
                // print_r($couponresponse);
                // die();
                $updatepriceRuleData = ShopifyCustomer::where('id', $CheckCustomer["id"])->update([
                    "discount_code_id" => $couponresponse[0]["id"],
                ]);

                if ($updatepriceRuleData) {
                    $responseArr["DiscountCodeStatus"] = "Discount Code Created";
                }
                $responseArr["DiscountCodeId"] = $couponresponse[0]["id"];
                $responseArr["DiscountCodeCode"] = $couponCode;
                $responseArr["DiscountBalance"] = $balance;
            } else {
                $responseArr["DiscountCodeStatus"] = "Discount Code Already Exist";
                $responseArr["DiscountCodeId"] = $CheckCustomer["discount_code_id"];
                $responseArr["DiscountCodeCode"] = $CheckCustomer["coupon_code"];
                $responseArr["DiscountBalance"] = $CheckCustomer["balance"];
            }
            $crmOrders = new CrmOrder();
            $crmOrders->orderId = $response["order_id"];
            $crmOrders->customerId = $response["customer_id"];
            $crmOrders->emailAddress = $response["email_address"];
            $crmOrders->phoneNumber = $response["customers_telephone"];
            $crmOrders->firstName = $response["first_name"];
            $crmOrders->lastName = $response["last_name"];
            $crmOrders->pid = implode(",", $CheckAllowedProductForGift);
            // $crmOrders->dateCreated = $response["acquisition_date"];
            // $crmOrders->dashboard = $dashID;
            $crmOrders->api_response = json_encode($responseArr, true);
            $crmOrders->save();
        }

        $returnType = isset($_REQUEST["return_type"])
            ? $_REQUEST["return_type"]
            : "json";
        if ($returnType == "html") {
            $html = "";
            $html .=
                "<tr><th>Shopify Coupon Code</th><td>" .
                $responseArr["DiscountCodeCode"] .
                "</td></tr>";
            $html .=
                "<tr><th>Shopify Coupon Balance</th><td>" .
                str_replace("-", "$", $responseArr["DiscountBalance"]) .
                "</td></tr>";
            $html .=
                "<tr><th>Shopify Customer Id</th><td>" .
                $responseArr["CustomerId"] .
                "</td></tr>";
            $html .=
                "<tr><th>Shopify Customer Email</th><td>" .
                $responseArr["CustomerUsername"] .
                "</td></tr>";
            $html .=
                "<tr><th>Shopify Customer Password</th><td>" .
                $responseArr["CustomerPassword"] .
                "</td></tr>";

            echo $html;
        } else {
            echo json_encode($responseArr, true);
        }
    }
}