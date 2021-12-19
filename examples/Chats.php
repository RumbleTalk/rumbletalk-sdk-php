<?php

/**
 * See more about the chat routes here:
 * https://dev.rumbletalk.com/rest/chats
 */

namespace Examples;

require_once '../vendor/autoload.php';

use RumbleTalk\RumbleTalkClient;

$appKey = 'YOUR_TOKEN_KEY';
$appSecret = 'YOUR_TOKEN_SECRET';

# create the RumbleTalk Client instance using the key and secret
$rumbletalk = new RumbleTalkClient($appKey, $appSecret);

# fetch (and set) the access token for the account (tokens last for 30 days)
$rumbletalk->fetchAccessToken();


# create a chat
//$data = ['name' => 'Chat #1'];
//$result = $rumbletalk->post('chats', $data);

# get all the account's chat rooms
$result = $rumbletalk->get('chats');

# get a specific chat
//$chatId = 0;
//$result = $rumbletalk->get("chats/$chatId");

# update a chat
//$chatId = 0;
//$data = ['name' => 'New example chat name'];
//$result = $rumbletalk->put("chats/$chatId", $data);

# delete a chat
//$chatId = 0;
//$result = $rumbletalk->delete("chats/$chatId");

print_r($result);
