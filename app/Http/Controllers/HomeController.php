<?php

namespace App\Http\Controllers;

use App\Models\Crm;
use App\Models\CrmOrder;
use App\Models\Dashboard;
use App\Models\Product;
use App\Models\Shopify;
use App\Models\Smtp;
use Illuminate\Http\Request;

class HomeController extends Controller
{
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

    public function mainData(Request $request, $id){
        if(empty($id)){
            die();
        }else{
            $data = [];
            $getDashboard = Dashboard::where('status','=',1)->get(['id','dashname']);
            $getDashboards = Dashboard::with('crm','shopify','smtp')->where('status','=',1)->where('id','=',$id)->first();
            $getAllowedProducts = Dashboard::with('products')->where('status','=',1)->where('id','=',$id)->get();
            foreach($getAllowedProducts as $products){
                $data = $products->products->pluck('products');
            }
            $getAllowedProduct = $data->implode(',');
            $getDatas = CrmOrder::with("shopifyCustomers")->where('dashboard','=',$id)->get();
            return view('main', compact('getDatas', 'getDashboard','getDashboards','getAllowedProduct'));
        }
    }
}
