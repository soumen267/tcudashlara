<?php

namespace App\Http\Controllers;

use App\Models\Shopify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopifyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()) {
            abort(403, 'Unauthorized access');
        }
        $getShopifyData = Shopify::all();
        return view('shopify.index',compact('getShopifyData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shopify.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shopifyapikey' => 'required',
            'shopifyapipassword' => 'required',
            'shopifyshopname' => 'required',
            'shopifydomainname' => 'required',
            'storeurl' => 'required',
            'shopifywebhookhash'=> 'required'
        ]);
        $saveShopifyData = new Shopify();
        $saveShopifyData->shopifyapikey = $request->shopifyapikey;
        $saveShopifyData->shopifyapipassword = $request->shopifyapipassword;
        $saveShopifyData->shopifyshopname = $request->shopifyshopname;
        $saveShopifyData->shopifydomainname = $request->shopifydomainname;
        $saveShopifyData->storeurl = $request->storeurl;
        $saveShopifyData->shopifywebhookhash = $request->shopifywebhookhash;
        if($saveShopifyData){
            $saveShopifyData->save();
            return redirect()->route('shopify.index')->with('success', 'Shopify Data added successfully!');
        }else{
            return redirect()->route('shopify.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Shopify $shopify)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shopify $shopify)
    {
        $editShopify = Shopify::findOrFail($shopify->id);
        return view('shopify.edit',compact('editShopify'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shopify $shopify)
    {
        $request->validate([
            'shopifyapikey' => 'required',
            'shopifyapipassword' => 'required',
            'shopifyshopname' => 'required',
            'shopifydomainname' => 'required',
            'storeurl' => 'required',
            'shopifywebhookhash'=> 'required'
        ]);
        $saveShopifyData = Shopify::findOrFail($shopify->id);
        $saveShopifyData->shopifyapikey = $request->shopifyapikey;
        $saveShopifyData->shopifyapipassword = $request->shopifyapipassword;
        $saveShopifyData->shopifyshopname = $request->shopifyshopname;
        $saveShopifyData->shopifydomainname = $request->shopifydomainname;
        $saveShopifyData->storeurl = $request->storeurl;
        $saveShopifyData->shopifywebhookhash = $request->shopifywebhookhash;
        if($saveShopifyData){
            $saveShopifyData->update();
            return redirect()->route('shopify.index')->with('success', 'Shopify Data added successfully!');
        }else{
            return redirect()->route('shopify.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shopify $shopify)
    {
        $data = Shopify::find($shopify->id)->delete();
        return redirect(route('shopify.index'))->with('success', 'Shopify data deleted successfully!');
    }

    public function changeStatus(Request $request, $id, $status){
        $changeStat = Shopify::findOrFail($id);
        if($changeStat){
            $changeStat->status = $status;
            $changeStat->update();
            return redirect()->route('shopify.index')->with('success', 'Status updated successfully!');
        }
    }
}
