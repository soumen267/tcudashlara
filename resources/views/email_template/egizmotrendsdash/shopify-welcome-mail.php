<?php


require_once "require.php";
use Mailgun\Mailgun; 
use \Mailjet\Resources;

$isForced = isset($_REQUEST['is_forced']) ? $_REQUEST['is_forced'] : '0';
if($isForced == '0'){
    define('SHOPIFY_APP_SECRET', __SHOPIFYWEBHOOKHASH__);
    function verify_webhook($data, $hmac_header)
    {
      $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
      return hash_equals($hmac_header, $calculated_hmac);
    }
    $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
    $ShopifyCustomerRawData = file_get_contents('php://input');
    $verified = verify_webhook($ShopifyCustomerRawData, $hmac_header);
    $ShopifyCustomerData = json_decode($ShopifyCustomerRawData,true);
}else{
    $emailId = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
    if($emailId != ""){
        $ShopifyCustomerData['email'] = $emailId;
    }
}

    function retry($db, $email, $maxRetries = 2,  $currentRetry = 0){
        try{
            $db ->where ('email_address', $email);
            $CheckCustomer = $db->getOne('shopify_customers');
            if($CheckCustomer['coupon_code'] == null || $CheckCustomer['balance'] == "0"){
                throw new \Exception("something went wrong. got unknown faultcode or different error");
            }
            else{
                return $CheckCustomer;
            }
        }catch (\Exception $e){
            if ($currentRetry < $maxRetries) {
                // Wait for 3 seconds before retrying
                sleep(2);

                // Retry the function recursively
                 return retry($db,$email, $maxRetries, $currentRetry + 1);
            } else {
                // Maximum number of retries reached, handle the error as needed
                throw $e;
            }
        }
        

        }


if($ShopifyCustomerData){    

    $CheckCustomer = retry($db,$ShopifyCustomerData['email']);
    // print_r($CheckCustomer);

    if($CheckCustomer['mail_status'] == "Not Sent" || $isForced == '1'){

        $EMAIL_TEMPLATE = file_get_contents(__BASIC_EMAIL_SETTING__['template_path']);
        // STORE RELATED TOKENS REPLACE
        $EMAIL_TEMPLATE = str_replace("{{__STORE_URL__}}",__STOREURL__,$EMAIL_TEMPLATE);
        $EMAIL_TEMPLATE = str_replace("{{__SHOPIFY_LOGIN_URL__}}",'https://'.__STOREURL__.'/account/login',$EMAIL_TEMPLATE);
        $EMAIL_TEMPLATE = str_replace("{{__STORE_NAME__}}",__SHOPIFYSTORENAME__,$EMAIL_TEMPLATE);
        $EMAIL_TEMPLATE = str_replace("{{__FROM_EMAIL_ID__}}",__FROM_EMAIL_ID__,$EMAIL_TEMPLATE);
        $EMAIL_TEMPLATE = str_replace("{{__FROM_NAME__}}",__FROM_NAME__,$EMAIL_TEMPLATE);

        // CUSTOMERE RELATED TOKENS REPLACE
        $EMAIL_TEMPLATE = str_replace("{{__CUSTOMER_NAME__}}",$CheckCustomer['name'],$EMAIL_TEMPLATE);
        $EMAIL_TEMPLATE = str_replace("{{__CUSTOMER_EMAIL__}}",$CheckCustomer['email_address'],$EMAIL_TEMPLATE);
        $EMAIL_TEMPLATE = str_replace("{{__CUSTOMER_PASSWORD__}}",$CheckCustomer['password'],$EMAIL_TEMPLATE);
        $EMAIL_TEMPLATE = str_replace("{{__DISCOUNT_CODE__}}",$CheckCustomer['coupon_code'],$EMAIL_TEMPLATE);
        $EMAIL_TEMPLATE = str_replace("{{__COUPON_AMOUNT__}}",abs($CheckCustomer['balance']),$EMAIL_TEMPLATE);



        foreach (__BASIC_EMAIL_SETTING__ as $TokenKey => $TokenValue) {
            if($TokenKey !== 'template_path'){
                $EMAIL_TEMPLATE = str_replace($TokenKey,$TokenValue,$EMAIL_TEMPLATE);
            }
        }

        $emailTemplate = $EMAIL_TEMPLATE;



        

        if(__SMTP_TYPE__ == "MAILGUN"){           
            $MailgunEmailData = [
                'from'	    => __FROM_NAME__.' <'.__FROM_EMAIL_ID__.'>',
                'to'	    => $CheckCustomer['name'].' <'.$CheckCustomer['email_address'].'>',
                'subject'   => 'Customer Welcome',
                'html'	    =>  $emailTemplate,
            ];

            try {
                $mgClient = Mailgun::create(__MAILGUN_API__);
                $result = $mgClient->messages()->send(__MAILGUN_DOMAIN__, $MailgunEmailData);
                

                $updateMailData = Array (
                    'mail_status' => 'Sent',
                    'mail_response' => json_encode($CheckCustomer)
                );
                if($isForced == '0'){
                    $updateMailData['webhook_response'] =$ShopifyCustomerRawData;
                }
            
            
                $db->where ('id', $CheckCustomer['id']);
                if ($db->update ('shopify_customers', $updateMailData))
                echo "Mail Sent Successfully";
            } catch (Exception $e) {
                $updateMailData = Array (
                    'mail_status' => 'Failed',
                );
                if($isForced == '0'){
                    $updateMailData['webhook_response'] =$ShopifyCustomerRawData;
                }
            
                $db->where ('id', $CheckCustomer['id']);
                $db->update ('shopify_customers', $updateMailData);
                echo "We Are Unable To Send The Mail. Try Agian.";
            }

        }elseif (__SMTP_TYPE__ == "SENDGRID") {
            
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom(__FROM_EMAIL_ID__, __FROM_NAME__);
            $email->setSubject("Customer Welcome");
            $email->addTo($CheckCustomer['email_address'], $CheckCustomer['name']);
            $email->addContent("text/html", $emailTemplate);
            $sendgrid = new \SendGrid(__SENDGRID_API__);
            try {
                $response = $sendgrid->send($email);
                $responseArr = [
                    'statusCode' => $response->statusCode(),
                    'headers' => $response->headers(),
                    'body' => $response->body(),
                    'raw' => $response,
                ];
                $updateMailData = Array (
                    'mail_status' => 'Sent',
                    'mail_response' => json_encode($responseArr,true),
                );
                if($isForced == '0'){
                    $updateMailData['webhook_response'] =$ShopifyCustomerRawData;
                }
            
            
                $db->where ('id', $CheckCustomer['id']);
                if ($db->update ('shopify_customers', $updateMailData))
                echo "Mail Sent Successfully";
            } catch (Exception $e) {
                
                $updateMailData = Array (
                    'mail_status' => 'Failed',
                    'mail_response' => $e->getMessage(),
                );
                if($isForced == '0'){
                    $updateMailData['webhook_response'] =$ShopifyCustomerRawData;
                }
            
                $db->where ('id', $CheckCustomer['id']);
                $db->update ('shopify_customers', $updateMailData);
                echo "We Are Unable To Send The Mail. Try Agian. (Caught exception: ".$e->getMessage()." )";
                
            }
        }
        elseif(__SMTP_TYPE__ == "MAILJET"){
                
            $mj = new \Mailjet\Client(__MAILJET_USERNAME__, __MAILJET_PASSWORD__,true,['version' => 'v3.1']);
            
            // Define your request body
            
            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => __FROM_EMAIL_ID__,
                            'Name' => __FROM_NAME__
                        ],
                        'To' => [
                            [
                                'Email' => $CheckCustomer['email_address'],
                                'Name' => $CheckCustomer['name']
                            ]
                        ],
                        'Subject' => "Customer Welcome",
                        'HTMLPart' => $emailTemplate
                    ]
                ]
            ];
            
            // All resources are located in the Resources class
            
            $response = $mj->post(Resources::$Email, ['body' => $body]);
            
            if($isForced == '0'){
                $updateMailData['webhook_response'] =$ShopifyCustomerRawData;
            }
            // Read the response
            if($response->success()){
                $updateMailData = Array (
                    'mail_status' => 'Sent',
                    'mail_response' =>  json_encode($response->getData(),true),
                );
                
                
                $db->where ('id', $CheckCustomer['id']);
                if ($db->update ('shopify_customers', $updateMailData))
                echo "Mail Sent Successfully";
            }else{
                $updateMailData = Array (
                    'mail_status' => 'Failed',
                    'mail_response' => json_encode($response->getData(),true),
                );
                if($isForced == '0'){
                    $updateMailData['webhook_response'] =$ShopifyCustomerRawData;
                } 
                
                
                $db->where ('id', $CheckCustomer['id']);
                $db->update ('shopify_customers', $updateMailData);
                
                // Read the response
                $MailResponse = $response->getData();
                echo "We Are Unable To Send The Mail. Try Agian. (Caught exception: ".$MailResponse['ErrorMessage']." )";
            }
            
        }

    }else{
        
    }
}else{

}