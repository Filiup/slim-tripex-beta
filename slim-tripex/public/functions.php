<?php
use Validation\Validation as V;

function paymentsPush($file, $data) {
    // Údaje o pladbách ukladáme do JSON súboru ( primitívna databáza )

    // Obsah súboru payments.json
    $json = file_get_contents($file);

    // Json súbor zmeníme na PHP array
    $json_array = json_decode($json, true);

    // Na koniec poľa pridáme novú pladbu
    $json_array[] = $data;

    // Pole môže obsahovať maximálne $limit pladieb
    // Pokiaľ obsahuje viac, zmažeme prvú pladbu

    $limit = intval( $_ENV["LIMIT"] );
    if ( count($json_array) >= $limit + 1 ) {
        \array_splice($json_array, 0, 1);
    }
    
    // Do súboru payments.json zapíšeme naše novo vytvorené pole
    file_put_contents($file, json_encode($json_array) . "\n");
    
}



function paymentsGet($file) {
    $json = file_get_contents($file);
    $json_array = json_decode($json, true);
    return $json_array;
}

function validate($data) {
    $error = false;
    // Validation 
    $schema = V::arr()->keys([
        "customer_mail" => V::string()->required()->email(),
        "agent_mail" =>  V::string()->required()->email()->valid("info@tripex.sk", "info@sk.fcm.travel"),
        "order_amount" => V::string()->required()->notEmpty(),
        "order_number" => V::string()->required()->notEmpty(),
        "note" => V::string()->defaultValue(""),
        "currency" => V::string()->valid("EUR", "CZK")->required(),
        "name_surname"=> V::string()->required()->notEmpty(),

        "company" => V::string()->defaultValue(""),
        "street" => V::string()->defaultValue(""),
        "street_2"=> V::string()->defaultValue(""),
        "postal_code" => V::string()->defaultValue(""),
        "city" => V::string()->defaultValue(""),
        "country"=> V::string()->defaultValue("")
    ]);

    V::validate($data, $schema, function($err, $output) use(&$error){
        if ($err) {
            $error = $err;
        }

    });

    return $error;
  
}

function lookUpTheId($payments, $id) {
    foreach($payments as $payment) {
        if ($payment["id"] == $id) {
            return $payment;
        }
    }

    return false;
}


function payment($data_array) {
    $data = http_build_query($data_array); // Konvertovanie poľa na URL Encoded format
    $url = $_ENV["CARDPAY_URL"];
    return $url . "?" . $data;

} 

function getStringToSign($data) {
    $stringToSign = "";
    foreach($data as $param) {
        $stringToSign = $stringToSign . $param;
    }

    return $stringToSign;

}

