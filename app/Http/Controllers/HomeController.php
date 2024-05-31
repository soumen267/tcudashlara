<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Crm;
use App\Models\Smtp;
use App\Models\Product;
use App\Models\Shopify;
use App\Models\CrmOrder;
use App\Models\Dashboard;
use Illuminate\View\View;
use App\Traits\StickyTrait;
use Illuminate\Support\Str;
use App\Traits\ShopifyTrait;
use Illuminate\Http\Request;
use App\Models\ShopifyCustomer;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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

    public function create(){
        $getCRMData = Crm::where('status','=',1)->get();
        $getShopifyData = Shopify::where('status','=',1)->get();
        $getSMTPData = Smtp::where('status','=',1)->get();
        return view('dash.create', compact('getCRMData','getShopifyData','getSMTPData'));
    }

    public function store(Request $request){
        $request->validate([
            'dashname' => 'required',
            'crmname' => 'required',
            'smtpname' => 'required',
            'shopifyname' => 'required',
            'products' => 'required'
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
        return redirect()->route('home')->with('success', 'Dashboard created successfully!');
    }

    public function mainData(Request $request, $id)
    {
        
        if (empty($id)) {
            die();
        } else {
            $columns = [];
            $type = '';
            $data = [];
            $getDatas = [];
            $getAllowedProduct = '';
            $getDashboard = Dashboard::where('status', '=', 1)->get(['id', 'dashname']);
            $getDashboards = Dashboard::with('crm', 'shopify', 'smtp')->where('status', '=', 1)->where('id', '=', $id)->first();
            $getAllowedProducts = Dashboard::with('products')->where('status', '=', 1)->where('id', '=', $id)->get();
            foreach ($getAllowedProducts as $products) {
                $data = $products->products->pluck('products');
                $getAllowedProduct = $data->implode(',');
            }
            if ($request->ajax()) {
                if ($request->filled('from_date') && $request->filled('to_date')) {
                    if ($request->type == 'failed') {
                        $orderId = DB::table('shopify_notreg_data')->whereBetween('created_at', [$request->from_date, $request->to_date])->pluck('pid')->toArray();
                        $getProducts = Product::where('dashboard_id', '=', $id)->pluck('products')->toArray();
                        $CheckAllowedProduct = array_intersect(
                            $orderId,
                            $getProducts
                        );
                        
                        foreach ($CheckAllowedProduct as $row) {
                            $getData = DB::table('shopify_notreg_data')->where('pid', '=', $row)
                                        ->select('id','order_id','email','error_msg','created_at')
                                        ->first();
                            array_push($getDatas, $getData);
                        }
                    } elseif ($request->type == 'success') {
                        $now = \Carbon\Carbon::now();
                        $getDatas = DB::table('crm_orders')
                        ->join('shopify_customers', 'crm_orders.shopify_customers_id', '=', 'shopify_customers.id')
                        ->select(
                            'crm_orders.id',
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
                            ->where('dashboard', '=', $id);
                    }
                } else {
                    $now = Carbon::now();
                    $getDatas = DB::table('crm_orders')
                    ->join('shopify_customers', 'crm_orders.shopify_customers_id', '=', 'shopify_customers.id')
                    ->select(
                        'crm_orders.id',
                        'shopify_customers.name',
                        'shopify_customers.email_address',
                        'shopify_customers.phone',
                        'shopify_customers.password',
                        'shopify_customers.coupon_code',
                        'shopify_customers.balance',
                        'shopify_customers.created_at',
                        'shopify_customers.mail_status',
                        'shopify_customers.created_at'
                    )
                    ->where('dashboard', '=', $id);
                }
                if($request->type == 'failed'){
                    $columns = ['Id','Order Id','Email','Error','Created Date'];
                }else{
                    $columns = [
                        'ID','Name','Contact Details','Password','Coupon Code','Balance','Mail Status','Created Date','Action'
                    ];
                }
                return DataTables::of($getDatas)
                    ->addColumn('balance', function ($row) {
                        if(isset($row->balance)){
                            $balance = Str::replace('-', '$', $row->balance);
                            return $balance;
                        }
                    })
                    ->addColumn('action', function ($row) {
                        $html = '<a data-id="' . $row->id . '" data-dashid="' . $row->id . '" class="btn btn-success btn-sm edit-details" style="margin:3px;">Edit</a>';
                        $html .= '<a data-id="' . $row->id . '" data-dashid="' . $row->id . '" class="btn btn-danger btn-sm sendmail">Send Mail</a>';
                        return $html;
                    })
                    ->addColumn('created_at', function ($row) {
                        $date = date("F d, Y", strtotime($row->created_at));
                        return $date;
                    })                    
                    ->with([
                        'columns' => $columns,
                        'type' => $request->type
                    ])
                    ->make(true);
            }
            return view('main', compact('getDashboard', 'getDashboards', 'getAllowedProducts', 'getAllowedProduct'));
            //return view('main');
            // return view('main', compact('getDatas', 'getDashboard','getDashboards','getAllowedProducts','getAllowedProduct'));
        }
    }
    public function getData(Request $request){
        $getData = DB::table('crm_orders')
                    ->join('shopify_customers', 'crm_orders.shopify_customers_id', '=', 'shopify_customers.id')
                    ->select('crm_orders.id',
                            'shopify_customers.name',
                            'shopify_customers.email_address',
                            'shopify_customers.password',
                            'shopify_customers.phone',
                            'shopify_customers.coupon_code',
                            'shopify_customers.balance',
                    )->where('crm_orders.id', $request->id)->first();
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
            'message'=>'Project updated successfully!',
        ]);
        }else{
        $CheckCustomer = CrmOrder::with('shopifyCustomers')->where('id', $request->update_id)->first();
        // dd($CheckCustomer);
        if($CheckCustomer){
            $customerId = $CheckCustomer->shopifyCustomers->shopify_customer_id ? $CheckCustomer->shopifyCustomers->shopify_customer_id : "";
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
            $CustomerResponse = $this->updateCustomer($CustomerData, $customerId);
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
                die();
            }else{
                $CheckShopifyCustomer = ShopifyCustomer::where('id', $CheckCustomer['shopifyCustomers']['id'])->first();
                if($CheckShopifyCustomer){
                    $saveCustomer = ShopifyCustomer::where('id', $CheckCustomer['shopifyCustomers']['id'])->update([
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
            'order_id'=> 'required',
        ]);
        $apiurl = env("STICKY_URL");
        $key = env("STICKY_API_USERNAME");
        $pwd = env("STICKY_API_PASSWORD");
        $responseArray = [];
        $orderId = [
            'order_id' => $request->order_id
        ];
        $ViewOrder = $this->orderView($apiurl, $orderId, $key, $pwd);
        if($ViewOrder["response_code"] != 100){
            $html = '<tr><td colspan="2">'.$ViewOrder['error_message'].'</td></tr>';
            echo $html;
            die();
        }
        $ordersProduct = [];
        foreach ($ViewOrder['products'] as $key => $order_offer) {
            $ordersProduct[] =  $order_offer['product_id'];
        }
        $getProducts = Product::where('dashboard_id','=',$request->dashid)->get()->pluck('products')->toArray();
        if($request->credit == '0'){
        $CheckAllowedProduct = array_intersect($ordersProduct,$getProducts);
            // dd($CheckAllowedProduct);
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
        $CheckCustomer = ShopifyCustomer::where('email_address', '=', $ViewOrder['email_address'])->first();
        // dd($CheckCustomer);
        if($CheckCustomer){
            // $CheckCustomer = $CheckCustomer[0];
            $responseArray['user_msg'] = '<span class="text-danger">Customer Already Exists In Shopify</span>';
            $responseArray['user_status'] = false;
            $responseArray['shopify_customer_id'] = $CheckCustomer['shopify_customer_id'];
            $responseArray['shopify_customer_useremail'] =  $CheckCustomer['email_address'];
            $responseArray['shopify_password'] =  $CheckCustomer['password'];
            $responseArray['coupon_code'] =  $CheckCustomer['coupon_code'];
            $responseArray['balance'] =  $CheckCustomer['balance'];
            
            if($CheckCustomer['crm_response'] == NULL){
                $CheckShopifyCustomer = ShopifyCustomer::where('id', $CheckCustomer['id'])->first();
                if($CheckShopifyCustomer){
                    $saveCustomer = ShopifyCustomer::where('id', $CheckCustomer['shopifyCustomers']['id'])->update([
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
        echo $html;        
    }
}
