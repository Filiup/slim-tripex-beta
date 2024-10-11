<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Slim\Exception\HttpNotFoundException;

require __DIR__ . '/../vendor/autoload.php';

// Načitame systémové premenné zo súboru .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__  . "/../");
$dotenv->load();

require __DIR__ . '/cors.php';

require __DIR__ . "/functions.php";
require __DIR__ . "/mailer.php";

$app = AppFactory::create();
$file = $_ENV["FILE"];


// Middleware ktorý do requestu pribalí Ip adresu klienta
$checkProxyHeaders = true;
$trustedProxies = ['10.0.0.1', '10.0.0.2'];
$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));



$app->get("/", function(Request $request, Response $response) use ($file) {
    // return $response->withJson(paymentsGet($file) );
    return $response->write("Payment server");
}); 


$app->get("/{id}", function(Request $request, Response $response, array $args) use ($file) {
    // PHP renderer 
    $renderer = new PhpRenderer("../views");

    // Pozrieme sa, že či daná platba existuje
    // Pokiaľ nie, navrátime status 404

    $payment = lookUpTheId(paymentsGet($file), $args["id"]);
    if (!$payment) return $response->withStatus(404)->write("Daná pladba bohužiaľ neexistuje");

    return $renderer->render($response, "index.php", $payment["data"]);
   
}); 





$app->post("/", function(Request $request, Response $response) use($file) {

    // Overíme, že či na server boli zaslané správne dáta
    $error = validate($request->getParam("data"));
    if ($error) return $response->withStatus(400)->write($error);

    $id = uniqid();

    // Server ip, port
    $server = $_SERVER["HTTP_HOST"];
    $url = $_ENV["PROTOCOL"] . "://$server/$id";


    // Odosielanie mailu
    
    try {
        sendMail($_ENV["MAIL"], $_ENV["MAIL_PASSWORD"], $request->getParam("data")["customer_mail"], $url, $request->getParam("data")["country"]);
    } catch(Exception $err) {
        return $response->withStatus(500)->write("Mail was not send: \n" . $err->getMessage());
    }
    

    // Odosielanie údajov platby na službu Cardpay

    $payment_data = [
        "MID" => $_ENV["CARDPAY_MID"],
        "AMT" => $request->getParam("data")["order_amount"],
        "CURR" => $request->getParam("data")["currency"] == "EUR" ? 978 : 203, // Pokiaľ nerozumiete tomuto riadku tak si treba vygoogliť "PHP ternary operator"
        "VS" => $request->getParam("data")["order_number"],
        "RURL" => $_ENV["CARDPAY_RURL"],
        "IPC" => $request->getAttribute('ip_address'), 
        "NAME" => $request->getParam("data")["customer_mail"],
        "REM" => $_ENV["CARDPAY_REM"],
        "TIMESTAMP" => date('dmYHis', time()) //d=deň, m=mesiac, Y=Rok, H=hodina (24 hodinový formát), i=minúta, s=sekunda

    ];


    $key = $_ENV["CARDPAY_KEY"]; // Verjený kľúč
    $stringToSign = getStringToSign($payment_data); 

    $keyBytes = pack("H*" , $key); // konverzia do binárneho formátu
    $signature = hash_hmac("sha256", $stringToSign, $keyBytes);

    $payment_data["HMAC"] = $signature; // Podpis pridáme do poľa pod kľúčom HMAC


    // Dáta su už pripravené na odoslanie službe Cardpay

    $resp = payment($payment_data);

    // Data, ktoré zapisujeme do JSON databázy

    $user_data = $request->getParam("data");

    // Do $user_data pridáme URL Cardpay platby
    $user_data["cardpay_url"] = $resp;

    $json_data = [
        "id" => $id,
        "data" => $user_data
    ];

    paymentsPush($file, $json_data);
    return $response->write($url);


});

$app->post("/login", function(Request $request, Response $response) {
    $login = $request->getParam("login");
    $password = $request->getParam("password");

    if ($password != $_ENV["FORM_PASSWD"] || $login != $_ENV["FORM_LOGIN"])  return $response->withStatus(401)->write("The password you have provided is not correct");
    return $response->withStatus(200);

});

// Manuálne premazanie celého JSONu (Pre administrátora)
$app->delete("/reset", function(Request $request, Response $response) use ($file) {

    try {
        file_put_contents($file, "");
        return $response->write("JSON cleared !");
    } catch(Exception $err) {
        return $response->withStatus(400)->write($err->getMessage());
    }
    

});


$app->run();