<?php

/**
 * Last updated at 18-DEC-2021
 * See more about the banned ips routes here:
 * https://dev.rumbletalk.com/rest/banned-ips
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


# ban an IP address in a chat
//$chatId = 0;
//$data = ['ip' => '1.1.1.1'];
//$result = $rumbletalk->post("chats/$chatId/banned-ips", $data);

# get all the IP address of a chat
$chatId = 0;
$result = $rumbletalk->get("chats/$chatId/banned-ips");

# delete an IP address of a chat
//$chatId = 0;
//$bannedIp = '1.1.1.1';
//$result = $rumbletalk->delete("chats/$chatId/banned-ips/$bannedIp");


print_r($result);
