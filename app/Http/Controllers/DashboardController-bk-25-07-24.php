<?php



namespace App\Http\Controllers;



use Carbon\Carbon;

use App\Models\Crm;

use App\Models\Smtp;

use App\Helpers\helper;

use App\Models\Product;

use App\Models\Shopify;

use App\Models\CrmOrder;

use App\Models\Dashboard;

use App\Traits\EmailTrait;

use App\Traits\StickyTrait;

use Illuminate\Support\Str;

use App\Traits\ShopifyTrait;

use Illuminate\Http\Request;

use App\Models\ShopifyCustomer;

use Yajra\DataTables\DataTables;

use App\Models\ShopifyNotregData;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Config;



class DashboardController extends Controller

{

    use ShopifyTrait, StickyTrait, EmailTrait;

    public function accountCreate(Request $request)

    {

        global $couponAmounts;

        $dashID = '0';

        $responseArr = [];

        $ordersProduct = [];

        $getProducts = [];

        $response = [];

        $balance = 0;

        $orderId = ($request->order_id ? $request->order_id : '');

        $saveShopify = [];

        $sticky1 = Crm::where('status', '=', '1')->first();

            $apiurl1 = $sticky1->apiendpoint . "/api/v1/order_view";

            $DataQuery1 = [

                'order_id' => $orderId,

        ];

        $response2 = $this->orderView($apiurl1, $DataQuery1, $sticky1->apiusername, $sticky1->apipassword);

        DB::table('request_check')->insert(

            [

            'order_id' => $orderId,

            'email' => $response2["email_address"],

            'api_response' => json_encode($response2,true),

            'created_at' => Carbon::now()->toDateTimeString(),

            'updated_at' => Carbon::now()->toDateTimeString()

            ]

        );

        $CheckOrders = CrmOrder::where('orderId', '=', $orderId)->first();

        if ($CheckOrders) {

            echo $html =

                '<tr><td colspan="2"><span class="text-danger">Order Id Already Processed</span></td></tr>';

            die();

        } else {

            //3290266

            $sticky = Crm::where('status', '=', '1')->first();

            $apiurl = $sticky->apiendpoint . "/api/v1/order_view";

            $DataQuery = [

                'order_id' => $orderId,

            ];

            try {

                $getProducts = Product::get()->pluck('products')->toArray();

                //dd($getProducts);

                $response = $this->orderView($apiurl, $DataQuery, $sticky->apiusername, $sticky->apipassword);

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

                    //dd($getDash['dashId']);

                }

                

                if (sizeof($CheckAllowedProduct) > 0) {

                    $TotalAllowedOrderPrice = 0;

                    foreach ($CheckAllowedProduct as $pkey => $pid) {

                        $TotalAllowedOrderPrice =

                            $TotalAllowedOrderPrice + $ProductPriceArr[$pid];

                    }

                    $couponValue = $request->coupon_val ? $request->coupon_val : '';
                    if($couponValue != null){
                        
                        $couponAmounts = $couponValue;
                        
                    }else{

                        //$couponAmount = env("COUPON_AMOUNT");
                        $couponValue = Helper::getCoupon();
                        //dd($couponValue['couponType']);
                        $couponType = $couponValue['couponType'];
                        $couponAmount= $couponValue['couponAmount'];
                        
                        if ($couponType === "PERCENTAGE") {

                            $couponAmount = round($TotalAllowedOrderPrice * ($couponAmount / 100));

                        } elseif ($couponType === "STATIC") {

                            $couponAmounts = round($couponAmount);

                        } else {

                            $couponAmounts = round($couponAmount);

                        }

                    }

                }

                

                if (sizeof($CheckAllowedProduct) > 0) {

                    //$CheckOrders = CrmOrder::where('emailAddress', '=', $response['email_address'])->first();

                    $getDash = Helper::getDashboardId($response);

                    $value = $response["email_address"];

                    $CheckCustomers = CrmOrder::with(['shopifyCustomers'])->whereHas('shopifyCustomers', function($q) use ($value){

                        $q->where('email_address', $value);

                    })

                    ->where('dashboard',$getDash)

                    ->first();



                    //dd($CheckCustomers->shopifyCustomers);

                    //$CheckCustomer = ShopifyCustomer::where('status', '=', 'Active')->get();

                    if ($CheckCustomers != null) {

                        $ExistsCustomer = $CheckCustomers->shopifyCustomers;

                        $responseArr["CustomerStatus"] = "Customer already exists";

                        $responseArr["CustomerId"] = $ExistsCustomer["shopify_customer_id"];

                        $responseArr["CustomerUsername"] = $ExistsCustomer["email_address"];

                        $responseArr["CustomerPassword"] = $ExistsCustomer["password"];

                    } else {

                        $password = $this->generatePassword(12, "password");

                        $CustomerData = [

                            "customer" =>[

                                    "address1" => $response["shipping_street_address"],

                                    "city" => $response["shipping_city"],

                                    "state" => $response["shipping_state"],

                                    "phone" => $response["customers_telephone"],

                                    "zip" => $response["shipping_street_address"],

                                    "last_name" => $response["last_name"],

                                    "first_name" => $response["first_name"],

                                    "email" => $response["email_address"],

                                    "verified_email" => "true",

                                    "password" => $password,

                                    "password_confirmation" => $password,

                                    "send_email_welcome" => "false"

                            ]

                        ];

                        $getProd = Product::with('dashb.shopify')->where('products', '=', $CheckAllowedProduct)->first();

                        $storename = $getProd->dashb->shopify['storeurl'];

                        $token = $getProd->dashb->shopify['shopifyapipassword'];

                        $dashID = $getProd->dashboard_id;

                        $response1 = $this->createCustomer($CustomerData, $storename, $token);

                        if ($response1['code'] == '422' || $response1['code'] == '401') {

                            // dd($response1->getStatusCode());

                            // dd($response1->getBody()->getContents());

                            $json = $response1['msg'];

                            $error = json_decode($json, true);

                            $responseArr["error_code"] = $response1['code'];

                            $responseArr["error_message"] = $error;

                            $error_reason = "";

                            if($response1['code'] == '401'){

                                $error_reason = $error['errors'];

                            }elseif($response1['code'] == '422'){

                                foreach ($error["errors"] as $key => $value) {

                                    $error_reason .=

                                        $key . " " . $error["errors"][$key][0] . " & ";
                                        //$error["errors"][$key][0] . " & ";

                                }
                                
                                $error_reason = substr($error_reason, 0, -2);

                            }

                            $mail_response = [];

                            //$mail_response = sendMail($response['firstName'],$response['lastName'],$response['emailAddress'],$response['phoneNumber'],$_REQUEST['order_id'], $error_reason);



                            // print_r($mail_response);

                            // print_r($err_data);
                            
                            $saveData = new ShopifyNotregData();

                            $saveData->order_id = $request->order_id;

                            $saveData->email = $response["email_address"];

                            $saveData->error_msg = $error_reason;

                            $saveData->mail_response = "";

                            $saveData->pid = $response["products"][0]["product_id"];

                            $saveData->save();

                            return response()->json([
                                'error_code' => $response1['code'],
                                'error_reason' => $error_reason,
                            ]);

                            // echo $html =

                            //     '<tr><td colspan="2"><span class="text-danger">' .

                            //     $error_reason .

                            //     "</span></td></tr>";

                        }else{

                            $saveShopify = new ShopifyCustomer();

                            $saveShopify->name = $response1['msg']['customer']["first_name"] . " " . $response1['msg']['customer']["last_name"];

                            $saveShopify->shopify_customer_id = $response1['msg']['customer']["id"];

                            $saveShopify->email_address = $response1['msg']['customer']["email"];

                            $saveShopify->password = $password;

                            $saveShopify->phone = $response1['msg']['customer']["phone"];

                            $saveShopify->crm_response = json_encode($response, true);

                            $saveShopify->status = "Active";

                            $saveShopify->save(); 

    

                            $responseArr["CustomerStatus"] = "Customer Created";

                            $responseArr["CustomerId"] = $saveShopify["shopify_customer_id"];

                            $responseArr["CustomerUsername"] = $saveShopify["email_address"];

                            $responseArr["CustomerPassword"] = $saveShopify["password"];

                        }

                        // dd($response1);

                        

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

            $getDash = Helper::getDashboardId($response);

            $shopifyCustomerID = '';

            $ViewOrder = $response["email_address"];

            $CheckCustomers = CrmOrder::with(['shopifyCustomers'])->whereHas('shopifyCustomers', function($q) use ($ViewOrder){

                                            $q->where('email_address', $ViewOrder);

                                        })

                                        ->where('dashboard',$getDash)

                                        ->first();

            //$CheckShopifyCustomer = $CheckCustomers->shopifyCustomers;

            $getProd = Product::with('dashb.shopify')->where('products', '=', $CheckAllowedProductForGift)->first();

            $storename = $getProd->dashb->shopify['storeurl'];

            $token = $getProd->dashb->shopify['shopifyapipassword'];

            $dashID = $getProd->dashboard_id;

            if($CheckCustomers == null && !empty($saveShopify['id'])){

                $customerId = $saveShopify['id'];

                $checkLastInsertShopifyCustomers = ShopifyCustomer::where('id', $customerId)->first();

                $shopifyCustomerID = $customerId;

                $generateCode = strtoupper(

                    $this->generatePassword(8, "discount") .

                        "CC" .

                        $customerId

                );

                $couponAmounts = round($couponAmounts,0);

                $balance = $checkLastInsertShopifyCustomers["balance"] ? $checkLastInsertShopifyCustomers["balance"] : 0;

                $balance = $balance - $couponAmounts;
                //$balance = $couponAmounts;
                //dd($balance);
                
                $couponCode = $checkLastInsertShopifyCustomers["coupon_code"] ? $checkLastInsertShopifyCustomers["coupon_code"] : $generateCode;

                $priceRuleId = $checkLastInsertShopifyCustomers["price_rule_id"] ? $checkLastInsertShopifyCustomers["price_rule_id"] : "";

                if ($checkLastInsertShopifyCustomers["price_rule_id"] == null || $checkLastInsertShopifyCustomers["price_rule_id"] == "") {

                    $dateNow = Carbon::now()->toIso8601String();

                    $priceRuleData = [

                    "price_rule" => [

                        "title" => $couponCode,

                        "target_type" => "line_item",

                        "target_selection" => "all",

                        "allocation_method" => "across",

                        "value_type" => "fixed_amount",

                        "value" => $balance,

                        "customer_selection" => "prerequisite",

                        "prerequisite_customer_ids" => [

                            $checkLastInsertShopifyCustomers["shopify_customer_id"]

                        ],

                        "starts_at" => $dateNow

                        ]

                    ];

                    $priceuleresponse = $this->createPriceRule($priceRuleData, $storename, $token);

                    $priceRuleId = $priceuleresponse["msg"]["price_rule"]["id"];

                    ShopifyCustomer::where('id', $customerId)->update([

                        "coupon_code" => $couponCode,

                        "balance" => $balance,

                        "price_rule_id" => $priceuleresponse["msg"]["price_rule"]["id"],

                    ]);

                    $responseArr["PriceRuleStatus"] =

                        "Price Rule Created. Id: " . $priceRuleId;

                    }



                    if ($checkLastInsertShopifyCustomers["discount_code_id"] == null || $checkLastInsertShopifyCustomers["discount_code_id"] == "") {

                        settype($priceRuleId, "integer");

                        $couponData = [

                            "discount_code" => [

                                "id" => $priceRuleId,

                                "code"=> $couponCode

                            ]

                        ];

                        $couponresponse = $this->createDiscountCode($couponData, $storename, $token, $priceRuleId);

                        // print_r($couponresponse);

                        // die();

                        $updatepriceRuleData = ShopifyCustomer::where('id', $checkLastInsertShopifyCustomers["id"])->update([

                            "discount_code_id" => $couponresponse["msg"]["discount_code"]["id"],

                        ]);

        

                        if ($updatepriceRuleData) {

                            $responseArr["DiscountCodeStatus"] = "Discount Code Created";

                        }

                        $responseArr["DiscountCodeId"] = $couponresponse["msg"]["discount_code"]["id"];

                        $responseArr["DiscountCode"] = $couponCode;

                        $responseArr["DiscountBalance"] = $balance;

                    }



            }else{

                if(empty($saveShopify['id']) && $CheckCustomers != null){

                $balance = $CheckCustomers->shopifyCustomers["balance"];

                $balance = $balance - $couponAmounts;

                $shopifyCustomerID = $CheckCustomers->shopifyCustomers['id'];

                $priceRuleId = $CheckCustomers->shopifyCustomers["price_rule_id"];

                settype($priceRuleId, "integer");

                        $priceRuleData = [

                        "price_rule"=> [

                                "id"=> $priceRuleId,

                                "value"=> $balance

                        ]

                        ];

                        $priceuleresponse = $this->updatePriceRule($priceRuleData, $storename, $token, $priceRuleId);

                        ShopifyCustomer::where('id', $CheckCustomers->shopifyCustomers["id"])->update([

                            "balance" => $balance,

                        ]);

                        $responseArr["PriceRuleStatus"] = "Price Rule Updated. Id: " . $priceRuleId;



                        $responseArr["DiscountCodeStatus"] = "Discount Code Already Exist";

                        $responseArr["DiscountCodeId"] = $CheckCustomers->shopifyCustomers["discount_code_id"];

                        $responseArr["DiscountCode"] = $CheckCustomers->shopifyCustomers["coupon_code"];

                        $responseArr["DiscountBalance"] = $CheckCustomers->shopifyCustomers["balance"];

                }

            }

            if($shopifyCustomerID != ''){

                //$sendMail = $this->sendGiftEmail($dashID, $shopifyCustomerID);

                //dd($sendMail);

                $crmOrders = new CrmOrder();

                $crmOrders->orderId = $response["order_id"];

                $crmOrders->shopify_customers_id = $shopifyCustomerID;

                $crmOrders->customerId = $response["customer_id"];

                $crmOrders->emailAddress = $response["email_address"];

                $crmOrders->phoneNumber = $response["customers_telephone"];

                $crmOrders->firstName = $response["first_name"];

                $crmOrders->lastName = $response["last_name"];

                $crmOrders->pid = implode(",", $CheckAllowedProductForGift);

                // $crmOrders->dateCreated = $response["acquisition_date"];

                $crmOrders->dashboard = $dashID;

                $crmOrders->api_response = json_encode($responseArr, true);

                $crmOrders->save();

                }

            }



        $returnType = isset($_REQUEST["return_type"]) ? $_REQUEST["return_type"] : "json";

        // dd($returnType);

        if ($returnType == "html" && !empty($responseArr["DiscountCode"])) {

            $html = "";

            $html .=

                "<tr><th>Shopify Coupon Code</th><td>" .

                $responseArr["DiscountCode"] .

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

            return response()->json([
                'responseArr' => json_encode($responseArr, true)
            ]);
            //echo json_encode($responseArr, true);

        }

    }



    public function index(){

        if (!Auth::user()) {

            abort(403, 'Unauthorized access');

        }

        $getDashData = Dashboard::with('crm','shopify','smtp')->where('status','=',1)->get();

        return view('dashboard.index', compact('getDashData'));

    }



    public function create(){

        $getCRMData = Crm::where('status','=',1)->get();

        $getShopifyData = Shopify::where('status','=',1)->get();

        $getSMTPData = Smtp::where('status','=',1)->get();

        return view('dashboard.create', compact('getCRMData','getShopifyData','getSMTPData'));

    }



    public function store(Request $request){

        $request->validate([

            'dashname' => 'required',

            'crmname' => 'required',

            'smtpname' => 'required',

            'shopifyname' => 'required',

            'products' => 'required'

        ],[
            'dashname.required' => 'The dashboard name field is required',

            'crmname' => 'The CRM provider name field is required',

            'smtpname' => 'The SMTP provider name field is required',

            'shopifyname' => 'The Shopify store name field is required',

            'products' => 'The allowed products field is required'
        ]);



        $saveDashData = new Dashboard();

        $saveDashData->dashname = $request->dashname;

        $saveDashData->crm_id = $request->crmname;

        $saveDashData->smtp_id = $request->smtpname;

        $saveDashData->shopify_id = $request->shopifyname;

        $products = explode(',',$request->products);

        $saveDashData->save();

        foreach($products as $row){

            $data = new Product();

            $data->dashboard_id = $saveDashData->id;

            $data->products = $row;

            $data->save();

        }

        return redirect()->route('dashboard.index')->with('success', 'Dashboard created successfully!');

    }



    public function edit($id)

    {

        $editDashboard = Dashboard::with('shopify','crm','smtp','products')->findOrFail($id);

        $getProducts = $editDashboard['products']->pluck('products')->toArray();

        $getProductsData = implode(',',$getProducts);

        $getCRMData = Crm::where('status','=',1)->get();

        $getShopifyData = Shopify::where('status','=',1)->get();

        $getSMTPData = Smtp::where('status','=',1)->get();

        

        return view('dashboard.edit',compact('editDashboard','getCRMData','getShopifyData','getSMTPData','getProductsData'));

    }



    // public function update(Request $request, Shopify $shopify)

    // {

    //     $request->validate([

    //         'shopifyapikey' => 'required',

    //         'shopifyapipassword' => 'required',

    //         'shopifyshopname' => 'required',

    //         'shopifydomainname' => 'required',

    //         'storeurl' => 'required'

    //     ]);

    //     $saveShopifyData = Shopify::findOrFail($shopify->id);

    //     $saveShopifyData->shopifyapikey = $request->shopifyapikey;

    //     $saveShopifyData->shopifyapipassword = $request->shopifyapipassword;

    //     $saveShopifyData->shopifyshopname = $request->shopifyshopname;

    //     $saveShopifyData->shopifydomainname = $request->shopifydomainname;

    //     $saveShopifyData->storeurl = $request->storeurl;

    //     if($saveShopifyData){

    //         $saveShopifyData->update();

    //         return redirect()->route('shopify.index')->with('success', 'Shopify Data added successfully!');

    //     }else{

    //         return redirect()->route('shopify.index')->with('error', 'Something went wrong!');

    //     }

    // }

    public function dashUpdate(Request $request){

        $request->validate([

            'dashname' => 'required',

            'crm_id' => 'required',

            'smtp_id' => 'required',

            'shopify_id' => 'required',

            'products' => 'required'

        ],
        [
            'dashname.required' => 'The dashboard name field is required',

            'crm_id.required' => 'The CRM provider name field is required',

            'smtp_id.required' => 'The SMTP provider name field is required',

            'shopify_id.required' => 'The Shopify store name field is required',

            'products.required' => 'The allowed products field is required'
        ]);

        $saveData = Dashboard::with('products')->findOrFail($request->id);

        $saveData->dashname = $request->dashname;

        $saveData->crm_id = $request->crm_id;

        $saveData->smtp_id = $request->smtp_id;

        $saveData->shopify_id = $request->shopify_id;

        $products = explode(',',$request->products);

        $getProducts = Product::where('dashboard_id', $request->id)->delete();

        $saveData->save();

        foreach ($products as $key => $value) {

            $data = new Product();

            $data->dashboard_id = $saveData->id;

            $data->products = $value;

            $data->save();

        }

        return redirect()->route('dashboard.index')->with('success', 'Dashboard updated successfully!');

    }



    public function shopifyToCrms(){

        //ini_set('max_execution_time', 120 ); // time in seconds

        $getData = DB::table('shopify_customers')->where('status','active')->limit(100)->get();

        foreach($getData as $value){

            $getcal = json_decode($value->crm_response, true);

            $pid = Helper::getDashboardId($getcal)['productID'][0] ? Helper::getDashboardId($getcal)['productID'][0] : '';

            $dashboardId = Helper::getDashboardId($getcal)['dashId'] ? Helper::getDashboardId($getcal)['dashId'] : '';

            if(CrmOrder::where('orderId', '=',$getcal['order_id'])->exists()){

                echo "OrderID already exists ".$getcal['order_id']."<br />";

            }else{

                $saveData = [

                    'orderId' => $getcal['order_id'],

                    'shopify_customers_id' => $value->id,

                    'customerId' => $getcal['customer_id'],

                    'emailAddress' => $getcal['email_address'],

                    'phoneNumber' => $getcal['customers_telephone'],

                    'firstName' => $getcal['first_name'],

                    'lastName' => $getcal['last_name'],

                    'pid' => $pid,

                    'api_response' => $value->crm_response,

                    'dashboard' => $dashboardId,

                    'status' => 1,

                    'created_at' => $getcal['created_at'],

                    'updated_at' => $getcal['updated_at'],

            ];

                $insertCRM = CrmOrder::insert($saveData);                

            }

        }

    }

    public function updateTableCRMOrder(){
        // $getData = DB::table('raw_crm_orders')->where('shopify_customers_id',0)->limit(500)->get();
        // foreach($getData as $value){
        //     $getcal = json_decode($value->api_response, true);
        //     if(DB::table('raw_shopify_customers')->where('shopify_customer_id', '=',$getcal['CustomerId'])->exists()){
        //         $getShopifyID = DB::table('raw_shopify_customers')->where('shopify_customer_id', '=',$getcal['CustomerId'])->first();
        //         DB::table('raw_crm_orders')->where('id','=',$value->id)
        //             ->where('shopify_customers_id',0)
        //             ->update(['shopify_customers_id' => $getShopifyID->id]);
        //     }else{
        //         $getShopifyID = DB::table('raw_shopify_customers')->where('shopify_customer_id', '=',$getcal['CustomerId'])->first();
        //         DB::table('raw_crm_orders')->where('id','=',$value->id)
        //             ->where('shopify_customers_id',0)
        //             ->update(['shopify_customers_id' => 'NA']);
        //     }
        // }
        $getData = DB::table('raw_crm_orders')->where('shopify_customers_id',0)->limit(100)->get();
        DB::transaction(function () use ($getData) {
        foreach($getData as $value){
            $getcal = json_decode($value->api_response, true);
            $checkData = DB::table('raw_shopify_customers')->where('shopify_customer_id', '=',$getcal['CustomerId'])
            ->select(['id'])
            ->first();
            //dd($checkData);
            if($checkData){
                DB::table('raw_crm_orders')->where('id','=',$value->id)
                    ->where('shopify_customers_id',0)
                    ->update(['shopify_customers_id' => $checkData->id]);
            }else{
                DB::table('raw_crm_orders')->where('id','=',$value->id)
                    ->where('shopify_customers_id',0)
                    ->update(['shopify_customers_id' => 'NA']);
            }
        }
        });
    }



    public function updatePIDNotRegData(){
        $getOrderID = DB::table('raw_shopify_notreg_data')->where('pid','=',0)->limit(100)->get();
        $sticky1 = Crm::where('status', '=', '1')->first();
        $apiurl = $sticky1->apiendpoint . "/api/v1/order_view";
        foreach($getOrderID as $value){
            $orderId = [
                'order_id' => $value->order_id
            ];

            // $apiurl = env("STICKY_URL");
            // $key = env("STICKY_API_USERNAME");
            // $pwd = env("STICKY_API_PASSWORD");
            $ViewOrder = $this->orderView($apiurl, $orderId, $sticky1->apiusername, $sticky1->apipassword);
            if($ViewOrder['response_code'] == 100){
                $oid = '';
                $data = '';
                $oid = $ViewOrder['order_id'];
                $data = $ViewOrder['products'][0]['product_id'];
                DB::table('raw_shopify_notreg_data')->where('order_id','=',$oid)
                            ->where('pid','0')
                            ->update([
                                    'pid' => $data,
                ]);
            }else{
                $oid = '';
                $data = '';
                $oid = $value->order_id;
                $data = 500;
                DB::table('raw_shopify_notreg_data')->where('order_id','=',$oid)
                            ->where('pid','0')
                            ->update([
                                    'pid' => $data,
                ]);
            }
            
        }

    }



    public function mainData(Request $request, $id)

    {
        if (!Auth::user()) {

            abort(403, 'Unauthorized access');

        }

        if(Auth::user()->role != 'admin'){
            abort(403, 'Unauthorized access');
        }


        if (empty($id)) {

            echo "Empty";

        } else {

            $data = [];

            $getDatas = [];

            $getDashboard = Helper::getDashboardName();

            global $dashboardId;

            $dashboardId = Helper::getIdfromUrl();

            if ($request->ajax()) {

                if ($request->filled('from_date') && $request->filled('to_date')) {

                        $now = \Carbon\Carbon::now();

                        $getDatas = DB::table('shopify_customers')

                        ->join('crm_orders', 'crm_orders.shopify_customers_id', '=', 'shopify_customers.id')

                        ->select(

                            'crm_orders.id AS ids',

                            'crm_orders.dashboard AS did',

                            'shopify_customers.id',

                            'shopify_customers.name',

                            'shopify_customers.email_address',

                            'shopify_customers.phone',

                            'shopify_customers.password',

                            'shopify_customers.coupon_code',

                            'shopify_customers.balance',

                            'shopify_customers.mail_status',

                            'shopify_customers.created_at'

                        )

                            ->whereBetween('shopify_customers.created_at', [$request->from_date, $request->to_date])

                            ->where('crm_orders.dashboard', '=', $id)

                            ->distinct()

                            ->groupBy('shopify_customers.email_address');

                } else {

                    $now = Carbon::now();

                    $getDatas = Helper::getCrmShopifyData($id);

                }

                

                return DataTables::of($getDatas)

                    ->addColumn('balance', function ($row) {

                        if(isset($row->balance)){

                            $balance = Str::replace('-', '$', $row->balance);

                            return $balance;

                        }

                    })

                    ->addColumn('action', function ($row) {

                        $html = '<a data-id="' . $row->id . '" data-dashid="' . $row->did . '" data-crmid="' . $row->ids . '" class="btn btn-success btn-sm edit-details" style="margin:3px;"><i class="fa fa-edit"></i> Edit</a>';

                        if($row->mail_status == 'Sent'){

                            $html .= '<a data-id="' . $row->id . '"  data-crmid="' . $row->ids . '" class="btn btn-danger btn-sm sendmail"><i class="fa fa-envelope"></i> Re-Send Mail</a>';

                        }else{

                            $html .= '<a data-id="' . $row->id . '" data-crmid="' . $row->ids . '" class="btn btn-danger btn-sm sendmail"><i class="fa fa-envelope"></i> Send Mail</a>';

                        }

                        

                        return $html;

                    })

                    ->addColumn('created_at', function ($row) {

                        $date = date("F d, Y", strtotime($row->created_at));

                        return $date;

                    })
                    
                    ->editColumn('phone', function($row) {
                        return (string)$row->phone;
                    })

                    ->with([

                        'type' => $request->type,

                        'getData' => $getDatas

                    ])

                    ->make(true);

            }

            return view('main',compact('getDashboard'));

        }

    }

    public function failedData(Request $request, $id)

    {
        if (!Auth::user()) {

            abort(403, 'Unauthorized access');

        }

        //$getDatas1 = [];

        $type = '';

        $getDashboard = Helper::getDashboardName();

        if ($request->ajax()) {

            if ($request->filled('from_date') && $request->filled('to_date')) {
                
                //$productId = DB::table('shopify_notreg_data')->whereBetween('created_at', [$request->from_date, $request->to_date])->pluck('pid')->toArray();
                $getProducts = Product::where('dashboard_id', '=', $id)->pluck('products')->toArray();
                
                $getDatas1 = DB::table('shopify_notreg_data')
                                      ->select('id','order_id','email','error_msg','created_at')
                                      ->whereIn('pid', $getProducts)
                                      ->whereBetween('created_at', [$request->from_date, $request->to_date])
                                      ->get();

            }else{

                $orderId = DB::table('shopify_notreg_data')->pluck('pid')->toArray();
                //$getDatas1 = Helper::failedData($id, $orderId);
                $getProducts = Product::where('dashboard_id', '=', $id)->pluck('products')->toArray();
                $getDatas1 = DB::table('shopify_notreg_data')
                                      ->select('id','order_id','email','error_msg','created_at')
                                      ->whereIn('pid', $getProducts)
                                      ->get();
            }
            return DataTables::of($getDatas1)
                    
                    ->addColumn('created_at', function ($row) {
                        if(isset($row->created_at)){
                        $date = date("F d, Y", strtotime($row->created_at));

                        return $date;
                        }
                    })
                    ->editColumn('order_id', function($row) {
                        return (string)$row->order_id;
                    })
                    ->editColumn('id', function($row) {
                        return (string)$row->id;
                    })
                    ->with([

                        'type' => $request->type,

                        'getData' => $getDatas1

                    ])

                    ->make(true);

        }

        return view('main-failed',compact('getDashboard'));

    }

}