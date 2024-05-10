<?php

namespace App\Http\Controllers;

use App\Models\Crm;
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
}
