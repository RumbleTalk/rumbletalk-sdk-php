<?php

/**
 * See more about the design routes here:
 * https://dev.rumbletalk.com/rest/design
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


# get a chat's design
$chatId = 0;
$result = $rumbletalk->get("chats/$chatId/design");

# update a chat's design
//$chatId = 0;
//$data = ['dlgBg' => 'aaaaaa'];
//$result = $rumbletalk->put("chats/$chatId/design", $data);

# change the chat's skin
/** NOTE: changing the chat's skin will RESET the chat's design variables */
//$chatId = 0;
//$data = ['skinId' => 40000];
//$result = $rumbletalk->put("chats/$chatId/design/skin", $data);

print_r($result);
