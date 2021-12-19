<?php

/**
 * Last updated at 18-DEC-2021
 * See more about the account routes here:
 * https://dev.rumbletalk.com/rest/account
 */

namespace Examples;

require_once '../vendor/autoload.php';

use RumbleTalk\RumbleTalkClient;

$appKey = 'YOUR_TOKEN_KEY';
$appSecret = 'YOUR_TOKEN_SECRET';

# create the RumbleTalk SDK instance using the key and secret
$rumbletalk = new RumbleTalkClient($appKey, $appSecret);

# fetch (and set) the access token for the account (tokens lasts for 30 days)
$rumbletalk->fetchAccessToken();


# retrieve your account info
$result = $rumbletalk->get('account');

# update your account info
//$data = ['name' => 'Nim'];
//$result = $rumbletalk->put('account', $data);

# set your account currency
/** It is only possible to set the currency once! */
//$data = ['currency' => 'EUR'];
//$result = $rumbletalk->put('account/currency', $data);

print_r($result);
