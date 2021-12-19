<?php

/**
 * Last updated at 18-DEC-2021
 * See more about the messages routes here:
 * https://dev.rumbletalk.com/rest/messages
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


# send a system message
//$chatId = 0;
//$data = ['text' => 'rumbletalk.com'];
//$result = $rumbletalk->post("chats/$chatId/messages", $data);

# get all the messages of a chat
$chatId = 0;
$result = $rumbletalk->get("chats/$chatId/messages");

# delete all the chat's messages
//$chatId = 0;
//$result = $rumbletalk->delete("chats/$chatId/messages");

# delete a message of a chat
//$chatId = 0;
//$messageId = 0;
//$result = $rumbletalk->delete("chats/$chatId/messages/$messageId");


print_r($result);
