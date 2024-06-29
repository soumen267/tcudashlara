<?php
namespace App\Helpers;

use App\Models\Product;
use App\Models\Dashboard;
use Illuminate\Support\Facades\DB;

class Helper
{

    public static function getDashboardName(){
        $getDashboard = Dashboard::where('status', '=', 1)->get(['id', 'dashname']);
        return $getDashboard;
    }

    public static function getDashboardId($data){
        $getProducts = Product::get()->pluck('products')->toArray();
        $CheckAllowedProduct = [];
        $ProductPriceArr = [];
        // $getData = DB::table('raw_shopify_customers')->get();
        // $getcal = json_decode($getData->crm_response, true);
            foreach ($data['products'] as $key => $order_offer) {
                $ordersProduct[] = $order_offer["product_id"];
                $ProductPriceArr[$order_offer["product_id"]] = $order_offer["price"];
            }
            $CheckAllowedProduct = array_intersect(
                $ordersProduct,
                $getProducts
            );
            if (sizeof($CheckAllowedProduct) > 0) {
                foreach ($CheckAllowedProduct as $pkey => $pid) {
                    $getProd = Product::where('products', '=', $pid)->first();
                    $dashId = $getProd->dashboard_id;
                }
            }
        $getData = [
            'dashId' => $dashId,
            'productID' => $CheckAllowedProduct
        ];
        return $getData;
    }

    public function getAllProducts($id){
        $getAllowedProducts = Dashboard::with('products')->where('status', '=', 1)->where('id', '=', $id)->get();
        foreach ($getAllowedProducts as $products) {
                $data = $products->products->pluck('products');
                $getAllowedProduct = $data->implode(',');
        }
        return $getAllowedProduct;
    }

    public static function getCrmShopifyData($id){
        // $dashboardId = [];
        // $data = $getData = DB::table('shopify_customers')->where('status','active')->limit(100)->get();
        // foreach($getData as $value){
        //     dd($value);
        //     $getcal = json_decode($value->crm_response, true);
        //     //$pid = Helper::getDashboardId($getcal)['productID'][0] ? Helper::getDashboardId($getcal)['productID'][0] : '';
        //     $dashboardId[] = Helper::getDashboardId($getcal)['dashId'] ? Helper::getDashboardId($getcal)['dashId'] : '';
        //     dd($dashboardId);
        // }
        $data = DB::table('shopify_customers')
                ->join('crm_orders', 'crm_orders.shopify_customers_id', '=', 'shopify_customers.id')
                ->select(
                    'crm_orders.id AS ids',
                    'crm_orders.shopify_customers_id',
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
                ->where('crm_orders.dashboard', '=', $id)
                ->distinct()
                ->groupBy('shopify_customers.email_address');
        return $data;
    }

    public static function failedData($id, $orderId){
        $getDatas1 = [];
        $getProducts = Product::where('dashboard_id', '=', $id)->pluck('products')->toArray();
                        $CheckAllowedProduct = array_intersect(
                            $orderId,
                            $getProducts
                        );
                        
                foreach ($CheckAllowedProduct as $row) {
                            $getData = DB::table('shopify_notreg_data')->where('pid', '=', $row)
                                        ->select('id','order_id','email','error_msg','created_at')
                                        ->first();
                        array_push($getDatas1, $getData);
                }
        return $getDatas1;
    }

    public static function getIdfromUrl(){
        $mylink = $_SERVER['REQUEST_URI'];
        $link_array = explode('/',$mylink);
        $lastpart = end($link_array);
        
        return $lastpart;
    }
}

?>