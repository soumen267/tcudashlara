<?php 
namespace App\Traits;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

trait StickyTrait {

    public function orderView($apiurl, $DataQuery, $key, $pwd){
        $client = new \GuzzleHttp\Client();
        $request = $client->request('POST', $apiurl, [
              'headers' => [
                'Content-Type' => 'application/json',
                ],
            'auth' => [$key, $pwd],
            'query' => $DataQuery
           ]); // Url of your choosing
           $res_body = $request->getBody()->getContents();
           $response = json_decode($res_body, true);
           return $response;
    }
}
?>