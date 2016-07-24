<?php
require_once 'vendor/autoload.php';

use \GuzzleHttp\Client;

$client = new Client();

$tenant_name = '{ACCOUNT_NAME}';
$account_client_id = '{NON_INTERACTIVE_CLIENT_ID}';
$account_client_secret = '{NON_INTERACTIVE_CLIENT_SECRET}';
$user_id = '{USER_ID}';
$idp_api = '{IDP_API_CALL}';


//FETCH THE ACCESS TOKEN

$response = $client->request('POST', "https://$tenant_name.auth0.com/oauth/token", [
  'headers' => [
    'Content-Type' => 'application/json'
  ],
  'body' => json_encode([
    'client_id' => $account_client_id,
    'client_secret' => $account_client_secret,
    'audience' => "https://$tenant_name.auth0.com/api/v2/",
    'grant_type' => 'client_credentials',
  ])
]);
$body = (string) $response->getBody();
$decoded_body = json_decode($body, true);


// FETCH COMPLETE USER PROFILE
$user_id = urlencode($user_id);

$response = $client->request('GET', "https://$tenant_name.auth0.com/api/v2/users/$user_id", [
  'headers' => [
    'Content-Type' => 'application/json',
    'Authorization' => 'Bearer ' . $decoded_body['access_token']
  ]
]);
$body = (string) $response->getBody();
$user = json_decode($body, true);
list($provider, $id) = explode("|", $user['user_id']);

$identities = $user['identities'];
$idp_access_token = null;
foreach($identities as $identity) {
    if($identity['access_token'] != null && $identity['provider'] == $provider) {
        $idp_access_token = (string) $identity['access_token'];
        break;
    }
}
var_dump($idp_access_token);
if ($idp_access_token) {
  $response = $client->request('GET', $idp_api, [
    'headers' => [
      'Content-Type' => 'application/json',
      'Authorization' => 'Bearer ' . $idp_access_token
    ]
  ]);
  $idp_response = (string) $response->getBody();
  var_dump($idp_response);
}