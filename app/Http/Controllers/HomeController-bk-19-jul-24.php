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

use App\Traits\StickyTrait;

use Illuminate\Support\Str;

use App\Traits\ShopifyTrait;

use Illuminate\Http\Request;

use App\Models\ShopifyCustomer;

use Yajra\DataTables\DataTables;

use App\Models\ShopifyNotregData;

use Illuminate\Support\Facades\DB;



class HomeController extends Controller

{

    use ShopifyTrait, StickyTrait;

    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

        $this->middleware('auth');

    }



    /**

     * Show the application dashboard.

     *

     * @return \Illuminate\Contracts\Support\Renderable

     */

    public function index()

    {

        $getData = Dashboard::where('status','=',1)->get();

        return view('home',compact('getData'));

    }



    public function getDashData(Request $request){

        $getDashboards = Dashboard::with('crm', 'shopify', 'smtp')->where('status', '=', 1)->where('id', '=', $request->id)->first();

        $getAllowedProducts1 = Dashboard::with('products')->where('status', '=', 1)->where('id', '=', $request->id)->get();

            foreach ($getAllowedProducts1 as $products) {

                $data = $products->products->pluck('products');

                $getAllowedProduct = $data->implode(',');

        }

        return response()->json([

            "getDashboards" => $getDashboards,

            "getAllowedProduct" => $getAllowedProduct

         ]

        );

    }

    public function getData(Request $request){

        $getData = DB::table('crm_orders')

                    ->join('shopify_customers', 'crm_orders.shopify_customers_id', '=', 'shopify_customers.id')

                    ->select('shopify_customers.id',

                            'shopify_customers.name',

                            'shopify_customers.email_address',

                            'shopify_customers.password',

                            'shopify_customers.phone',

                            'shopify_customers.coupon_code',

                            'shopify_customers.balance',

                    )->where('shopify_customers.id', $request->id)->first();

        return response()->json($getData);

    }





    public function customerUpdate(Request $request){

        

        $request->validate([

            'update_id' => 'required',

            'update_fname' => 'required',

            'update_lname' => 'required',

            'update_email' => 'required|email',

            'update_phone' => 'required'

        ],[

            'update_fname.required' => 'Firstname is required!',

            'update_lname.required' => 'Lastname is required!',

            'update_email.required' => 'Email is required!',

            'update_phone.required' => 'Phone is required!',

        ]

        );

        if(empty($request->all())){

        return response()->json([

            'getProjectsData' => $request->all(),

            'status'=> 200,

            'message'=>'Something went wrong!',

        ]);

        }else{

        //$CheckCustomer = CrmOrder::with('shopifyCustomers')->where('id', $request->update_id)->first();

        $ID = $request->update_id;

        $dashID = $request->dashboard;

        $email = $request->update_email;

        //$CheckCustomer = ShopifyCustomer::where('id', $request->update_id)->where('email_address',$request->update_email)->first();

        $CheckCustomer = CrmOrder::with(['shopifyCustomers'])

                                    ->whereHas('shopifyCustomers', function($q) use ($ID, $email){

                                            $q->where('id', $ID);

                                            $q->where('email_address', $email);

        })

        ->where('dashboard',$dashID)

        ->first();

        $getDatas = Dashboard::with('crm','shopify','smtp')->where('id','=',$dashID)->first();

        $storename = $getDatas->shopify['storeurl'];

        $token = $getDatas->shopify['shopifyapipassword'];



        // dd($CheckCustomer);

        if($CheckCustomer){

            // $customerId = $CheckCustomer->shopifyCustomers->shopify_customer_id ? $CheckCustomer->shopifyCustomers->shopify_customer_id : "";

            $customerId = $CheckCustomer->shopifyCustomers->shopify_customer_id ? $CheckCustomer->shopifyCustomers->shopify_customer_id : "";

            //dd($customerId);

            settype($customerId, "integer");

            $CustomerData = [

                    "customer" => [

                        'id' => $customerId,

                        'first_name' => $request->update_fname,

                        'last_name' => $request->update_lname,

                        'email' => $request->update_email,

                        'phone' => $request->update_phone

                ]

            ];

            // dd(json_encode($CustomerData));

            $CustomerResponse = $this->updateCustomer($CustomerData, $customerId, $storename, $token);

            //dd($CustomerResponse);

            if ($CustomerResponse['code'] == '422') {

                $json = $CustomerResponse['msg'];

                $error = json_decode($json, true);

                $error_reason = "";

                foreach ($error["errors"] as $key => $value) {

                    $error_reason .=

                        $key . " " . $error["errors"][$key][0] . " & ";

                }

                $error_reason = substr($error_reason, 0, -2);

                return response()->json([

                    'status' => 'false',

                    'error_code' => $CustomerResponse['code'],

                    'error_reason' => $error_reason

                ]);

            }else{

                // $CheckShopifyCustomer = ShopifyCustomer::where('id', $CheckCustomer['shopifyCustomers']['id'])->first();

                $CheckShopifyCustomer = ShopifyCustomer::where('id', $CheckCustomer->shopifyCustomers['id'])->first();

                if($CheckShopifyCustomer){

                    //ShopifyCustomer::where('id', $CheckCustomer['shopifyCustomers']['id'])->update([

                    $saveCustomer = ShopifyCustomer::where('id', $CheckCustomer->shopifyCustomers['id'])->update([

                        "name" => $CustomerResponse['msg']['customer']["first_name"] . " " . $CustomerResponse['msg']['customer']["last_name"],

                        "email_address" => $CustomerResponse['msg']['customer']['email'],

                        "phone" => $CustomerResponse['msg']['customer']['phone'],

                        "webhook_response" => json_encode($CustomerResponse['msg'],true)

                    ]);

                    return response()->json([

                        'status' => true,

                        'shopify_update' => true,

                        'db_update' => true

                    ]);

                }else{

                    return response()->json([

                        'status' => true,

                        'shopify_update' => true,

                        'db_update' => false

                    ]);

                }

            }

        }        

    }

    }

   

    public function orderCheck(Request $request){
        $request->validate([

            'dashid' => 'required',

            'order_id'=> 'required|numeric',

        ]);

        $sticky = Crm::where('status', '=', '1')->first();

        $apiurl = $sticky->apiendpoint . "/api/v1/order_view";

        $key = $sticky->apiusername;

        $pwd = $sticky->password;

        $responseArray = [];

        $DataQuery = [

            'order_id' => $request->order_id

        ];

        $ViewOrder = $this->orderView1($apiurl, $DataQuery, $key, $pwd);

        $orderCheck = CrmOrder::where('orderId',$request->order_id)->first();

        if($ViewOrder["response_code"] == 100){

            $checkAllowedDashboard = Helper::getDashboardId($ViewOrder);

        }        

        if($ViewOrder["response_code"] != 100 && $ViewOrder["response_code"] != 350){

            return response()->json([
                'getMessage' => $ViewOrder['error_message'],
                'error_code' => 350
            ]);            

        }else if($ViewOrder["response_code"] == 350){

            return response()->json([
                'getMessage' => "Invalid Order ID",
                'error_code' => 350
            ]);


        }else if($orderCheck){

            return response()->json([
                'getMessage' => "Order Already Exists",
                'error_code' => 350
            ]);

        }else if($checkAllowedDashboard['dashId'] != $request->dashid){

            return response()->json([
                'getMessage' => "Order not allowed for this dashboard",
                'error_code' => 350
            ]);

        }else{

        $ordersProduct = [];

        foreach ($ViewOrder['products'] as $key => $order_offer) {

            $ordersProduct[] =  $order_offer['product_id'];

        }

        $getProducts = Product::where('dashboard_id','=',$request->dashid)->get()->pluck('products')->toArray();

        if($request->credit == '0'){

        $CheckAllowedProduct = array_intersect($ordersProduct,$getProducts);

            if (sizeof($CheckAllowedProduct) > 0) {

                $responseArray['creation_msg'] = '<span class="text-success">Order Allowed For Account Creation</span>';

                $responseArray['creation_status'] = true;

                $responseArray['emailAddress'] = $ViewOrder['email_address'];

                $responseArray['phoneNumber'] = $ViewOrder['customers_telephone'];

                $responseArray['name'] = $ViewOrder['first_name'] . " " . $ViewOrder['last_name'];

            }else{

                $responseArray['creation_msg'] = '<span class="text-danger">Order Not Allowed For Account Creation</span>';

                $responseArray['creation_status'] = false;

                $responseArray['emailAddress'] = "---";

                $responseArray['phoneNumber'] =  "---";

                $responseArray['name'] =  "---";

            }

        } else {

            $responseArray['creation_msg'] = '<span class="text-success">Order Allowed For Account Creation</span>';

            $responseArray['creation_status'] = true;

            $responseArray['emailAddress'] = $ViewOrder['email_address'];

            $responseArray['phoneNumber'] = $ViewOrder['customers_telephone'];

            $responseArray['name'] = $ViewOrder['first_name'] . " " . $ViewOrder['last_name'];

        }

        $customerEmail = $ViewOrder["email_address"];

        $CheckCustomer = CrmOrder::with(['shopifyCustomers'])->whereHas('shopifyCustomers', function($q) use ($customerEmail){

                                            $q->where('email_address', $customerEmail);

                                        })

                                        ->where('dashboard',$request->dashid)

                           ->first();

        if($CheckCustomer != null){

            // $CheckCustomer = $CheckCustomer[0];

            $responseArray['user_msg'] = '<span class="text-danger">Customer Already Exists In Shopify</span>';

            $responseArray['user_status'] = false;

            $responseArray['shopify_customer_id'] = $CheckCustomer->shopifyCustomers['shopify_customer_id'];

            $responseArray['shopify_customer_useremail'] =  $CheckCustomer->shopifyCustomers['email_address'];

            $responseArray['shopify_password'] =  $CheckCustomer->shopifyCustomers['password'];

            $responseArray['coupon_code'] =  $CheckCustomer->shopifyCustomers['coupon_code'];

            $responseArray['balance'] =  $CheckCustomer->shopifyCustomers['balance'];

            

            if($CheckCustomer['crm_response'] == NULL){

                $CheckShopifyCustomer = ShopifyCustomer::where('id', $CheckCustomer->shopifyCustomers['id'])->first();

                if($CheckShopifyCustomer){

                    $saveCustomer = ShopifyCustomer::where('id', $CheckCustomer->shopifyCustomers['id'])->update([

                        'crm_response' => json_encode($ViewOrder,true),

                    ]);

                }

            }

            

        }else{

            $responseArray['user_msg'] = '<span class="text-success">Customer Can Be Created</span>';

            $responseArray['user_status'] = true;

            $responseArray['shopify_customer_id'] =  "---";

            $responseArray['shopify_customer_useremail'] =  "---";

            $responseArray['shopify_password'] =  "---";

            $responseArray['coupon_code'] =  "---";

            $responseArray['balance'] =  "---";

        }

        

        $html ="";

        $html .= '<tr>

                    <th width="50%">Creation Status</th>

                    <td width="50%">'.$responseArray['creation_msg'].'</td>

                </tr>';

        $html .= '<tr><th>Email Address</th><td>'.$responseArray['emailAddress'].'</td></tr>';

        $html .= '<tr><th>Phone Number</th><td>'.$responseArray['phoneNumber'].'</td></tr>';

        $html .= '<tr><th>Name</th><td>'.$responseArray['name'].'</td></tr>';

        $html .= '<tr><th>User Status</th><td>'.$responseArray['user_msg'].'</td></tr>';

        if($responseArray['user_status'] && $responseArray['creation_status']){

            if($request->credit == '1'){

                $html .= '<tr><th>Enter Coupon Value</th><th><input type="text" class="form-control w-100" id="coupon_val" value="75" name="coupon_val" placeholder="Coupon Value required"></tr>';

            }

            $html .= '<tr><td colspan="2"><a href="javascript:void(0);" class="btn btn-danger p-2 create_customer" data-id="'.$request->order_id.'">Create Customer</a></td></tr>';

        }else{

            $html .= '<tr><th>Shopify Coupon Code</th><td>'.$responseArray['coupon_code'].'</td></tr>';

            $html .= '<tr><th>Shopify Coupon Balance</th><td>'.str_replace("-","$",$responseArray['balance']).'</td></tr>';

            $html .= '<tr><th>Shopify Customer Id</th><td>'.$responseArray['shopify_customer_id'].'</td></tr>';

            $html .= '<tr><th>Shopify Customer Email</th><td>'.$responseArray['shopify_customer_useremail'].'</td></tr>';

            $html .= '<tr><th>Shopify Customer Password</th><td>'.$responseArray['shopify_password'].'</td></tr>';

        }

        }

        echo $html;  

        // return response()->json([
        //     'responseArray' => $responseArray,
        // ]);

    }

}

