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

    public function getAllProducts($id){
        $getAllowedProducts = Dashboard::with('products')->where('status', '=', 1)->where('id', '=', $id)->get();
        foreach ($getAllowedProducts as $products) {
                $data = $products->products->pluck('products');
                $getAllowedProduct = $data->implode(',');
        }
        return $getAllowedProduct;
    }

    public static function getCrmShopifyData($id){
        $data = DB::table('crm_orders')
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
        $mylink = $_SERVER['PHP_SELF'];
        $link_array = explode('/',$mylink);
        $lastpart = end($link_array);
        
        return $lastpart;
    }
}

?>