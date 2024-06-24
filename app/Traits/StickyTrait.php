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

    public function orderView1($apiurl1, $DataQuery1){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $apiurl1,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($DataQuery1),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic Y2NfZGV2X2FwaTp5bUFScXVlbnNNWXd1'
        ),
        ));

        $data = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($data, true);

        return $response;
    }

}

?>