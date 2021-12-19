<?php

/**
 * See more about the authentication route here:
 * https://dev.rumbletalk.com/rest/authentication
 */

namespace Examples;

require_once '../vendor/autoload.php';

use RumbleTalk\RumbleTalkClient;

$appKey = 'YOUR_TOKEN_KEY';
$appSecret = 'YOUR_TOKEN_SECRET';

# create the RumbleTalk SDK instance using the key and secret
$rumbletalk = new RumbleTalkClient($appKey, $appSecret);

# fetch (and set) the access token for the account (tokens lasts for 30 days)
$accessToken = $rumbletalk->fetchAccessToken();

/************************************************************
 * tokens are valid for 30 days.                            *
 * it is recommended to reuse the token until it expires    *
 * because there's an hourly, daily, and monthly quota      *
 * on access token creation requests                        *
 ************************************************************/

// $db = new PDO(); # some DB implementation

# save the $accessToken in the DB
//$db->save($accessToken);

# retrieve the token from the DB
//$accessToken = $db->getToken();

if ($rumbletalk->renewalNeeded($accessToken)) {
    echo 'Token needs to be renewed';
    $accessToken = $rumbletalk->fetchAccessToken();
    // save the token to the DB
}
else {
    echo 'token still valid';
    // continue with the code
}
